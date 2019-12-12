<?php

namespace App\Service;

use App\Entity\RegistryDataEntry;
use App\Entity\Vehicle;
use App\Repository\RegistryDataEntryRepository;
use App\Repository\UserRepository;
use App\Repository\VehicleRepository;
use App\Service\RegistryDataProcessor\RegistryDataProcessorInterface;
use App\Service\VehicleDataProcessor\VehicleDataProcessorInterface;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RegistryDataImport
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var VehicleRepository
     */
    private $vehicleRepository;

    /**
     * @var RegistryDataEntryRepository
     */
    private $registryDataEntryRepository;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var RegistryDataProcessorInterface[]
     */
    private $processors;

    /**
     * @param HttpClientInterface $httpClient
     * @param VehicleRepository $vehicleRepository
     * @param RegistryDataEntryRepository $registryDataEntryRepository
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param RouterInterface $router
     * @param string $apiUrl
     * @param array $processors
     */
    public function __construct(
        HttpClientInterface $httpClient,
        VehicleRepository $vehicleRepository,
        RegistryDataEntryRepository $registryDataEntryRepository,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        RouterInterface $router,
        string $apiUrl,
        array $processors
    ) {
        $this->httpClient = $httpClient;
        $this->vehicleRepository = $vehicleRepository;
        $this->registryDataEntryRepository = $registryDataEntryRepository;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->router = $router;
        $this->apiUrl = $apiUrl;
        $this->processors = $processors;
    }

    /**
     * @return void
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function execute(): void
    {
        $vehicles = $this->getVehicles();
        foreach ($vehicles as $vehicle) {
            try {
                $response = $this->doApiRequest($vehicle);
                if (null === $response) {
                    continue;
                }
                $data = $this->parseApiData($response);
                $lastEventTime = $this->getLastEventTime($vehicle);

                foreach ($data as $row) {
                    if ($lastEventTime >= new DateTime($row['publishedAt'])) {
                        continue;
                    }
                    $registryDataEntry = $this->fillEntity($vehicle, $row);
                    $this->entityManager->persist($registryDataEntry);

                    $this->runProcessors($registryDataEntry);
                }
                $this->entityManager->flush();
            } catch (Exception $e) {
                throw $e;
            }
        }
    }

    /**
     * @return Vehicle[]
     */
    private function getVehicles()
    {
        return $this->vehicleRepository->findAll();
    }

    /**
     * @param Vehicle $vehicle
     *
     * @return string
     */
    private function getUrl(Vehicle $vehicle)
    {
        return $this->apiUrl . $vehicle->getVin();
    }

    /**
     * @param Vehicle $vehicle
     *
     * @return ResponseInterface|null
     *
     * @throws TransportExceptionInterface
     */
    private function doApiRequest(Vehicle $vehicle)
    {
        try {
            $response = $this->httpClient->request('GET', $this->getUrl($vehicle));

            if (200 !== $response->getStatusCode()) {
                return null;
            }

            return $response;
        } catch (TransportExceptionInterface $e) {
            throw $e;
        }
    }

    /**
     * @param ResponseInterface $response
     *
     * @return Exception|mixed|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface
     *
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    private function parseApiData(ResponseInterface $response)
    {
        try {
            $content = $response->getContent();

            return json_decode($content, true);
        } catch (ClientExceptionInterface $e) {
            throw $e;
        } catch (RedirectionExceptionInterface $e) {
            throw $e;
        } catch (ServerExceptionInterface $e) {
            throw $e;
        } catch (TransportExceptionInterface $e) {
            throw $e;
        }
    }

    /**
     * @param Vehicle $vehicle
     *
     * @return DateTimeInterface
     *
     * @throws Exception
     */
    private function getLastEventTime(Vehicle $vehicle): DateTimeInterface
    {
        $lastEvent = $this->registryDataEntryRepository->getLastEntry($vehicle);
        if (isset($lastEvent)) {
            $lastEventTime = $lastEvent->getEventTime();
        }
        $lastEventTime = ($lastEventTime ?? new DateTime('-1 day'));

        return $lastEventTime;
    }

    /**
     * @param Vehicle $vehicle
     * @param array $row
     *
     * @return RegistryDataEntry
     *
     * @throws Exception
     */
    private function fillEntity(Vehicle $vehicle, array $row)
    {
        $registryDataEntry = new RegistryDataEntry();
        $registryDataEntry->setVehicle($vehicle);
        $registryDataEntry->setStatus($row['status']);
        $registryDataEntry->setTechnicalInspectionValidTill(new DateTime($row['technicalInspectionValidTill']));
        $registryDataEntry->setIsAllowedDriving($row['isAllowedDriving']);
        $registryDataEntry->setIsInsured($row['isInsured']);
        $registryDataEntry->setIsPoliceSearching($row['isPoliceSearching']);
        $registryDataEntry->setEventTime(new DateTime($row['publishedAt']));

        return $registryDataEntry;
    }

    /**
     * @param RegistryDataEntry $registryDataEntry
     */
    private function runProcessors(RegistryDataEntry $registryDataEntry): void
    {
        foreach ($this->processors as $processor) {
            $processor->process($registryDataEntry);
        }
    }
}
