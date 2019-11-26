<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VehicleDataEntryRepository")
 */
class VehicleDataEntry
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vehicle", inversedBy="vehicleDataEntries")
     * @ORM\JoinColumn(nullable=true)
     * @var Vehicle|null
     */
    private $vehicle;

    /**
     * @ORM\Column(type="float")
     * @var float
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     * @var float
     */
    private $longitude;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $mileage;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTimeInterface
     */
    private $eventTime;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Vehicle|null
     */
    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }

    /**
     * @param Vehicle|null $vehicle
     * @return $this
     */
    public function setVehicle(?Vehicle $vehicle): self
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude(): float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return $this
     */
    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return float
     */
    public function getLongitude(): float
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     * @return $this
     */
    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return int
     */
    public function getMileage(): int
    {
        return $this->mileage;
    }

    /**
     * @param int $mileage
     * @return $this
     */
    public function setMileage(int $mileage): self
    {
        $this->mileage = $mileage;

        return $this;
    }

    /**
     * @return DateTimeInterface
     */
    public function getEventTime(): DateTimeInterface
    {
        return $this->eventTime;
    }

    /**
     * @param DateTimeInterface $eventTime
     * @return $this
     */
    public function setEventTime(DateTimeInterface $eventTime): self
    {
        $this->eventTime = $eventTime;

        return $this;
    }
}
