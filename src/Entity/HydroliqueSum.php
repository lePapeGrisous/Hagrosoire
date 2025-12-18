<?php

namespace App\Entity;

use App\Repository\HydroliqueSumRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HydroliqueSumRepository::class)]
class HydroliqueSum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column]
    private ?int $etc = null;

    #[ORM\Column(length: 255)]
    private ?int $rain = null;

    #[ORM\Column(length: 255)]
    private ?int $stock = null;

    #[ORM\Column(length: 255)]
    private ?int $volume = null;

    #[ORM\Column(length: 255)]
    private ?string $decision = null;

    #[ORM\OneToOne(inversedBy: 'hydroliqueSum', cascade: ['persist', 'remove'])]
    private ?Zone $zone = null;

    #[ORM\OneToOne(inversedBy: 'hydroliqueSum', cascade: ['persist', 'remove'])]
    private ?Sensor $sensor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getEtc(): ?int
    {
        return $this->etc;
    }

    public function setEtc(int $etc): static
    {
        $this->etc = $etc;

        return $this;
    }

    public function getRain(): ?int
    {
        return $this->rain;
    }

    public function setRain(int $rain): static
    {
        $this->rain = $rain;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getVolume(): ?int
    {
        return $this->volume;
    }

    public function setVolume(int $volume): static
    {
        $this->volume = $volume;

        return $this;
    }

    public function getDecision(): ?string
    {
        return $this->decision;
    }

    public function setDecision(string $decision): static
    {
        $this->decision = $decision;

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

    public function getSensor(): ?Sensor
    {
        return $this->sensor;
    }

    public function setSensor(?Sensor $sensor): static
    {
        $this->sensor = $sensor;

        return $this;
    }
}
