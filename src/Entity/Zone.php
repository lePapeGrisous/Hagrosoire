<?php

namespace App\Entity;

use App\Repository\ZoneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ZoneRepository::class)]
class Zone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $space_type = null;

    #[ORM\Column]
    private ?int $ru = null;

    #[ORM\Column(length: 255)]
    private ?int $surface = null;

    #[ORM\Column(length: 255)]
    private ?string $uniformity = null;

    #[ORM\OneToOne(mappedBy: 'zone', cascade: ['persist', 'remove'])]
    private ?HydroliqueSum $hydroliqueSum = null;

    #[ORM\ManyToOne(inversedBy: 'zones')]
    private ?Sensor $sensor = null;

    #[ORM\ManyToOne(inversedBy: 'zone')]
    private ?Meteo $meteo = null;

    #[ORM\Column(nullable: true)]
    private ?int $seuil_bas = null;

    #[ORM\Column(nullable: true)]
    private ?int $seuil_haut = null;

    #[ORM\Column(nullable: true)]
    private ?int $kc = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSpaceType(): ?string
    {
        return $this->space_type;
    }

    public function setSpaceType(string $space_type): static
    {
        $this->space_type = $space_type;

        return $this;
    }

    public function getRu(): ?int
    {
        return $this->ru;
    }

    public function setRu(int $ru): static
    {
        $this->ru = $ru;

        return $this;
    }

    public function getSurface(): ?int
    {
        return $this->surface;
    }

    public function setSurface(int $surface): static
    {
        $this->surface = $surface;

        return $this;
    }

    public function getUniformity(): ?string
    {
        return $this->uniformity;
    }

    public function setUniformity(string $uniformity): static
    {
        $this->uniformity = $uniformity;

        return $this;
    }

    public function getHydroliqueSum(): ?HydroliqueSum
    {
        return $this->hydroliqueSum;
    }

    public function setHydroliqueSum(?HydroliqueSum $hydroliqueSum): static
    {
        // unset the owning side of the relation if necessary
        if ($hydroliqueSum === null && $this->hydroliqueSum !== null) {
            $this->hydroliqueSum->setZone(null);
        }

        // set the owning side of the relation if necessary
        if ($hydroliqueSum !== null && $hydroliqueSum->getZone() !== $this) {
            $hydroliqueSum->setZone($this);
        }

        $this->hydroliqueSum = $hydroliqueSum;

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

    public function getMeteo(): ?Meteo
    {
        return $this->meteo;
    }

    public function setMeteo(?Meteo $meteo): static
    {
        $this->meteo = $meteo;

        return $this;
    }

    public function getSeuilBas(): ?int
    {
        return $this->seuil_bas;
    }

    public function setSeuilBas(?int $seuil_bas): static
    {
        $this->seuil_bas = $seuil_bas;

        return $this;
    }

    public function getSeuilHaut(): ?int
    {
        return $this->seuil_haut;
    }

    public function setSeuilHaut(?int $seuil_haut): static
    {
        $this->seuil_haut = $seuil_haut;

        return $this;
    }

    public function getKc(): ?int
    {
        return $this->kc;
    }

    public function setKc(?int $kc): static
    {
        $this->kc = $kc;

        return $this;
    }
}
