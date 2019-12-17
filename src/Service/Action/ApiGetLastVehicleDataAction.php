<?php

declare(strict_types=1);

namespace App\Service\Action;

use App\Entity\Vehicle;
use App\Repository\RegistryDataEntryRepository;
use App\Repository\VehicleDataEntryRepository;
use App\Repository\VehicleRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiGetLastVehicleDataAction
{
    /**
     * @var VehicleRepository
     */
    private $vehicleRepository;

    /**
     * @var VehicleDataEntryRepository
     */
    private $vehicleDataEntryRepository;

    /**
     * @var RegistryDataEntryRepository
     */
    private $registryDataRepository;

    /**
     * @param VehicleRepository $vehicleRepository
     * @param VehicleDataEntryRepository $vehicleDataEntryRepository
     * @param RegistryDataEntryRepository $registryDataEntryRepository
     */
    public function __construct(
        VehicleRepository $vehicleRepository,
        VehicleDataEntryRepository $vehicleDataEntryRepository,
        RegistryDataEntryRepository $registryDataEntryRepository
    ) {
        $this->vehicleRepository = $vehicleRepository;
        $this->vehicleDataEntryRepository = $vehicleDataEntryRepository;
        $this->registryDataRepository = $registryDataEntryRepository;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function execute(Request $request): Response
    {
        $data = $this->getData($request);

        return new JsonResponse($data);
    }

    private function getVehicleData(Vehicle $vehicle): array
    {
        $data = [
            'id' => $vehicle->getId(),
            'vin' => $vehicle->getVin(),
            'plateNumber' => $vehicle->getPlateNumber(),
            'type' => $vehicle->getType(),
            'make' => $vehicle->getMake(),
            'model' => $vehicle->getModel(),
            'firstRegistration' => $vehicle->getFirstRegistration(),
            'additionalInformation' => $vehicle->getAdditionalInformation(),
        ];

        $vehicleDataEntry = $this->vehicleDataEntryRepository->getLastEntry($vehicle);
        if ($vehicleDataEntry !== null) {
            $data['latitude'] = $vehicleDataEntry->getLatitude();
            $data['longitude'] = $vehicleDataEntry->getLongitude();
            $data['mileage'] = $vehicleDataEntry->getMileage();
        }

        $registryDataEntry = $this->registryDataRepository->getLastEntry($vehicle);
        if ($registryDataEntry !== null) {
            $data['status'] = $registryDataEntry->getStatus();
            $data['technicalInspectionValidTill'] = $registryDataEntry->getTechnicalInspectionValidTill();
            $data['isInsured'] = $registryDataEntry->getIsInsured();
            $data['isPoliceSearching'] = $registryDataEntry->getIsPoliceSearching();
            $data['isAllowedDriving'] = $registryDataEntry->getIsAllowedDriving();
        }

        return $data;
    }

    /**
     * @param Request $request
     *
     * @return mixed|null
     */
    private function getData(Request $request)
    {
        $data = [];

        $vin = $request->attributes->get('vin');
        if ($vin === null) {
            $vehicles = $this->vehicleRepository->findAll();

            foreach ($vehicles as $vehicle) {
                $data[$vehicle->getVin()] = $this->getVehicleData($vehicle);
            }
        } else {
            $vehicle = $this->vehicleRepository->findOneBy(['vin' => $vin]);
            if (is_null($vehicle)) {
                return $data;
            }

            $data[$vehicle->getVin()] = $this->getVehicleData($vehicle);
        }

        return $data;
    }
}
