<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VehicleRepository")
 */
class Vehicle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int $id
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="vehicle", orphanRemoval=true)
     * @var Event $events
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="vehicle")
     * @var Task $tasks
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExpenseEntry", mappedBy="vehicle")
     * @var ExpenseEntry $expenseEntries
     */
    private $expenseEntries;

    /**
     * Vehicle constructor.
     */
    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->expenseEntries = new ArrayCollection();
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
                $event->setVehicle(null);
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
                $task->setVehicle(null);
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
                $expenseEntry->setVehicle(null);
            }
        }

        return $this;
    }
}
