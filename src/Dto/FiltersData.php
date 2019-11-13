<?php


namespace App\Dto;


class FiltersData
{
    /**
     * @var string
     */
    private $vehicleType;

    /**
     * @var string
     */
    private $registrationPlateNumberPart;

    /**
     * @return string
     */
    public function getVehicleType(): string
    {
        return $this->vehicleType;
    }

    /**
     * @param string $vehicleType
     */
    public function setVehicleType(string $vehicleType): void
    {
        $this->vehicleType = $vehicleType;
    }

    /**
     * @return string
     */
    public function getRegistrationPlateNumberPart(): string
    {
        return $this->registrationPlateNumberPart;
    }

    /**
     * @param string $registrationPlateNumberPart
     */
    public function setRegistrationPlateNumberPart(string $registrationPlateNumberPart): void
    {
        $this->registrationPlateNumberPart = $registrationPlateNumberPart;
    }
}