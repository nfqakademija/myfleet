<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VehicleRepository")
 * @UniqueEntity("vinCode")
 * @UniqueEntity("registrationPlateNumber")
 */
class Vehicle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="vehicle", orphanRemoval=true)
     * @var Collection|Event[]
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="vehicle")
     * @var Collection|Task[]
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExpenseEntry", mappedBy="vehicle")
     * @var Collection|ExpenseEntry[]
     */
    private $expenseEntries;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\VehicleDataEntry", mappedBy="vehicle", orphanRemoval=true)
     * @var Collection|VehicleDataEntry[]
     */
    private $vehicleDataEntries;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255, nullable=false)
     * @var string|null
     */
    private $make;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", length=255, nullable=false)
     * @var string|null
     */
    private $model;

    /**
     * @Assert\Date
     * @ORM\Column(type="date", nullable=false)
     * @var DateTimeInterface|null
     */
    private $firstRegistration;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string", unique=true, length=10, nullable=false)
     * @var string|null
     */
    private $registrationPlateNumber;

    /**
     * @Assert\Length(
     *     min = 17,
     *     max = 17
     * )
     * @ORM\Column(type="string", unique=true, length=17, nullable=false)
     * @var string|null
     */
    private $vinCode;

    /**
     * @Assert\Choice(
     *     choices={"car", "truck", "semitrailer", "van"},
     *     message="Pasirink tinkamą transporto priemonės tipą"
     * )
     * @ORM\Column(type="string", columnDefinition="ENUM('car', 'truck', 'semitrailer', 'van')", nullable=false)
     * @var string|null
     */
    private $type;

    /**
     * @Assert\NotNull
     * @ORM\Column(type="text", length=3000, nullable=false)
     * @var string|null
     */
    private $additionalInformation;

    /**
     * Vehicle constructor.
     */
    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->expenseEntries = new ArrayCollection();
        $this->vehicleDataEntries = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Collection|Event[]
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    /**
     * @param Event $event
     * @return $this
     */
    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events[] = $event;
            $event->setVehicle($this);
        }

        return $this;
    }

    /**
     * @param Event $event
     * @return $this
     */
    public function removeEvent(Event $event): self
    {
        if ($this->events->contains($event)) {
            $this->events->removeElement($event);
            // set the owning side to null (unless already changed)
            if ($event->getVehicle() === $this) {
                $event->setVehicle($this);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    /**
     * @param Task $task
     * @return $this
     */
    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setVehicle($this);
        }

        return $this;
    }

    /**
     * @param Task $task
     * @return $this
     */
    public function removeTask(Task $task): self
    {
        if ($this->tasks->contains($task)) {
            $this->tasks->removeElement($task);
            // set the owning side to null (unless already changed)
            if ($task->getVehicle() === $this) {
                $task->setVehicle($this);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ExpenseEntry[]
     */
    public function getExpenseEntries(): Collection
    {
        return $this->expenseEntries;
    }

    /**
     * @param ExpenseEntry $expenseEntry
     * @return $this
     */
    public function addExpenseEntry(ExpenseEntry $expenseEntry): self
    {
        if (!$this->expenseEntries->contains($expenseEntry)) {
            $this->expenseEntries[] = $expenseEntry;
            $expenseEntry->setVehicle($this);
        }

        return $this;
    }

    /**
     * @param ExpenseEntry $expenseEntry
     * @return $this
     */
    public function removeExpenseEntry(ExpenseEntry $expenseEntry): self
    {
        if ($this->expenseEntries->contains($expenseEntry)) {
            $this->expenseEntries->removeElement($expenseEntry);
            // set the owning side to null (unless already changed)
            if ($expenseEntry->getVehicle() === $this) {
                $expenseEntry->setVehicle($this);
            }
        }

        return $this;
    }

    /**
     * @return Collection|VehicleDataEntry[]
     */
    public function getVehicleDataEntries(): Collection
    {
        return $this->vehicleDataEntries;
    }

    /**
     * @param VehicleDataEntry $vehicleDataEntry
     * @return $this
     */
    public function addVehicleDataEntry(VehicleDataEntry $vehicleDataEntry): self
    {
        if (!$this->vehicleDataEntries->contains($vehicleDataEntry)) {
            $this->vehicleDataEntries[] = $vehicleDataEntry;
            $vehicleDataEntry->setVehicle($this);
        }

        return $this;
    }

    /**
     * @param VehicleDataEntry $vehicleDataEntry
     * @return $this
     */
    public function removeVehicleDataEntry(VehicleDataEntry $vehicleDataEntry): self
    {
        if ($this->vehicleDataEntries->contains($vehicleDataEntry)) {
            $this->vehicleDataEntries->removeElement($vehicleDataEntry);
            // set the owning side to null (unless already changed)
            if ($vehicleDataEntry->getVehicle() === $this) {
                $vehicleDataEntry->setVehicle(null);
            }
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMake(): ?string
    {
        return $this->make;
    }

    /**
     * @param string|null $make
     * @return $this
     */
    public function setMake(?string $make): self
    {
        $this->make = $make;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * @param string|null $model
     * @return $this
     */
    public function setModel(?string $model): self
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getFirstRegistration(): ?DateTimeInterface
    {
        return $this->firstRegistration;
    }

    /**
     * @param DateTimeInterface|null $firstRegistration
     * @return $this
     */
    public function setFirstRegistration(?DateTimeInterface $firstRegistration): self
    {
        $this->firstRegistration = $firstRegistration;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRegistrationPlateNumber(): ?string
    {
        return $this->registrationPlateNumber;
    }

    /**
     * @param string|null $registrationPlateNumber
     * @return $this
     */
    public function setRegistrationPlateNumber(?string $registrationPlateNumber): self
    {
        $this->registrationPlateNumber = $registrationPlateNumber;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getVinCode(): ?string
    {
        return $this->vinCode;
    }

    /**
     * @param string|null $vinCode
     * @return $this
     */
    public function setVinCode(?string $vinCode): self
    {
        $this->vinCode = $vinCode;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return $this
     */
    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAdditionalInformation(): ?string
    {
        return $this->additionalInformation;
    }

    /**
     * @param string|null $additionalInformation
     * @return $this
     */
    public function setAdditionalInformation(?string $additionalInformation): self
    {
        $this->additionalInformation = $additionalInformation;

        return $this;
    }
}
