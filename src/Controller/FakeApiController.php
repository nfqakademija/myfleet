<?php

namespace App\Controller;

use App\Repository\FakeRegistryDataEntryRepository;
use App\Repository\FakeVehicleDataEntryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class FakeApiController extends AbstractController
{
    /**
     * @Route("/fake/api/vehicle_data/{vin}", name="fake_api_vehicle_data")
     *
     * @param FakeVehicleDataEntryRepository $repository
     * @param string $vin
     *
     * @return Response
     */
    public function vehicleData(FakeVehicleDataEntryRepository $repository, string $vin)
    {
        $data = $repository->findByVinTillThisMoment($vin);

        return $this->json($data);
    }

    /**
     * @Route("/fake/api/registry_data/{vin}", name="fake_api_registry_data")
     *
     * @param FakeRegistryDataEntryRepository $repository
     * @param string $vin
     *
     * @return false|string
     */
    public function registryData(FakeRegistryDataEntryRepository $repository, string $vin)
    {
        $data = $repository->findByVinTillThisMoment($vin);

        return $this->json($data);
    }
}