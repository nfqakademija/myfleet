<?php

namespace App\Service\RegistryDataProcessor;

use App\Entity\Event;
use App\Entity\RegistryDataEntry;
use App\Repository\RegistryDataEntryRepository;
use App\Service\InstantNotificationCreator;
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
     * @var InstantNotificationCreator
     */
    private $instantNotificationCreator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param RegistryDataEntryRepository $registryDataEntryRepository
     * @param InstantNotificationCreator $instantNotificationCreator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RegistryDataEntryRepository $registryDataEntryRepository,
        InstantNotificationCreator $instantNotificationCreator
    ) {
        $this->entityManager = $entityManager;
        $this->registryDataEntryRepository = $registryDataEntryRepository;
        $this->instantNotificationCreator = $instantNotificationCreator;
    }

    /**
     * @param RegistryDataEntry $registryDataEntry
     */
    public function process(RegistryDataEntry $registryDataEntry): void
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
            $this->instantNotificationCreator->execute(
                $vehicle,
                $registryDataEntry->getEventTime(),
                'Transporto priemonė neturi galiojančio draudimo: '
            );
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
        $event->setDescription('Transporto priemonė neturi galiojančio draudimo');

        $this->entityManager->persist($event);
        $this->entityManager->flush();
    }
}
