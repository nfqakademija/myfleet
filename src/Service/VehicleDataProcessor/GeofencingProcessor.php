<?php

namespace App\Service\VehicleDataProcessor;

use App\Entity\Event;
use App\Entity\InstantNotification;
use App\Entity\VehicleDataEntry;
use App\Repository\VehicleDataEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

class GeofencingProcessor implements VehicleDataProcessorInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var VehicleDataEntryRepository
     */
    private $vehicleDataEntryRepository;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param EntityManagerInterface $entityManager
     * @param VehicleDataEntryRepository $vehicleDataEntryRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        VehicleDataEntryRepository $vehicleDataEntryRepository,
        RouterInterface $router
    ) {
        $this->entityManager = $entityManager;
        $this->vehicleDataEntryRepository = $vehicleDataEntryRepository;
        $this->router = $router;
    }

    public function process(VehicleDataEntry $vehicleDataEntry)
    {
        if (is_null($vehicleDataEntry->getVehicle())) {
            return;
        }

        $previous = $this->vehicleDataEntryRepository->getPreviousRecord($vehicleDataEntry->getVehicle());

        if (is_null($previous)) {
            return;
        }

        if (false === $this->isInLatvia($previous) && $this->isInLatvia($vehicleDataEntry)) {
            $this->addEventToVehicle($vehicleDataEntry);
            $this->addNotificationToUsers($vehicleDataEntry);
        }
    }

    /**
     * @param VehicleDataEntry $vehicleDataEntry
     */
    private function addEventToVehicle(VehicleDataEntry $vehicleDataEntry): void
    {
        $event = new Event();
        $event->setVehicle($vehicleDataEntry->getVehicle());
        $event->setCreatedAt($vehicleDataEntry->getEventTime());
        $event->setDescription('Transporto priemonė išvyko iš Lietuvos');

        $this->entityManager->persist($event);
        $this->entityManager->flush();
    }

    /**
     * @param VehicleDataEntry $vehicleDataEntry
     */
    private function addNotificationToUsers(VehicleDataEntry $vehicleDataEntry): void
    {
        $vehicle = $vehicleDataEntry->getVehicle();
        if (is_null($vehicle)) {
            return;
        }

        $users = $vehicle->getUsers();
        if (0 === count($users)) {
            return;
        }

        $linkToVehicle = $this->router->generate('vehicle_view', [
            'id' => $vehicle->getId(),
        ]);

        $description = 'Transporto priemonė išvyko iš Lietuvos: '
            . '<a href="' . $linkToVehicle . '">' . $vehicle->getPlateNumber() . '</a>';

        foreach ($users as $user) {
            $instantNotification = new InstantNotification();
            $instantNotification->setUser($user);
            $instantNotification->setEventTime($vehicleDataEntry->getEventTime());
            $instantNotification->setIsSent(false);
            $instantNotification->setDescription($description);

            $this->entityManager->persist($instantNotification);
        }

        $this->entityManager->flush();
    }

    /**
     * @param VehicleDataEntry $dataEntry
     * @return bool
     */
    private function isInLatvia(VehicleDataEntry $dataEntry): bool
    {
        return (56.45 < $dataEntry->getLatitude());
    }
}
