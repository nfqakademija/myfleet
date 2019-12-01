<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Form\Type\EventType;
use App\Form\Type\ExpenseEntryType;
use App\Form\Type\TaskType;
use App\Form\Type\VehicleType;
use App\Repository\RegistryDataEntryRepository;
use App\Repository\VehicleDataEntryRepository;
use App\Repository\VehicleRepository;
use App\Service\Action\VehicleCreateAction;
use App\Service\Action\VehicleListAction;
use App\Service\Action\VehicleUpdateAction;
use App\Service\Action\VehicleViewAction;
use App\Service\BuildFilterDtoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class VehicleController extends AbstractController
{
    /**
     * @Route("/vehicle/list", name="vehicle_list")
     *
     * @param Request $request
     * @param VehicleListAction $vehicleListAction
     *
     * @return Response
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
     * @return Response
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function view(
        Request $request,
        VehicleViewAction $vehicleViewAction
    ) {
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
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
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
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function update(Request $request, VehicleUpdateAction $vehicleUpdateAction)
    {
        return $vehicleUpdateAction->execute($request);
    }
}
