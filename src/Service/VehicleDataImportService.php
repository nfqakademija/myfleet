<?php

namespace App\Service;

use App\Entity\Vehicle;
use App\Entity\VehicleDataEntry;
use App\Repository\VehicleDataEntryRepository;
use App\Repository\VehicleRepository;
use DateTime;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\Common\Persistence\ObjectManager;

class VehicleDataImportService
{
    /**
     * @var HttpClientInterface
     */
    private $httpClient;

    /**
     * @var ObjectManager
     */
    private $entityManager;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @param HttpClientInterface $httpClient
     * @param ObjectManager $manager
     * @param string $apiUrl
     */
    public function __construct(
        HttpClientInterface $httpClient,
        ObjectManager $manager,
        string $apiUrl
    ) {
        $this->httpClient = $httpClient;
        $this->entityManager = $manager;
        $this->apiUrl = $apiUrl;
    }

    public function execute()
    {
        /** @var VehicleRepository $vehicles */
        $vehicles = $this->entityManager->getRepository(Vehicle::class);
        foreach ($vehicles->findAll() as $vehicle) {
            try {
                $response = $this->httpClient->request(
                    'GET',
                    $this->apiUrl.$vehicle->getVin()
                );
                /** @var VehicleDataEntryRepository $vehicleDataEntry */
                $vehicleDataEntry = $this->entityManager->getRepository(VehicleDataEntry::class);
                $lastEventTime = $vehicleDataEntry->findOneBy(['vehicle' => $vehicle], ['eventTime' => 'DESC']);
                if (isset($lastEventTime)) {
                    $lastEventTime = $lastEventTime->getEventTime();
                } else {
                    $lastEventTime = new DateTime('-1 day');
                }

                if (200 !== $response->getStatusCode()) {
                    continue;
                }
                $content = $response->getContent();
                $data = json_decode($content);

                foreach ($data as $record) {
                    if ($lastEventTime >= new DateTime($record->eventTime)) {
                        continue;
                    }
                    $vehicleDataEntry = new VehicleDataEntry();
                    $vehicleDataEntry->setVehicle($vehicle);
                    $vehicleDataEntry->setLatitude($record->latitude);
                    $vehicleDataEntry->setLongitude($record->longitude);
                    $vehicleDataEntry->setMileage($record->mileage);
                    $vehicleDataEntry->setEventTime(new DateTime($record->eventTime));

                    $this->entityManager->persist($vehicleDataEntry);
                }
                $this->entityManager->flush();
            } catch (TransportExceptionInterface $e) {
                return $e->getMessage();
            }
        }
    }
}
