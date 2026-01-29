<?php

namespace App\Entity;

use App\Repository\WeeklyDecisionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeeklyDecisionRepository::class)]
class WeeklyDecision
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?bool $monday = null;

    #[ORM\Column(nullable: true)]
    private ?bool $tuesday = null;

    #[ORM\Column(nullable: true)]
    private ?bool $wensday = null;

    #[ORM\Column(nullable: true)]
    private ?bool $thursday = null;

    #[ORM\Column(nullable: true)]
    private ?bool $friday = null;

    #[ORM\Column(nullable: true)]
    private ?bool $saturday = null;

    #[ORM\Column(nullable: true)]
    private ?bool $sunday = null;

    #[ORM\Column(type: Types::TIME_IMMUTABLE, nullable: true)]
    private ?\DateTimeImmutable $startingTime = null;

    #[ORM\Column(nullable: true)]
    private ?int $sprayDuration = null;

    #[ORM\OneToOne(inversedBy: 'weeklyDecision', cascade: ['persist', 'remove'])]
    private ?Zone $zone = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isMonday(): ?bool
    {
        return $this->monday;
    }

    public function setMonday(?bool $monday): static
    {
        $this->monday = $monday;

        return $this;
    }

    public function isTuesday(): ?bool
    {
        return $this->tuesday;
    }

    public function setTuesday(?bool $tuesday): static
    {
        $this->tuesday = $tuesday;

        return $this;
    }

    public function isWensday(): ?bool
    {
        return $this->wensday;
    }

    public function setWensday(?bool $wensday): static
    {
        $this->wensday = $wensday;

        return $this;
    }

    public function isThursday(): ?bool
    {
        return $this->thursday;
    }

    public function setThursday(?bool $thursday): static
    {
        $this->thursday = $thursday;

        return $this;
    }

    public function isFriday(): ?bool
    {
        return $this->friday;
    }

    public function setFriday(?bool $friday): static
    {
        $this->friday = $friday;

        return $this;
    }

    public function isSaturday(): ?bool
    {
        return $this->saturday;
    }

    public function setSaturday(?bool $saturday): static
    {
        $this->saturday = $saturday;

        return $this;
    }

    public function isSunday(): ?bool
    {
        return $this->sunday;
    }

    public function setSunday(?bool $sunday): static
    {
        $this->sunday = $sunday;

        return $this;
    }

    public function getStartingTime(): ?\DateTimeImmutable
    {
        return $this->startingTime;
    }

    public function setStartingTime(?\DateTimeImmutable $startingTime): static
    {
        $this->startingTime = $startingTime;

        return $this;
    }

    public function getSprayDuration(): ?int
    {
        return $this->sprayDuration;
    }

    public function setSprayDuration(?int $sprayDuration): static
    {
        $this->sprayDuration = $sprayDuration;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): static
    {
        $this->zone = $zone;

        return $this;
    }
}
