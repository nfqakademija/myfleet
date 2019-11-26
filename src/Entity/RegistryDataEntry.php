<?php

namespace App\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RegistryDataEntryRepository")
 */
class RegistryDataEntry
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Vehicle", inversedBy="registryDataEntries")
     * @ORM\JoinColumn(nullable=false)
     * @var Vehicle
     */
    private $vehicle;

    /**
     * @ORM\Column(type="string", columnDefinition="ENUM('registred', 'registred_but_suspended', 'unregistred')", nullable=false)
     * @var string
     */
    private $status;

    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    private $technicalInspectionValidTill;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $isAllowedDriving;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $isInsured;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $isPoliceSearching;

    /**
     * @return int|null
     */
    public function getId(): ?int
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
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return $this
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getTechnicalInspectionValidTill(): ?DateTimeInterface
    {
        return $this->technicalInspectionValidTill;
    }

    /**
     * @param DateTimeInterface $technicalInspectionValidTill
     * @return $this
     */
    public function setTechnicalInspectionValidTill(DateTimeInterface $technicalInspectionValidTill): self
    {
        $this->technicalInspectionValidTill = $technicalInspectionValidTill;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsAllowedDriving(): ?bool
    {
        return $this->isAllowedDriving;
    }

    /**
     * @param bool $isAllowedDriving
     * @return $this
     */
    public function setIsAllowedDriving(bool $isAllowedDriving): self
    {
        $this->isAllowedDriving = $isAllowedDriving;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsInsured(): ?bool
    {
        return $this->isInsured;
    }

    /**
     * @param bool $isInsured
     * @return $this
     */
    public function setIsInsured(bool $isInsured): self
    {
        $this->isInsured = $isInsured;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsPoliceSearching(): ?bool
    {
        return $this->isPoliceSearching;
    }

    /**
     * @param bool $isPoliceSearching
     * @return $this
     */
    public function setIsPoliceSearching(bool $isPoliceSearching): self
    {
        $this->isPoliceSearching = $isPoliceSearching;

        return $this;
    }
}
