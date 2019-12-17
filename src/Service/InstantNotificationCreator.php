<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\InstantNotification;
use App\Entity\User;
use App\Entity\Vehicle;
use App\Repository\UserRepository;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\RouterInterface;

class InstantNotificationCreator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

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
     * @param UserRepository $userRepository
     * @param RouterInterface $router
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        RouterInterface $router
    ) {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->router = $router;
    }

    /**
     * @param Vehicle $vehicle
     * @param DateTimeInterface $eventTime
     * @param string $message
     */
    public function execute(Vehicle $vehicle, DateTimeInterface $eventTime, string $message): void
    {
        $users = $this->getUsers($vehicle);
        $link = $this->getLinkToVehiclePage($vehicle);

        $description = $message . '<a href="' . $link . '">' . $vehicle->getPlateNumber() . '</a>';

        foreach ($users as $user) {
            $instantNotification = new InstantNotification();
            $instantNotification->setUser($user);
            $instantNotification->setEventTime($eventTime);
            $instantNotification->setIsSent(false);
            $instantNotification->setDescription($description);

            $this->entityManager->persist($instantNotification);
        }
        $this->entityManager->flush();
    }

    /**
     * @param Vehicle $vehicle
     *
     * @return array
     */
    private function getUsers(Vehicle $vehicle): array
    {
        return array_merge(
            $this->userRepository->findByRole(User::ROLE_ADMIN),
            $vehicle->getUsers()->toArray()
        );
    }

    /**
     * @param Vehicle $vehicle
     *
     * @return string
     */
    private function getLinkToVehiclePage(Vehicle $vehicle): string
    {
        return $this->router->generate('vehicle_view', [
            'id' => $vehicle->getId(),
        ]);
    }
}
