<?php

namespace App\Service\RegistryDataProcessor;

use App\Entity\Event;
use App\Entity\InstantNotification;
use App\Entity\RegistryDataEntry;
use App\Repository\RegistryDataEntryRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

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
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param EntityManagerInterface $entityManager
     * @param RegistryDataEntryRepository $registryDataEntryRepository
     * @param UserRepository $userRepository
     * @param RouterInterface $router
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        RegistryDataEntryRepository $registryDataEntryRepository,
        UserRepository $userRepository,
        RouterInterface $router
    ) {
        $this->entityManager = $entityManager;
        $this->registryDataEntryRepository = $registryDataEntryRepository;
        $this->userRepository = $userRepository;
        $this->router = $router;
    }

    /**
     * @param RegistryDataEntry $registryDataEntry
     */
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
        $event->setDescription('Transporto priemonė neturi galiojančio draudimo');

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

        $users = array_merge(
            $this->userRepository->findByRole('ADMIN'),
            $vehicle->getUsers()
        );

        $linkToVehicle = $this->router->generate('vehicle_view', [
            'id' => $vehicle->getId(),
        ]);

        $description = 'Transporto priemonė neturi galiojančio draudimo: '
            . '<a href="' . $linkToVehicle . '">' . $vehicle->getPlateNumber() . '</a>';

        foreach ($users as $user) {
            $instantNotification = new InstantNotification();
            $instantNotification->setUser($user);
            $instantNotification->setEventTime($registryDataEntry->getEventTime());
            $instantNotification->setIsSent(false);
            $instantNotification->setDescription($description);

            $this->entityManager->persist($instantNotification);
            $this->entityManager->flush();
        }
    }
}
