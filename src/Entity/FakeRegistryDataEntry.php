<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping\Table;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FakeRegistryDataEntryRepository")
 *
 * @Table(indexes={
 * @Index(name="idx_vin", columns={"vin"}),
 * @Index(name="idx_published_at", columns={"published_at"})
 * })
 */
class FakeRegistryDataEntry
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=17)
     *
     * @var string
     */
    private $vin;

    /**
     * @ORM\Column(
     *     type="string",
     *     columnDefinition="ENUM('registered', 'registered_but_suspended', 'unregistered')",
     *     nullable=false
     *     )
     *
     * @var string
     */
    private $status;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $isAllowedDriving;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $isInsured;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $isPoliceSearching;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface
     */
    private $technicalInspectionValidTill;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface
     */
    private $publishedAt;

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
     *
     * @return $this
     */
    public function setVin(string $vin): self
    {
        $this->vin = $vin;

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
     *
     * @return $this
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;

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
     *
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
     *
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
     *
     * @return $this
     */
    public function setIsPoliceSearching(bool $isPoliceSearching): self
    {
        $this->isPoliceSearching = $isPoliceSearching;

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
     *
     * @return $this
     */
    public function setTechnicalInspectionValidTill(DateTimeInterface $technicalInspectionValidTill): self
    {
        $this->technicalInspectionValidTill = $technicalInspectionValidTill;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getPublishedAt(): ?DateTimeInterface
    {
        return $this->publishedAt;
    }

    /**
     * @param DateTimeInterface $publishedAt
     *
     * @return $this
     */
    public function setPublishedAt(DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }
}
