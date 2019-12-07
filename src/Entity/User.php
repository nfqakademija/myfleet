<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @var array
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @var string
     */
    private $password;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Vehicle", mappedBy="users")
     * @var Collection|Vehicle[]
     */
    private $vehicles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Task", mappedBy="user")
     * @var Collection|Task[]
     */
    private $tasks;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Event", mappedBy="user")
     * @var Collection|Event[]
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExpenseEntry", mappedBy="user")
     * @var Collection|ExpenseEntry[]
     */
    private $expenseEntries;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InstantNotification", mappedBy="user")
     * @var Collection|InstantNotification[]
     */
    private $instantNotifications;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->vehicles = new ArrayCollection();
        $this->tasks = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->expenseEntries = new ArrayCollection();
        $this->instantNotifications = new ArrayCollection();
    }

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
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection|Vehicle[]
     */
    public function getVehicles(): Collection
    {
        return $this->vehicles;
    }

    /**
     * @param Vehicle $vehicle
     * @return $this
     */
    public function addVehicle(Vehicle $vehicle): self
    {
        if (!$this->vehicles->contains($vehicle)) {
            $this->vehicles[] = $vehicle;
            $vehicle->addUser($this);
        }

        return $this;
    }

    /**
     * @param Vehicle $vehicle
     * @return $this
     */
    public function removeVehicle(Vehicle $vehicle): self
    {
        if ($this->vehicles->contains($vehicle)) {
            $this->vehicles->removeElement($vehicle);
            $vehicle->removeUser($this);
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
            $task->setUser($this);
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
            if ($task->getUser() === $this) {
                $task->setUser(null);
            }
        }

        return $this;
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
            $event->setUser($this);
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
            if ($event->getUser() === $this) {
                $event->setUser(null);
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
            $expenseEntry->setUser($this);
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
            if ($expenseEntry->getUser() === $this) {
                $expenseEntry->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|InstantNotification[]
     */
    public function getInstantNotifications(): Collection
    {
        return $this->instantNotifications;
    }

    /**
     * @param InstantNotification $instantNotification
     * @return $this
     */
    public function addInstantNotification(InstantNotification $instantNotification): self
    {
        if (!$this->instantNotifications->contains($instantNotification)) {
            $this->instantNotifications[] = $instantNotification;
            $instantNotification->setUser($this);
        }

        return $this;
    }

    /**
     * @param InstantNotification $instantNotification
     * @return $this
     */
    public function removeInstantNotification(InstantNotification $instantNotification): self
    {
        if ($this->instantNotifications->contains($instantNotification)) {
            $this->instantNotifications->removeElement($instantNotification);
            // set the owning side to null (unless already changed)
            if ($instantNotification->getUser() === $this) {
                $instantNotification->setUser(null);
            }
        }

        return $this;
    }
}
