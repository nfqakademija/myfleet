<?php

declare(strict_types=1);

namespace App\Service\Action;

use App\Entity\Vehicle;
use App\Repository\VehicleDataEntryRepository;
use App\Repository\VehicleRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiGetVehicleDataAction
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
     * @param VehicleRepository $vehicleRepository
     * @param VehicleDataEntryRepository $vehicleDataEntryRepository
     */
    public function __construct(
        VehicleRepository $vehicleRepository,
        VehicleDataEntryRepository $vehicleDataEntryRepository
    ) {
        $this->vehicleRepository = $vehicleRepository;
        $this->vehicleDataEntryRepository = $vehicleDataEntryRepository;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function execute(Request $request): Response
    {
        $entries = $this->getEntries($request);

        if ($entries === null) {
            return new JsonResponse([]);
        }

        $data = $this->transformData($entries);

        return new JsonResponse($data);
    }

    /**
     * @param Request $request
     *
     * @return mixed|null
     */
    private function getEntries(Request $request)
    {
        $vin = $request->attributes->get('vin');
        $vehicle = $this->vehicleRepository->findOneBy(['vin' => $vin]);

        if (!($vehicle instanceof Vehicle)) {
            return null;
        }

        return $this->vehicleDataEntryRepository->findByVehicleTillThisMoment(
            $vehicle->getId(),
            (int)$request->get('timestamp', 0)
        );
    }

    /**
     * @param mixed $vehicleDataEntries
     *
     * @return array
     */
    private function transformData($vehicleDataEntries): array
    {
        $out = [];
        foreach ($vehicleDataEntries as $entry) {
            $out['coordinates'][] = [$entry->getLatitude(), $entry->getLongitude()];
            if (!isset($out['timestamp'])) {
                $out['timestamp'] = $entry->getEventTime()->getTimestamp();
            }
        }

        if (isset($out['coordinates'])) {
            $out['coordinates'] = array_reverse($out['coordinates']);
        }

        return $out;
    }
}
