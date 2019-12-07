<?php

namespace App\Service\RegistryDataProcessor;

use App\Entity\RegistryDataEntry;

interface RegistryDataProcessorInterface
{
    public function process(RegistryDataEntry $registryDataEntry);
}
