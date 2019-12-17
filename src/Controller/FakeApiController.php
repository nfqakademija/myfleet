<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\FakeRegistryDataEntryRepository;
use App\Repository\FakeVehicleDataEntryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    public function vehicleData(FakeVehicleDataEntryRepository $repository, string $vin): Response
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
     * @return Response
     */
    public function registryData(FakeRegistryDataEntryRepository $repository, string $vin): Response
    {
        $data = $repository->findByVinTillThisMoment($vin);

        return $this->json($data);
    }
}
