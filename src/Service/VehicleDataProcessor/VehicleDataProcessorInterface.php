<?php

namespace App\Service\VehicleDataProcessor;

use App\Entity\VehicleDataEntry;

interface VehicleDataProcessorInterface
{
    public function process(VehicleDataEntry $vehicleDataEntry);
}
