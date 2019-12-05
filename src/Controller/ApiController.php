<?php

namespace App\Controller;

use App\Repository\InstantNotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/instant_notification", name="api_instant_notificaiton")
     *
     * @param InstantNotificationRepository $instantNotificationRepository
     * @param Security $security
     *
     * @return Response
     */
    public function getInstantNotification(
        InstantNotificationRepository $instantNotificationRepository,
        Security $security
    ) {
        $entityManager = $this->getDoctrine()->getManager();
        $currentUser = $security->getUser();

        if (is_null($currentUser)) {
            return new JsonResponse();
        }

        for ($i = 1; $i <= 20; $i++) {
            $lastNotification = $instantNotificationRepository->findUnsentUserNotification($currentUser);

            if (null !== $lastNotification && !is_null($lastNotification->getEventTime())) {
                $lastNotification->setIsSent(true);

                $entityManager->persist($lastNotification);
                $entityManager->flush();

                return new JsonResponse($lastNotification);
            } else {
                sleep(1);
                continue;
            }
        }
        return new JsonResponse();
    }
}
