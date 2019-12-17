<?php

declare(strict_types=1);

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InstantNotificationRepository")
 */
class InstantNotification
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
     * @ORM\Column(type="string", length=250)
     *
     * @var string
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var DateTimeInterface
     */
    private $eventTime;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $isSent;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="instantNotifications")
     *
     * @var User|null
     */
    private $user;

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
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

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
     *
     * @return $this
     */
    public function setEventTime(DateTimeInterface $eventTime): self
    {
        $this->eventTime = $eventTime;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsSent(): ?bool
    {
        return $this->isSent;
    }

    /**
     * @param bool $isSent
     *
     * @return $this
     */
    public function setIsSent(bool $isSent): self
    {
        $this->isSent = $isSent;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     *
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
