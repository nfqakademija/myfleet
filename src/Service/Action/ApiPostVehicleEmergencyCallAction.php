<?php

namespace App\Service\Action;

use App\Entity\Event;
use App\Entity\Vehicle;
use App\Repository\VehicleRepository;
use App\Service\InstantNotificationCreator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiPostVehicleEmergencyCallAction
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var VehicleRepository
     */
    private $vehicleRepository;

    /**
     * @var InstantNotificationCreator
     */
    private $instantNotificationCreator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param VehicleRepository $vehicleRepository
     * @param InstantNotificationCreator $instantNotificationCreator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        VehicleRepository $vehicleRepository,
        InstantNotificationCreator $instantNotificationCreator
    ) {
        $this->entityManager = $entityManager;
        $this->vehicleRepository = $vehicleRepository;
        $this->instantNotificationCreator = $instantNotificationCreator;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws Exception
     */
    public function execute(Request $request): Response
    {
        $vehicle = $this->getVehicle($request);
        if (is_null($vehicle)) {
            return new JsonResponse([
                'status' => 'error',
                'errorCode' => 404,
                'errorMessage' => 'Vehicle Not Found!'
            ]);
        }

        $this->addEventToVehicle($vehicle);
        $this->instantNotificationCreator->execute(
            $vehicle,
            new DateTime(),
            'Gautas SOS signalas: '
        );

        return new JsonResponse(['status' => 'success']);
    }

    /**
     * @param Request $request
     *
     * @return Vehicle|null
     */
    private function getVehicle(Request $request): ?Vehicle
    {
        $vin = $request->attributes->get('vin');

        return $this->vehicleRepository->findOneBy(['vin' => $vin]);
    }

    /**
     * @param Vehicle $vehicle
     *
     * @throws Exception
     */
    private function addEventToVehicle(Vehicle $vehicle)
    {
        $event = new Event();
        $event->setVehicle($vehicle);
        $event->setCreatedAt(new DateTime());
        $event->setDescription('Paspaustas SOS mygtukas!');

        $this->entityManager->persist($event);
        $this->entityManager->flush();
    }
}
