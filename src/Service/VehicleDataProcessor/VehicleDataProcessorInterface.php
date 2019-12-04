<?php

namespace App\Service\VehicleDataProcessor;

use App\Entity\VehicleDataEntry;
use App\Repository\VehicleDataEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

interface VehicleDataProcessorInterface
{
    public function __construct(
        Security $security,
        EntityManagerInterface $entityManager,
        VehicleDataEntryRepository $vehicleDataEntryRepository
    );

    public function process(VehicleDataEntry $vehicleDataEntry);
}
