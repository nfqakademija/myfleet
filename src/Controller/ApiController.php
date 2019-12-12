<?php

namespace App\Controller;

use App\Service\Action\ApiGetFreshVehicleDataAction;
use App\Service\Action\ApiGetInstantNotificationAction;
use App\Service\Action\ApiGetVehicleDataAction;
use App\Service\Action\ApiPostVehicleEmergencyCallAction;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/instant_notification", name="api_instant_notificaiton")
     *
     * @IsGranted("ROLE_USER")
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
     * @Route("/api/fresh_vehicle_data/{vin?}", name="api_fresh_vehicle_data")
     *
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param ApiGetFreshVehicleDataAction $action
     *
     * @return Response
     */
    public function getFreshVehicleData(Request $request, ApiGetFreshVehicleDataAction $action): Response
    {
        return $action->execute($request);
    }

    /**
     * @Route("/api/vehicle_data/{vin}", name="api_vehicle_data")
     *
     * @IsGranted("ROLE_USER")
     *
     * @param Request $request
     * @param ApiGetVehicleDataAction $action
     *
     * @return mixed
     */
    public function getVehicleData(Request $request, ApiGetVehicleDataAction $action)
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
     *
     * @throws Exception
     */
    public function postVehicleEmergencyCall(Request $request, ApiPostVehicleEmergencyCallAction $action)
    {
        return $action->execute($request);
    }
}
