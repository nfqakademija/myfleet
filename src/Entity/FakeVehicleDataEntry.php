<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FakeVehicleDataEntryRepository")
 */
class FakeVehicleDataEntry
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=17)
     * @var string
     */
    private $vin;

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
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getVin(): ?string
    {
        return $this->vin;
    }

    /**
     * @param string $vin
     * @return $this
     */
    public function setVin(string $vin): self
    {
        $this->vin = $vin;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getLatitude(): ?float
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
     * @return float|null
     */
    public function getLongitude(): ?float
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
     * @return int|null
     */
    public function getMileage(): ?int
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
     * @return DateTimeInterface|null
     */
    public function getEventTime(): ?DateTimeInterface
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
