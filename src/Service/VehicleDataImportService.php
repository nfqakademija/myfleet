<?php

namespace App\Service;

use App\Entity\Vehicle;
use App\Entity\VehicleDataEntry;
use App\Repository\VehicleDataEntryRepository;
use App\Repository\VehicleRepository;
use DateTime;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\RequestStack;

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
     * @var Vehicle[]|object[]
     */
    private $vehicles;

    /**
     * @var VehicleDataEntryRepository|ObjectRepository
     */
    private $vehicleDataEntry;

    public function __construct(
        HttpClientInterface $httpClient,
        ObjectManager $manager,
        VehicleRepository $vehicles,
        VehicleDataEntryRepository $vehicleDataEntryRepository
    ) {
        $this->httpClient = $httpClient;
        $this->entityManager = $manager;
        $this->vehicles = $vehicles;
        $this->vehicleDataEntry = $vehicleDataEntryRepository;
    }

    public function execute()
    {
        foreach ($this->vehicles->findAll() as $vehicle) {
            $response = $this->httpClient->request(
                'GET',
                $_ENV['API_URL_VEHICLE_DATA'].$vehicle->getVin()
            );
            $lastEventTime = $this->vehicleDataEntry->findOneBy(['vehicle' => $vehicle]);
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
        }
    }
}
