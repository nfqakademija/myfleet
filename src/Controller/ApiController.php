<?php

namespace App\Controller;

use App\Repository\InstantNotificationRepository;
use App\Service\Action\ApiGetInstantNotificationAction;
use App\Service\Action\ApiGetVehicleCoordinatesAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/instant_notification", name="api_instant_notificaiton")
     *
     * @param ApiGetInstantNotificationAction $action
     * @return Response
     */
    public function getInstantNotification(ApiGetInstantNotificationAction $action)
    {
        return $action->execute();
    }

    /**
     * @Route("/api/vehicle_data/{vin}", name="api_vehicle_data")
     *
     * @param Request $request
     * @param ApiGetVehicleCoordinatesAction $action
     *
     * @return mixed
     */
    public function getVehicleCoordinates(Request $request, ApiGetVehicleCoordinatesAction $action)
    {
        return $action->execute($request);
    }
}
