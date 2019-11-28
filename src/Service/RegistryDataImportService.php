<?php

namespace App\Service;

use App\Entity\RegistryDataEntry;
use App\Entity\Vehicle;
use App\Repository\RegistryDataEntryRepository;
use App\Repository\VehicleRepository;
use DateTime;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\Common\Persistence\ObjectManager;

class RegistryDataImportService
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
     * @var RegistryDataEntryRepository|ObjectRepository
     */
    private $registryDataEntry;

    /**
     * RegistryDataImportService constructor.
     * @param HttpClientInterface $httpClient
     * @param ObjectManager $manager
     * @param VehicleRepository $vehicles
     * @param RegistryDataEntryRepository $registryDataEntry
     */
    public function __construct(
        HttpClientInterface $httpClient,
        ObjectManager $manager,
        VehicleRepository $vehicles,
        RegistryDataEntryRepository $registryDataEntry
    ) {
        $this->httpClient = $httpClient;
        $this->entityManager = $manager;
        $this->vehicles = $vehicles;
        $this->registryDataEntry = $registryDataEntry;
    }

    public function execute()
    {
        foreach ($this->vehicles->findAll() as $vehicle) {
            $response = $this->httpClient->request(
                'GET',
                $_ENV['API_URL_REGISTRY_DATA'].$vehicle->getVin()
            );
            $lastEventTime = $this->registryDataEntry->findOneBy(['vehicle' => $vehicle]);
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
                if ($lastEventTime >= new DateTime($record->publishedAt)) {
                    continue;
                }
                $registryDataEntry = new RegistryDataEntry();
                $registryDataEntry->setVehicle($vehicle);
                $registryDataEntry->setStatus($record->status);
                $registryDataEntry->setTechnicalInspectionValidTill(
                    new DateTime($record->technicalInspectionValidTill)
                );
                $registryDataEntry->setIsAllowedDriving($record->isAllowedDriving);
                $registryDataEntry->setIsInsured($record->isInsured);
                $registryDataEntry->setIsPoliceSearching($record->isPoliceSearching);
                $registryDataEntry->setEventTime(new DateTime($record->publishedAt));

                $this->entityManager->persist($registryDataEntry);
            }
            $this->entityManager->flush();
        }
    }
}
