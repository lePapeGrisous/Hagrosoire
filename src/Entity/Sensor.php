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
    private ?int $longitude = null;

    #[ORM\Column]
    private ?int $latitude = null;

    #[ORM\Column]
    private ?int $batterie = null;

    #[ORM\Column]
    private ?int $temperature = null;

    #[ORM\OneToOne(mappedBy: 'sensor', cascade: ['persist', 'remove'])]
    private ?HydroliqueSum $hydroliqueSum = null;

    /**
     * @var Collection<int, Zone>
     */
    #[ORM\OneToMany(targetEntity: Zone::class, mappedBy: 'sensor')]
    private Collection $zones;

    public function __construct()
    {
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

    public function getLongitude(): ?int
    {
        return $this->longitude;
    }

    public function setLongitude(int $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?int
    {
        return $this->latitude;
    }

    public function setLatitude(int $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getBatterie(): ?int
    {
        return $this->batterie;
    }

    public function setBatterie(int $batterie): static
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

    public function getHydroliqueSum(): ?HydroliqueSum
    {
        return $this->hydroliqueSum;
    }

    public function setHydroliqueSum(?HydroliqueSum $hydroliqueSum): static
    {
        // unset the owning side of the relation if necessary
        if ($hydroliqueSum === null && $this->hydroliqueSum !== null) {
            $this->hydroliqueSum->setSensor(null);
        }

        // set the owning side of the relation if necessary
        if ($hydroliqueSum !== null && $hydroliqueSum->getSensor() !== $this) {
            $hydroliqueSum->setSensor($this);
        }

        $this->hydroliqueSum = $hydroliqueSum;

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
