<?php

namespace App\Service\RegistryDataProcessor;

use App\Entity\Event;
use App\Entity\InstantNotification;
use App\Entity\RegistryDataEntry;
use App\Repository\RegistryDataEntryRepository;
use Doctrine\ORM\EntityManagerInterface;

class IsInsuredProcessor implements RegistryDataProcessorInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var RegistryDataEntryRepository
     */
    private $registryDataEntryRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param RegistryDataEntryRepository $registryDataEntryRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RegistryDataEntryRepository $registryDataEntryRepository
    ) {
        $this->entityManager = $entityManager;
        $this->registryDataEntryRepository = $registryDataEntryRepository;
    }

    public function process(RegistryDataEntry $registryDataEntry)
    {
        $vehicle = $registryDataEntry->getVehicle();
        if (null === $vehicle) {
            return;
        }

        $previous = $this->registryDataEntryRepository->getPreviousRecord($vehicle);

        if (
            (null === $previous || true === $previous->getIsInsured())
            && false === $registryDataEntry->getIsInsured()
        ) {
            $this->addEventToVehicle($registryDataEntry);
            $this->addNotificationToUsers($registryDataEntry);
        }
    }

    /**
     * @param RegistryDataEntry $registryDataEntry
     */
    private function addEventToVehicle(RegistryDataEntry $registryDataEntry): void
    {
        $event = new Event();
        $event->setVehicle($registryDataEntry->getVehicle());
        $event->setCreatedAt($registryDataEntry->getEventTime());
        $event->setDescription('vehicle_has_no_insurance');

        $this->entityManager->persist($event);
        $this->entityManager->flush();
    }

    /**
     * @param RegistryDataEntry $registryDataEntry
     */
    private function addNotificationToUsers(RegistryDataEntry $registryDataEntry)
    {
        $vehicle = $registryDataEntry->getVehicle();
        if (is_null($vehicle)) {
            return;
        }

        $users = $vehicle->getUsers();
        if (0 === count($users)) {
            return;
        }

        foreach ($users as $user) {
            $instantNotification = new InstantNotification();
            $instantNotification->setUser($user);
            $instantNotification->setEventTime($registryDataEntry->getEventTime());
            $instantNotification->setIsSent(false);
            $instantNotification->setDescription('vehicle_has_no_insurance');

            $this->entityManager->persist($instantNotification);
            $this->entityManager->flush();
        }
    }
}
