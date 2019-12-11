<?php

namespace App\Controller;

use App\Service\Action\ApiGetInstantNotificationAction;
use App\Service\Action\ApiGetVehicleCoordinatesAction;
use App\Service\Action\ApiPostVehicleEmergencyCallAction;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/instant_notification", name="api_instant_notificaiton")
     *
     * @param ApiGetInstantNotificationAction $action
     *
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

    /**
     * @Route("/api/emergency_call/{vin}", methods={"POST"}, name="api_vehicle_emergency_call")
     *
     * @param Request $request
     * @param ApiPostVehicleEmergencyCallAction $action
     *
     * @return mixed
     */
    public function postVehicleEmergencyCall(Request $request, ApiPostVehicleEmergencyCallAction $action)
    {
        return $action->execute($request);
    }
}
