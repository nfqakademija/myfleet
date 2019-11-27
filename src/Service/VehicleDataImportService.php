<?php

namespace App\Service;

use App\Entity\Vehicle;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\Common\Persistence\ObjectManager;

class VehicleDataImportService
{
    private $url = 'http://127.0.0.1:8000/demo/api/vehicle_data/';

    private $httpClient;

    private $entityManager;

    private $vehicles;

    public function __construct(HttpClientInterface $httpClient, ObjectManager $manager)
    {
        $this->httpClient = $httpClient;
        $this->entityManager = $manager;//$this->getDoctrine()->getManager();
        $this->vehicles = $manager->getRepository(Vehicle::class)->findAll();
    }

    public function importAndUpdateRegistryDataEntry()
    {
        foreach ($this->vehicles as $vehicle) {
            $response = $this->httpClient->request('GET', $this->url.$vehicle->getVin());

            if (200 === $response->getStatusCode()) {
                $content = $response->getContent();
                $data = json_decode($content, true);

                foreach ($data as $record) {
                    $vehicle->addVehicleDataEntry($record);
                    $this->entityManager->persist($vehicle);
                }
            }
            $this->entityManager->flush();
        }
    }
}
