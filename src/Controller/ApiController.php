<?php

namespace App\Controller;

use App\Entity\FakeRegistryDataEntry;
use App\Repository\FakeRegistryDataEntryRepository;
use App\Repository\FakeVehicleDataEntryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/demo/api/vehicle_data/{vin}", name="api_vehicle_data")
     * @param Request $request
     * @param FakeVehicleDataEntryRepository $repository
     * @param string $vin
     * @return Response
     */
    public function vehicleData(
        Request $request,
        FakeVehicleDataEntryRepository $repository,
        string $vin
    ) {
        $data = $repository->findByVinTillThisMoment($vin);
        return $this->json($data);
    }

    /**
     * @Route("/demo/api/registry_data/{vin}", name="api_registry_data")
     * @param Request $request
     * @param FakeRegistryDataEntryRepository $repository
     * @param string $vin
     * @return false|string
     */
    public function registryData(
        Request $request,
        FakeRegistryDataEntryRepository $repository,
        string $vin
    ) {
        $data = $repository->findByVinTillThisMoment($vin);

        return $this->json($data);
    }
}
