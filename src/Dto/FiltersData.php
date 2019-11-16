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
    private $registrationPlateNumberPart;

    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $pageSize = 3;

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
    public function getRegistrationPlateNumberPart(): ?string
    {
        return $this->registrationPlateNumberPart;
    }

    /**
     * @param string|null $registrationPlateNumberPart
     */
    public function setRegistrationPlateNumberPart(?string $registrationPlateNumberPart): void
    {
        $this->registrationPlateNumberPart = $registrationPlateNumberPart;
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
