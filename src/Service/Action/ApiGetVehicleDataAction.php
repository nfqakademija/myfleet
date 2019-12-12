<?php

namespace App\Service\Action;

use App\Entity\Vehicle;
use App\Repository\VehicleDataEntryRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;

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
     * @var Security
     */
    private $security;

    /**
     * @param VehicleRepository $vehicleRepository
     * @param VehicleDataEntryRepository $vehicleDataEntryRepository
     * @param Security $security
     */
    public function __construct(
        VehicleRepository $vehicleRepository,
        VehicleDataEntryRepository $vehicleDataEntryRepository,
        Security $security
    ) {
        $this->vehicleRepository = $vehicleRepository;
        $this->vehicleDataEntryRepository = $vehicleDataEntryRepository;
        $this->security = $security;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function execute(Request $request): Response
    {
        $entries = $this->getEntries($request);

        if (null === $entries) {
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
            $request->get('start_id', 0)
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
            if (!isset($out['startId'])) {
                $out['startId'] = $entry->getId();
            }
        }

        return $out;
    }
}
