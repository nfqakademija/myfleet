<?php

namespace App\Service\Action;

use App\Repository\VehicleDataEntryRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\SerializerInterface;

class ApiGetVehicleCoordinatesAction
{
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

    public function __construct(
        VehicleDataEntryRepository $vehicleDataEntryRepository,
        Security $security,
        SerializerInterface $serializer
    ) {
        $this->vehicleDataEntryRepository = $vehicleDataEntryRepository;
        $this->security = $security;
        $this->serializer = $serializer;
    }

    public function execute(Request $request): Response
    {
        return new JsonResponse($this->serializeToJson([]));
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
