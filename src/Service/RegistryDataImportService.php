<?php

namespace App\Service;

use App\Entity\RegistryDataEntry;
use App\Entity\Vehicle;
use App\Repository\RegistryDataEntryRepository;
use App\Repository\VehicleRepository;
use DateTime;
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
     * RegistryDataImportService constructor.
     * @param HttpClientInterface $httpClient
     * @param ObjectManager $manager
     */
    public function __construct(
        HttpClientInterface $httpClient,
        ObjectManager $manager
    ) {
        $this->httpClient = $httpClient;
        $this->entityManager = $manager;
    }

    public function execute()
    {
        /** @var VehicleRepository $vehicles */
        $vehicles = $this->entityManager->getRepository(Vehicle::class);
        foreach ($vehicles->findAll() as $vehicle) {
            $response = $this->httpClient->request(
                'GET',
                $_ENV['API_URL_REGISTRY_DATA'].$vehicle->getVin()
            );
            /** @var RegistryDataEntryRepository $registryDataEntry */
            $registryDataEntry = $this->entityManager->getRepository(RegistryDataEntry::class);
            $lastEventTime = $registryDataEntry->findOneBy(['vehicle' => $vehicle], ['eventTime' => 'DESC']);
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
