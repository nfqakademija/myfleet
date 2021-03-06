<?php

declare(strict_types=1);

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
use Throwable;

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
     * @throws Exception
     * @throws Throwable
     */
    public function execute(): void
    {
        $vehicles = $this->getVehicles();
        foreach ($vehicles as $vehicle) {
            try {
                $response = $this->doApiRequest($vehicle);
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
            } catch (Throwable $e) {
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
     * @return ResponseInterface
     *
     * @throws Throwable
     */
    private function doApiRequest(Vehicle $vehicle)
    {
        try {
            $response = $this->httpClient->request('GET', $this->getUrl($vehicle));

            if ($response->getStatusCode() !== 200) {
                throw new Exception('Unexpected Status Code');
            }

            return $response;
        } catch (Throwable $e) {
            throw $e;
        }
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
     * @param ResponseInterface $response
     *
     * @return Exception|mixed|ClientExceptionInterface|RedirectionExceptionInterface|ServerExceptionInterface|TransportExceptionInterface
     *
     * @throws Throwable
     */
    private function parseApiData(ResponseInterface $response)
    {
        try {
            $content = $response->getContent();

            return json_decode($content, true);
        } catch (Throwable $e) {
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
