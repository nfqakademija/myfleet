<?php

namespace App\Service\VehicleDataProcessor;

use App\Entity\VehicleDataEntry;

interface VehicleDataProcessorInterface
{
    /**
     * @param VehicleDataEntry $vehicleDataEntry
     *
     * @return mixed
     */
    public function process(VehicleDataEntry $vehicleDataEntry);
}
