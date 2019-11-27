<?php

namespace App\Service;

use App\Entity\Vehicle;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\Common\Persistence\ObjectManager;

class RegistryDataImportService
{
    private $url;

    protected $requestStack;

    private $httpClient;

    private $entityManager;

    private $vehicles;

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
    }

    public function importAndUpdateRegistryDataEntry()
    {
        foreach ($this->vehicles as $vehicle) {
            $response = $this->httpClient->request('GET', $this->url.$vehicle->getVin());

            if (200 === $response->getStatusCode()) {
                $content = $response->getContent();
                $data = json_decode($content, true);

                foreach ($data as $record) {
                    $vehicle->addRegistryDataEntry($record);
                    $this->entityManager->persist($vehicle);
                }
            }
            $this->entityManager->flush();
        }
    }
}
