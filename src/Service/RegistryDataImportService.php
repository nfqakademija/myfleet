<?php

namespace App\Service;

use App\Entity\RegistryDataEntry;
use App\Entity\Vehicle;
use DateTime;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\Common\Persistence\ObjectManager;

class RegistryDataImportService
{
    protected $requestStack;

    private $url;

    private $httpClient;

    private $entityManager;

    private $vehicles;

    private $registryDataEntry;

    public function __construct(
        KernelInterface $kernel,
        RequestStack $requestStack,
        HttpClientInterface $httpClient,
        ObjectManager $manager
    ) {
        $this->requestStack = $requestStack;
        $request = $this->requestStack->getCurrentRequest();
        if ($kernel->getEnvironment() == 'prod') {
            $this->url = $request->getSchemeAndHttpHost().':'.$request->getPort().'/demo/api/registry_data/';
        } else {
            $this->url = 'http://host.docker.internal:8000/demo/api/registry_data/';
        }

        $this->httpClient = $httpClient;
        $this->entityManager = $manager;
        $this->vehicles = $manager->getRepository(Vehicle::class)->findAll();
        $this->registryDataEntry = $manager->getRepository(RegistryDataEntry::class);
    }

    public function importAndUpdateRegistryDataEntry()
    {
        foreach ($this->vehicles as $vehicle) {
            $response = $this->httpClient->request('GET', $this->url.$vehicle->getVin());
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
