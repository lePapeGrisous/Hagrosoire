<?php

namespace App\Entity;

use App\Repository\SensorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SensorRepository::class)]
class Sensor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column]
    private ?int $humidity = null;

    #[ORM\Column]
    private ?float $longitude = null;

    #[ORM\Column]
    private ?float $latitude = null;

    #[ORM\Column]
    private ?float $batterie = null;

    #[ORM\Column]
    private ?int $temperature = null;

    /**
     * @var Collection<int, HydroliqueSum>
     */
    #[ORM\OneToMany(targetEntity: HydroliqueSum::class, mappedBy: 'sensor', cascade: ['persist', 'remove'])]
    private Collection $hydroliqueSums;

    /**
     * @var Collection<int, Zone>
     */
    #[ORM\OneToMany(targetEntity: Zone::class, mappedBy: 'sensor')]
    private Collection $zones;

    public function __construct()
    {
        $this->hydroliqueSums = new ArrayCollection();
        $this->zones = new ArrayCollection();
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

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getHumidity(): ?int
    {
        return $this->humidity;
    }

    public function setHumidity(int $humidity): static
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getBatterie(): ?float
    {
        return $this->batterie;
    }

    public function setBatterie(float $batterie): static
    {
        $this->batterie = $batterie;

        return $this;
    }

    public function getTemperature(): ?int
    {
        return $this->temperature;
    }

    public function setTemperature(int $temperature): static
    {
        $this->temperature = $temperature;

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
            $hydroliqueSum->setSensor($this);
        }

        return $this;
    }

    public function removeHydroliqueSum(HydroliqueSum $hydroliqueSum): static
    {
        if ($this->hydroliqueSums->removeElement($hydroliqueSum)) {
            if ($hydroliqueSum->getSensor() === $this) {
                $hydroliqueSum->setSensor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Zone>
     */
    public function getZones(): Collection
    {
        return $this->zones;
    }

    public function addZone(Zone $zone): static
    {
        if (!$this->zones->contains($zone)) {
            $this->zones->add($zone);
            $zone->setSensor($this);
        }

        return $this;
    }

    public function removeZone(Zone $zone): static
    {
        if ($this->zones->removeElement($zone)) {
            // set the owning side to null (unless already changed)
            if ($zone->getSensor() === $this) {
                $zone->setSensor(null);
            }
        }

        return $this;
    }
}
