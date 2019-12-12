<?php

namespace App\Service\RegistryDataProcessor;

use App\Entity\RegistryDataEntry;

interface RegistryDataProcessorInterface
{
    /**
     * @param RegistryDataEntry $registryDataEntry
     *
     * @return mixed
     */
    public function process(RegistryDataEntry $registryDataEntry);
}
