<?php

namespace App\Service\VehicleDataProcessor;

use App\Entity\Event;
use App\Entity\InstantNotification;
use App\Entity\VehicleDataEntry;
use App\Repository\VehicleDataEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class GeofencingProcessor implements VehicleDataProcessorInterface
{
    /**
     * @var Security
     */
    private $security;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var VehicleDataEntryRepository
     */
    private $vehicleDataEntryRepository;

    /**
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     * @param VehicleDataEntryRepository $vehicleDataEntryRepository
     */
    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager,
        VehicleDataEntryRepository $vehicleDataEntryRepository
    ) {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->vehicleDataEntryRepository = $vehicleDataEntryRepository;
    }

    public function process(VehicleDataEntry $vehicleDataEntry)
    {
        $previous = $this->vehicleDataEntryRepository->getPreviousRecord($vehicleDataEntry->getVehicle());

        if (56.45 < $previous->getLatitude() && 56.45 >= $vehicleDataEntry->getLatitude()) {
            $event = new Event();
            $event->setVehicle($vehicleDataEntry->getVehicle());
            $event->setCreatedAt($vehicleDataEntry->getEventTime());
            $event->setDescription('vehicle_has_left_lithuania');

            $this->entityManager->persist($event);

            $instantNotification = new InstantNotification();
            $instantNotification->setUser($this->security->getUser());
            $instantNotification->setEventTime($vehicleDataEntry->getEventTime());
            $instantNotification->setIsSent(false);
            $instantNotification->setDescription('vehicle_has_left_lithuania');

            $this->entityManager->persist($instantNotification);

            $this->entityManager->flush();
        }
    }
}
