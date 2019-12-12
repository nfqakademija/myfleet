<?php

namespace App\Service\Action;

use App\Entity\Vehicle;
use App\Repository\RegistryDataEntryRepository;
use App\Repository\VehicleDataEntryRepository;
use App\Repository\VehicleRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

class ApiGetFreshVehicleDataAction
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
     * @var Security
     */
    private $security;

    /**
     * @param VehicleRepository $vehicleRepository
     * @param VehicleDataEntryRepository $vehicleDataEntryRepository
     * @param RegistryDataEntryRepository $registryDataEntryRepository
     * @param Security $security
     */
    public function __construct(
        VehicleRepository $vehicleRepository,
        VehicleDataEntryRepository $vehicleDataEntryRepository,
        RegistryDataEntryRepository $registryDataEntryRepository,
        Security $security
    ) {
        $this->vehicleRepository = $vehicleRepository;
        $this->vehicleDataEntryRepository = $vehicleDataEntryRepository;
        $this->registryDataRepository = $registryDataEntryRepository;
        $this->security = $security;
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
        if (null !== $vehicleDataEntry) {
            $data['latitude'] = $vehicleDataEntry->getLatitude();
            $data['longitude'] = $vehicleDataEntry->getLongitude();
            $data['mileage'] = $vehicleDataEntry->getMileage();
        }

        $registryDataEntry = $this->registryDataRepository->getLastEntry($vehicle);
        if (null !== $registryDataEntry) {
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
        if (null === $vin) {
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
