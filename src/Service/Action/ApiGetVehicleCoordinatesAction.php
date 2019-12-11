<?php

namespace App\Service\Action;

use App\Entity\Vehicle;
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

        return new JsonResponse($data);
    }

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
