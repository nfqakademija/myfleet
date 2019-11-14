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
    public function getVehicleType(): ?string
    {
        return $this->vehicleType;
    }

    /**
     * @param mixed $vehicleType
     */
    public function setVehicleType($vehicleType): void
    {
        $this->vehicleType = $vehicleType;
    }

    /**
     * @return string
     */
    public function getRegistrationPlateNumberPart(): ?string
    {
        return $this->registrationPlateNumberPart;
    }

    /**
     * @param mixed $registrationPlateNumberPart
     */
    public function setRegistrationPlateNumberPart($registrationPlateNumberPart): void
    {
        $this->registrationPlateNumberPart = $registrationPlateNumberPart;
    }
}
