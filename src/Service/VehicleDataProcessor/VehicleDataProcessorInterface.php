<?php

namespace App\Service\VehicleDataProcessor;

use App\Entity\VehicleDataEntry;
use App\Repository\VehicleDataEntryRepository;
use Doctrine\ORM\EntityManagerInterface;

interface VehicleDataProcessorInterface
{
    public function __construct(
        EntityManagerInterface $entityManager,
        VehicleDataEntryRepository $vehicleDataEntryRepository
    );

    public function process(VehicleDataEntry $vehicleDataEntry);
}
