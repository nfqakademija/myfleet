<?php

namespace App\Service\Action;

use App\Entity\Event;
use App\Entity\InstantNotification;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Repository\UserRepository;
use App\Repository\VehicleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

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
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param EntityManagerInterface $entityManager
     * @param RouterInterface $router
     * @param VehicleRepository $vehicleRepository
     * @param UserRepository $userRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        VehicleRepository $vehicleRepository,
        UserRepository $userRepository,
        RouterInterface $router
    ) {
        $this->entityManager = $entityManager;
        $this->vehicleRepository = $vehicleRepository;
        $this->userRepository = $userRepository;
        $this->router = $router;
    }

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
        $this->addNotificationToUsers($vehicle);

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

    private function addNotificationToUsers(Vehicle $vehicle)
    {
        foreach ($this->userRepository->findByRole('ADMIN') as $user) {
            $this->addNotificationToUser($vehicle, $user);
        }

        foreach ($vehicle->getUsers() as $user) {
            $this->addNotificationToUser($vehicle, $user);
        }
    }

    private function addNotificationToUser(Vehicle $vehicle, User $user)
    {
        $linkToVehicle = $this->router->generate('vehicle_view', [
            'id' => $vehicle->getId(),
        ]);

        $description = 'Gautas SOS signalas: '
            . '<a href="' . $linkToVehicle . '">' . $vehicle->getPlateNumber() . '</a>';

        $instantNotification = new InstantNotification();
        $instantNotification->setUser($user);
        $instantNotification->setEventTime(new DateTime());
        $instantNotification->setIsSent(false);
        $instantNotification->setDescription($description);

        $this->entityManager->persist($instantNotification);
        $this->entityManager->flush();
    }
}
