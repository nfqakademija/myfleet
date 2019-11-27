<?php

namespace App\Service;

use App\Entity\Vehicle;
use App\Entity\VehicleDataEntry;
use DateTime;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\Common\Persistence\ObjectManager;

class VehicleDataImportService
{
    protected $requestStack;

    private $url;

    private $httpClient;

    private $entityManager;

    private $vehicles;

    private $vehicleDataEntry;

    public function __construct(
        KernelInterface $kernel,
        RequestStack $requestStack,
        HttpClientInterface $httpClient,
        ObjectManager $manager
    ) {
        $this->requestStack = $requestStack;
        $request = $this->requestStack->getCurrentRequest();
        if ($kernel->getEnvironment() == 'prod') {
            $this->url = $request->getSchemeAndHttpHost().':'.$request->getPort().'/demo/api/vehicle_data/';
        } else {
            $this->url = 'http://host.docker.internal:8000/demo/api/vehicle_data/';
        }

        $this->httpClient = $httpClient;
        $this->entityManager = $manager;
        $this->vehicles = $manager->getRepository(Vehicle::class)->findAll();
        $this->vehicleDataEntry = $manager->getRepository(VehicleDataEntry::class);
    }

    public function importAndUpdateVehicleDataEntry()
    {
        foreach ($this->vehicles as $vehicle) {
            $response = $this->httpClient->request('GET', $this->url.$vehicle->getVin());
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
