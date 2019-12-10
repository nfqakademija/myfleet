<?php

namespace App\Service\Action;

use App\Entity\InstantNotification;
use App\Repository\InstantNotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

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
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ApiGetInstantNotificationAction constructor.
     * @param Security $security
     * @param EntityManagerInterface $entityManager
     * @param InstantNotificationRepository $instantNotificationRepository
     * @param SerializerInterface $serializer
     */
    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager,
        InstantNotificationRepository $instantNotificationRepository,
        SerializerInterface $serializer
    ) {
        $this->security = $security;
        $this->entityManager = $entityManager;
        $this->instantNotificationRepository = $instantNotificationRepository;
        $this->serializer = $serializer;
    }

    /**
     * @return JsonResponse
     */
    public function execute()
    {
        $currentUser = $this->getCurrentUser();

        if (is_null($currentUser)) {
            return new JsonResponse($this->serializeToJson([]));
        }

        for ($i = 1; $i <= 20; $i++) {
            $lastNotification = $this->getUnsentUserNotification($currentUser);

            if (null !== $lastNotification && !is_null($lastNotification->getEventTime())) {
                $this->setSentUserNotification($lastNotification);

                return new JsonResponse($this->serializeToJson($lastNotification));
            } else {
                sleep(1);
                continue;
            }
        }
        return new JsonResponse($this->serializeToJson([]));
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
     * @return InstantNotification|null
     */
    private function getUnsentUserNotification(UserInterface $user)
    {
        return $this->instantNotificationRepository->findUnsentUserNotification($user);
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

    /**
     * @param mixed $data
     * @return string
     */
    private function serializeToJson($data): string
    {
        return $this->serializer->serialize(
            $data,
            'json',
            array_merge([
                'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS
            ])
        );
    }
}
