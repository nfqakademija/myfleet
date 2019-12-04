<?php

namespace App\Dto;

class FiltersData
{
    /**
     * @var string|null
     */
    private $vehicleType;

    /**
     * @var string|null
     */
    private $plateNumberPart;

    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $pageSize = 10;

    /**
     * @return string|null
     */
    public function getVehicleType(): ?string
    {
        return $this->vehicleType;
    }

    /**
     * @param string|null $vehicleType
     */
    public function setVehicleType(?string $vehicleType): void
    {
        $this->vehicleType = $vehicleType;
    }

    /**
     * @return string|null
     */
    public function getPlateNumberPart(): ?string
    {
        return $this->plateNumberPart;
    }

    /**
     * @param string|null $plateNumberPart
     */
    public function setPlateNumberPart(?string $plateNumberPart): void
    {
        $this->plateNumberPart = $plateNumberPart;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @return int
     */
    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    /**
     * @param int $pageSize
     */
    public function setPageSize(int $pageSize): void
    {
        $this->pageSize = $pageSize;
    }
}
