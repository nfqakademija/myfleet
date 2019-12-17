<?php

declare(strict_types=1);

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
