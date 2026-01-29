<?php

namespace App\Entity;

use App\Repository\ZoneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column]
    private ?float $uniformity = null;

    /**
     * @var Collection<int, HydroliqueSum>
     */
    #[ORM\OneToMany(targetEntity: HydroliqueSum::class, mappedBy: 'zone', cascade: ['persist', 'remove'])]
    private Collection $hydroliqueSums;

    #[ORM\ManyToOne(inversedBy: 'zones')]
    private ?Sensor $sensor = null;

    #[ORM\ManyToOne(inversedBy: 'zone')]
    private ?Meteo $meteo = null;

    #[ORM\Column(nullable: true)]
    private ?int $seuil_bas = null;

    #[ORM\Column(nullable: true)]
    private ?int $seuil_haut = null;

    #[ORM\Column(nullable: true)]
    private ?float $kc = null;

    #[ORM\Column(nullable: true)]
    private ?float $long = null;

    #[ORM\Column(nullable: true)]
    private ?float $lat = null;

    #[ORM\OneToOne(mappedBy: 'zone', cascade: ['persist', 'remove'])]
    private ?WeeklyDecision $weeklyDecision = null;

    public function __construct()
    {
        $this->hydroliqueSums = new ArrayCollection();
    }

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

    public function getUniformity(): ?float
    {
        return $this->uniformity;
    }

    public function setUniformity(float $uniformity): static
    {
        $this->uniformity = $uniformity;

        return $this;
    }

    /**
     * @return Collection<int, HydroliqueSum>
     */
    public function getHydroliqueSums(): Collection
    {
        return $this->hydroliqueSums;
    }

    public function addHydroliqueSum(HydroliqueSum $hydroliqueSum): static
    {
        if (!$this->hydroliqueSums->contains($hydroliqueSum)) {
            $this->hydroliqueSums->add($hydroliqueSum);
            $hydroliqueSum->setZone($this);
        }

        return $this;
    }

    public function removeHydroliqueSum(HydroliqueSum $hydroliqueSum): static
    {
        if ($this->hydroliqueSums->removeElement($hydroliqueSum)) {
            if ($hydroliqueSum->getZone() === $this) {
                $hydroliqueSum->setZone(null);
            }
        }

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

    public function getKc(): ?float
    {
        return $this->kc;
    }

    public function setKc(?float $kc): static
    {
        $this->kc = $kc;

        return $this;
    }

    public function getLong(): ?float
    {
        return $this->long;
    }

    public function setLong(?float $long): static
    {
        $this->long = $long;

        return $this;
    }

    public function getLat(): ?float
    {
        return $this->lat;
    }

    public function setLat(?float $lat): static
    {
        $this->lat = $lat;

        return $this;
    }

    public function getWeeklyDecision(): ?WeeklyDecision
    {
        return $this->weeklyDecision;
    }

    public function setWeeklyDecision(?WeeklyDecision $weeklyDecision): static
    {
        // unset the owning side of the relation if necessary
        if ($weeklyDecision === null && $this->weeklyDecision !== null) {
            $this->weeklyDecision->setZone(null);
        }

        // set the owning side of the relation if necessary
        if ($weeklyDecision !== null && $weeklyDecision->getZone() !== $this) {
            $weeklyDecision->setZone($this);
        }

        $this->weeklyDecision = $weeklyDecision;

        return $this;
    }
}
