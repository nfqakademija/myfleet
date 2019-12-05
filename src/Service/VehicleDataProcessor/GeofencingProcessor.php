<?php

namespace App\Service\VehicleDataProcessor;

use App\Entity\Event;
use App\Entity\InstantNotification;
use App\Entity\Vehicle;
use App\Entity\VehicleDataEntry;
use App\Repository\VehicleDataEntryRepository;
use Doctrine\ORM\EntityManagerInterface;

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
     * @param EntityManagerInterface $entityManager
     * @param VehicleDataEntryRepository $vehicleDataEntryRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        VehicleDataEntryRepository $vehicleDataEntryRepository
    ) {
        $this->entityManager = $entityManager;
        $this->vehicleDataEntryRepository = $vehicleDataEntryRepository;
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

        if (56.45 > $previous->getLatitude() && 56.45 < $vehicleDataEntry->getLatitude()) {
            $event = new Event();
            $event->setVehicle($vehicleDataEntry->getVehicle());
            $event->setCreatedAt($vehicleDataEntry->getEventTime());
            $event->setDescription('vehicle_has_left_lithuania');

            $this->entityManager->persist($event);

            foreach ($vehicleDataEntry->getVehicle()->getUsers() as $user) {
                $instantNotification = new InstantNotification();
                $instantNotification->setUser($user);
                $instantNotification->setEventTime($vehicleDataEntry->getEventTime());
                $instantNotification->setIsSent(false);
                $instantNotification->setDescription('vehicle_has_left_lithuania');

                $this->entityManager->persist($instantNotification);
            }

            $this->entityManager->flush();
        }
    }
}
