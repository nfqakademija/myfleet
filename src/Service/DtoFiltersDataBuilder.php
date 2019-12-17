<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\FiltersData;
use Symfony\Component\HttpFoundation\Request;

class DtoFiltersDataBuilder
{
    /**
     * @param Request $request
     *
     * @return FiltersData
     */
    public function execute(Request $request): FiltersData
    {
        $filterData = new FiltersData();
        $filterData->setVehicleType(trim($request->get('type', '')));
        $filterData->setPlateNumberPart(trim($request->get('plate_number', '')));
        $filterData->setPage((int)$request->get('page', 1));

        return $filterData;
    }
}
