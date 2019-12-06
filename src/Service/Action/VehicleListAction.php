<?php

namespace App\Service\Action;

use App\Repository\VehicleRepository;
use App\Service\DtoFiltersDataBuild;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class VehicleListAction
{
    /**
     * @var VehicleRepository
     */
    private $vehicleRepository;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @param VehicleRepository $vehicleRepository
     * @param Environment $twig
     */
    public function __construct(
        VehicleRepository $vehicleRepository,
        Environment $twig
    ) {
        $this->vehicleRepository = $vehicleRepository;
        $this->twig = $twig;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function execute(Request $request): Response
    {
        $dtoFiltersDataBuild = new DtoFiltersDataBuild();
        $filtersData = $dtoFiltersDataBuild->execute($request);

        $vehicles = $this->vehicleRepository->filterVehicles($filtersData);
        $totalVehicles = $this->vehicleRepository->countMatchingVehicles($filtersData);
        $pagesCount = ceil($totalVehicles / $filtersData->getPageSize());

        $content = $this->twig->render('vehicle/list.html.twig', [
            'vehicles' => $vehicles,
            'pagesCount' => $pagesCount,
            'page' => $request->get('page', 1),
        ]);

        $response = new Response();
        $response->setContent($content);

        return $response;
    }
}
