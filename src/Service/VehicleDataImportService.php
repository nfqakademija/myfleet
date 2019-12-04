<?php

namespace App\Service;

use App\Entity\Vehicle;
use App\Entity\VehicleDataEntry;
use App\Repository\VehicleDataEntryRepository;
use App\Repository\VehicleRepository;
use DateTime;
use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
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
     * @param HttpClientInterface $httpClient
     * @param VehicleRepository $vehicleRepository
     * @param ObjectManager $manager
     * @param string $apiUrl
     */
    public function __construct(
        HttpClientInterface $httpClient,
        VehicleRepository $vehicleRepository,
        VehicleDataEntryRepository $vehicleDataEntryRepository,
        ObjectManager $manager,
        string $apiUrl
    ) {
        $this->httpClient = $httpClient;
        $this->vehicleRepository = $vehicleRepository;
        $this->vehicleDataEntryRepository = $vehicleDataEntryRepository;
        $this->entityManager = $manager;
        $this->apiUrl = $apiUrl;
    }

    public function execute()
    {
        $vehicles = $this->getVehicles();
        foreach ($vehicles as $vehicle) {
            try {
                $response = $this->httpClient->request('GET', $this->getUrl($vehicle));

                if (200 !== $response->getStatusCode()) {
                    continue;
                }

                $lastEvent = $this->vehicleDataEntryRepository->findOneBy(
                    ['vehicle' => $vehicle],
                    ['eventTime' => 'DESC']
                );
                $lastEventTime = ($lastEvent->getEventTime() ?? new DateTime('-1 day'));

                $content = $response->getContent();
                $data = json_decode($content);

                foreach ($data as $row) {
                    if ($lastEventTime >= new DateTime($row->eventTime)) {
                        continue;
                    }
                    $vehicleDataEntry = new VehicleDataEntry();
                    $vehicleDataEntry->setVehicle($vehicle);
                    $vehicleDataEntry->setLatitude($row->latitude);
                    $vehicleDataEntry->setLongitude($row->longitude);
                    $vehicleDataEntry->setMileage($row->mileage);
                    $vehicleDataEntry->setEventTime(new DateTime($row->eventTime));

                    $this->entityManager->persist($vehicleDataEntry);
                }
                $this->entityManager->flush();
            } catch (TransportExceptionInterface $e) {
                return $e->getMessage();
            } catch (ClientExceptionInterface $e) {
                return $e->getMessage();
            } catch (RedirectionExceptionInterface $e) {
                return $e->getMessage();
            } catch (ServerExceptionInterface $e) {
                return $e->getMessage();
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
    }

    private function getVehicles()
    {
        return $this->vehicleRepository->findAll();
    }

    private function getUrl(Vehicle $vehicle)
    {
        return $this->apiUrl.$vehicle->getVin();
    }
}
