<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Action\VehicleCreateAction;
use App\Service\Action\VehicleListAction;
use App\Service\Action\VehicleTaskCompleteAction;
use App\Service\Action\VehicleUpdateAction;
use App\Service\Action\VehicleViewAction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Class VehicleController
 *
 * @package App\Controller
 *
 * @IsGranted("ROLE_USER")
 */
class VehicleController extends AbstractController
{
    /**
     * @Route("/vehicle/list", name="vehicle_list")
     *
     * @param Request $request
     * @param VehicleListAction $vehicleListAction
     *
     * @return Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function list(Request $request, VehicleListAction $vehicleListAction): Response
    {
        return $vehicleListAction->execute($request);
    }

    /**
     * @Route("/vehicle/{id}", name="vehicle_view", requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param VehicleViewAction $vehicleViewAction
     *
     * @return Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function view(Request $request, VehicleViewAction $vehicleViewAction)
    {
        return $vehicleViewAction->execute($request);
    }

    /**
     * @Route("/vehicle/create", name="vehicle_create")
     *
     * @param Request $request
     * @param VehicleCreateAction $vehicleCreateAction
     *
     * @return Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function create(Request $request, VehicleCreateAction $vehicleCreateAction)
    {
        return $vehicleCreateAction->execute($request);
    }

    /**
     * @Route("/vehicle/{id}/update", name="vehicle_update", requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param VehicleUpdateAction $vehicleUpdateAction
     *
     * @return RedirectResponse|Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function update(Request $request, VehicleUpdateAction $vehicleUpdateAction)
    {
        return $vehicleUpdateAction->execute($request);
    }

    /**
     * @Route("/vehicle/task/{id}/complete", name="vehicle_task_complete", requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param VehicleTaskCompleteAction $vehicleTaskCompleteAction
     *
     * @return RedirectResponse
     */
    public function taskComplete(Request $request, VehicleTaskCompleteAction $vehicleTaskCompleteAction)
    {
        return $vehicleTaskCompleteAction->execute($request);
    }
}
