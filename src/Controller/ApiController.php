<?php

namespace App\Controller;

use App\Repository\InstantNotificationRepository;
use App\Service\Action\ApiGetInstantNotificationAction;
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
    public function getInstantNotification(ApiGetInstantNotificationAction $action)
    {
        return $action->execute();
    }
}
