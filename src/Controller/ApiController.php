<?php

namespace App\Controller;

use App\Entity\FakeVehicleDataEntry;
use App\Entity\FakeRegistryDataEntry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/demo/api/vehicle_data/{vin}", name="api_vehicle_data")
     * @param Request $request
     * @param string $vin
     * @return Response
     */
    public function vehicleData(Request $request, string $vin)
    {
        $data = $this->getDoctrine()
            ->getRepository(FakeVehicleDataEntry::class)
            ->findByVinTillThisMoment($vin);

        return $this->json($data);
    }

    /**
     * @Route("/demo/api/registry_data/{vin}", name="api_registry_data")
     * @param Request $request
     * @param string $vin
     * @return false|string
     */
    public function registryData(Request $request, string $vin)
    {
        $data = $this->getDoctrine()
            ->getRepository(FakeRegistryDataEntry::class)
            ->findByVinTillThisMoment($vin);

        return $this->json($data);
    }
}
