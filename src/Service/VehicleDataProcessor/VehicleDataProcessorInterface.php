<?php

declare(strict_types=1);

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
