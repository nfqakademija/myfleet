<?php

namespace App\Service;

use App\Dto\FiltersData;
use Symfony\Component\HttpFoundation\Request;

class VehicleService
{
    /**
     * @var FiltersData
     */
    private $filtersData;

    /**
     * VehicleService constructor.
     * @param FiltersData $filtersData
     */
    public function __construct(FiltersData $filtersData)
    {
        $this->filtersData = $filtersData;
    }

    /**
     * @param Request $request
     * @return FiltersData
     */
    public function buildFilterDto(Request $request): FiltersData
    {
        $this->filtersData->setVehicleType($request->get('type'));
        $this->filtersData->setPlateNumberPart($request->get('plate_number'));
        $this->filtersData->setPage($request->get('page', 1));

        return $this->filtersData;
    }
}
