<?php

namespace App\Service\VehicleDataProcessor;

use App\Entity\Event;
use App\Entity\VehicleDataEntry;
use App\Repository\VehicleDataEntryRepository;
use App\Service\InstantNotificationCreator;
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
     * @var InstantNotificationCreator
     */
    private $instantNotificationCreator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param VehicleDataEntryRepository $vehicleDataEntryRepository
     * @param InstantNotificationCreator $instantNotificationCreator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        VehicleDataEntryRepository $vehicleDataEntryRepository,
        InstantNotificationCreator $instantNotificationCreator
    ) {
        $this->entityManager = $entityManager;
        $this->vehicleDataEntryRepository = $vehicleDataEntryRepository;
        $this->instantNotificationCreator = $instantNotificationCreator;
    }

    /**
     * @param VehicleDataEntry $vehicleDataEntry
     */
    public function process(VehicleDataEntry $vehicleDataEntry): void
    {
        $vehicle = $vehicleDataEntry->getVehicle();
        if (null === $vehicle) {
            return;
        }

        $previous = $this->vehicleDataEntryRepository->getPreviousRecord($vehicle);

        if (null === $previous) {
            return;
        }

        if (false === $this->isInLatvia($previous) && $this->isInLatvia($vehicleDataEntry)) {
            $this->addEventToVehicle($vehicleDataEntry);
            $this->instantNotificationCreator->execute(
                $vehicle,
                $vehicleDataEntry->getEventTime(),
                'Transporto priemonė išvyko iš Lietuvos: '
            );
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
     * @param VehicleDataEntry $dataEntry
     *
     * @return bool
     */
    private function isInLatvia(VehicleDataEntry $dataEntry): bool
    {
        return (56.45 < $dataEntry->getLatitude());
    }
}
