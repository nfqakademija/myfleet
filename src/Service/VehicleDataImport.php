<?php

namespace App\Service;

use App\Entity\Vehicle;
use App\Entity\VehicleDataEntry;
use App\Repository\VehicleDataEntryRepository;
use App\Repository\VehicleRepository;
use App\Service\VehicleDataProcessor\VehicleDataProcessorInterface;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class VehicleDataImport
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
     * @var VehicleDataEntryRepository
     */
    private $vehicleDataEntryRepository;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var VehicleDataProcessorInterface[]
     */
    private $processors;

    /**
     * @param HttpClientInterface $httpClient
     * @param VehicleRepository $vehicleRepository
     * @param VehicleDataEntryRepository $vehicleDataEntryRepository
     * @param EntityManagerInterface $entityManager
     * @param string $apiUrl
     * @param array $processors
     */
    public function __construct(
        HttpClientInterface $httpClient,
        VehicleRepository $vehicleRepository,
        VehicleDataEntryRepository $vehicleDataEntryRepository,
        EntityManagerInterface $entityManager,
        string $apiUrl,
        array $processors
    ) {
        $this->httpClient = $httpClient;
        $this->vehicleRepository = $vehicleRepository;
        $this->vehicleDataEntryRepository = $vehicleDataEntryRepository;
        $this->entityManager = $entityManager;
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
     * @throws Exception
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
                $data = array_reverse($data);

                $lastEventTime = $this->getLastEventTime($vehicle);

                foreach ($data as $row) {
                    if ($lastEventTime >= new DateTime($row['eventTime'])) {
                        continue;
                    }
                    $vehicleDataEntry = $this->fillEntity($vehicle, $row);
                    $this->entityManager->persist($vehicleDataEntry);

                    $this->runProcessors($vehicleDataEntry);
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
        $lastEvent = $this->vehicleDataEntryRepository->getLastEntry($vehicle);
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
     * @return VehicleDataEntry
     *
     * @throws Exception
     */
    private function fillEntity(Vehicle $vehicle, array $row)
    {
        $vehicleDataEntry = new VehicleDataEntry();
        $vehicleDataEntry->setVehicle($vehicle);
        $vehicleDataEntry->setLatitude($row['latitude']);
        $vehicleDataEntry->setLongitude($row['longitude']);
        $vehicleDataEntry->setMileage($row['mileage']);
        $vehicleDataEntry->setEventTime(new DateTime($row['eventTime']));

        return $vehicleDataEntry;
    }

    /**
     * @param VehicleDataEntry $vehicleDataEntry
     */
    private function runProcessors(VehicleDataEntry $vehicleDataEntry): void
    {
        foreach ($this->processors as $processor) {
            $processor->process($vehicleDataEntry);
        }
    }
}
