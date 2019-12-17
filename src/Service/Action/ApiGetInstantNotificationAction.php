<?php

declare(strict_types=1);

namespace App\Service\Action;

use App\Entity\InstantNotification;
use App\Repository\InstantNotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ApiGetInstantNotificationAction
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
     * @var InstantNotificationRepository
     */
    private $instantNotificationRepository;

    /**
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     * @param InstantNotificationRepository $instantNotificationRepository
     */
    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager,
        InstantNotificationRepository $instantNotificationRepository
    ) {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->instantNotificationRepository = $instantNotificationRepository;
    }

    /**
     * @return Response
     *
     * @throws Exception
     */
    public function execute(): Response
    {
        $currentUser = $this->getCurrentUser();

        if ($currentUser === null) {
            return new JsonResponse([]);
        }

        for ($i = 1; $i <= 20; $i++) {
            $lastNotification = $this->getUnsentUserNotification($currentUser);

            if ($lastNotification !== null && $lastNotification->getEventTime() !== null) {
                $this->setSentUserNotification($lastNotification);

                return new JsonResponse(['description' => $lastNotification->getDescription()]);
            } else {
                sleep(1);
                continue;
            }
        }

        return new JsonResponse([]);
    }

    /**
     * @return UserInterface|null
     */
    private function getCurrentUser()
    {
        return $this->security->getUser();
    }

    /**
     * @param UserInterface $user
     *
     * @return InstantNotification|null
     *
     * @throws Exception
     */
    private function getUnsentUserNotification(UserInterface $user)
    {
        return $this->instantNotificationRepository->findLastUnsentUserNotification($user);
    }

    /**
     * @param InstantNotification $instantNotification
     */
    private function setSentUserNotification(InstantNotification $instantNotification): void
    {
        $instantNotification->setIsSent(true);

        $this->entityManager->persist($instantNotification);
        $this->entityManager->flush();
    }
}
