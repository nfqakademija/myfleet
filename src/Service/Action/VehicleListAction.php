<?php

namespace App\Service\Action;

use App\Repository\VehicleRepository;
use App\Service\BuildFilterDtoService;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VehicleListAction
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var VehicleRepository
     */
    private $vehicleRepository;


    public function __construct(
        ContainerInterface $container,
        VehicleRepository $vehicleRepository
    ) {
        $this->container = $container;
        $this->vehicleRepository = $vehicleRepository;
    }

    public function execute(Request $request): Response
    {
        $buildFilterDtoService = new BuildFilterDtoService();
        $filtersData = $buildFilterDtoService->execute($request);

        $vehicles = $this->vehicleRepository->filterVehicles($filtersData);
        $totalVehicles = $this->vehicleRepository->countMatchingVehicles($filtersData);
        $pagesCount = ceil($totalVehicles / $filtersData->getPageSize());

        $content = $this->container->get('twig')->render('vehicle/list.html.twig', [
            'vehicles' => $vehicles,
            'pagesCount' => $pagesCount,
            'page' => $request->get('page', 1),
        ]);

        $response = new Response();
        $response->setContent($content);

        return $response;
    }
}
