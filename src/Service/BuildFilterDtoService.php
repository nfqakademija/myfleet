<?php

namespace App\Service;

use App\Dto\FiltersData;
use Symfony\Component\HttpFoundation\Request;

class BuildFilterDtoService
{
    /**
     * @param Request $request
     * @return FiltersData
     */
    public function execute(Request $request): FiltersData
    {
        $filterData = new FiltersData();
        $filterData->setVehicleType($request->get('type'));
        $filterData->setPlateNumberPart($request->get('plate_number'));
        $filterData->setPage($request->get('page', 1));

        return $filterData;
    }
}
