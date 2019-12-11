<?php

namespace App\Service\Action;

use App\Entity\Vehicle;
use App\Entity\VehicleDataEntry;
use App\Repository\VehicleDataEntryRepository;
use App\Repository\VehicleRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

class ApiGetVehicleCoordinatesAction
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
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param VehicleRepository $vehicleRepository
     * @param VehicleDataEntryRepository $vehicleDataEntryRepository
     * @param Security $security
     * @param SerializerInterface $serializer
     */
    public function __construct(
        VehicleRepository $vehicleRepository,
        VehicleDataEntryRepository $vehicleDataEntryRepository,
        Security $security,
        SerializerInterface $serializer
    ) {
        $this->vehicleRepository = $vehicleRepository;
        $this->vehicleDataEntryRepository = $vehicleDataEntryRepository;
        $this->security = $security;
        $this->serializer = $serializer;
    }

    public function execute(Request $request): Response
    {
        $entries = $this->getEntries($request);
        if (null === $entries) {
            return new JsonResponse($this->serializeToJson([]));
        }

        $data = $this->transformData($entries);

        return new JsonResponse($this->serializeToJson($data));
    }

    private function getEntries(Request $request)
    {
        $vehicleId = $request->attributes->get('id');
        $vehicle = $this->vehicleRepository->find($vehicleId);

        if (!$vehicle instanceof Vehicle) {
            return null;
        }

        return $this->vehicleDataEntryRepository->findByVehicleTillThisMoment(
            $vehicle,
            $request->get('start_id', 0)
        );
    }

    private function transformData($vehicleDataEntries): array
    {
        $out = ['coordinates' => [], 'start_id' => 0];
        foreach ($vehicleDataEntries as $entry) {
            $out['coordinates'][] = [$entry->getLatitude(), $entry->getLongitude()];
        }
        $lastEntry = end($vehicleDataEntries);
        $out['start_id'] = $lastEntry->getId();

        return $out;
    }

    /**
     * @param mixed $data
     * @return string
     */
    private function serializeToJson($data): string
    {
        return $this->serializer->serialize(
            $data,
            'json',
            array_merge([
                'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS
            ])
        );
    }
}
