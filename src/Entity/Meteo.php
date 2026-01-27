<?php

namespace App\Entity;

use App\Repository\MeteoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeteoRepository::class)]
class Meteo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column]
    private ?int $wind = null;

    #[ORM\Column]
    private ?int $rain = null;

    #[ORM\Column]
    private ?int $rain_prob = null;

    #[ORM\Column]
    private ?int $hr_pct = null;

    #[ORM\Column(nullable: true)]
    private ?int $t_c = null;

    #[ORM\Column(nullable: true)]
    private ?int $sun_hours = null;

    /**
     * @var Collection<int, Zone>
     */
    #[ORM\OneToMany(targetEntity: Zone::class, mappedBy: 'meteo')]
    private Collection $zone;

    public function __construct()
    {
        $this->zone = new ArrayCollection();
    }

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

    public function getWind(): ?int
    {
        return $this->wind;
    }

    public function setWind(int $wind): static
    {
        $this->wind = $wind;

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

    public function getRainProb(): ?int
    {
        return $this->rain_prob;
    }

    public function setRainProb(int $rain_prob): static
    {
        $this->rain_prob = $rain_prob;

        return $this;
    }

    public function getHrPct(): ?int
    {
        return $this->hr_pct;
    }

    public function setHrPct(int $hr_pct): static
    {
        $this->hr_pct = $hr_pct;

        return $this;
    }

    public function getTC(): ?int
    {
        return $this->t_c;
    }

    public function setTC(?int $t_c): static
    {
        $this->t_c = $t_c;

        return $this;
    }

    public function getSunHours(): ?int
    {
        return $this->sun_hours;
    }

    public function setSunHours(?int $sun_hours): static
    {
        $this->sun_hours = $sun_hours;

        return $this;
    }

    /**
     * @return Collection<int, Zone>
     */
    public function getZone(): Collection
    {
        return $this->zone;
    }

    public function addZone(Zone $zone): static
    {
        if (!$this->zone->contains($zone)) {
            $this->zone->add($zone);
            $zone->setMeteo($this);
        }

        return $this;
    }

    public function removeZone(Zone $zone): static
    {
        if ($this->zone->removeElement($zone)) {
            // set the owning side to null (unless already changed)
            if ($zone->getMeteo() === $this) {
                $zone->setMeteo(null);
            }
        }

        return $this;
    }
}
