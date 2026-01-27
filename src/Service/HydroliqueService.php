<?php

namespace App\Service;

use App\Entity\HydroliqueSum;
use App\Entity\Zone;
use App\Repository\HydroliqueSumRepository;
use App\Repository\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;

class HydroliqueService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ZoneRepository $zoneRepository,
        private HydroliqueSumRepository $hydroliqueSumRepository
    ) {
    }

    /**
     * Execute l'algorithme pour toutes les zones
     * @return array Liste des resultats
     */
    public function calculateAllZones(): array
    {
        $zones = $this->zoneRepository->findAll();
        $results = [];

        foreach ($zones as $zone) {
            $result = $this->decisionJour($zone);
            if ($result) {
                $results[] = [
                    'zone' => $zone->getName(),
                    'decision' => $result->getDecision(),
                    'volume' => $result->getVolume(),
                    'stock' => $result->getStock(),
                ];
            }
        }

        $this->entityManager->flush();

        return $results;
    }

    /**
     * Execute l'algorithme pour une zone specifique
     */
    public function calculateForZone(Zone $zone): ?HydroliqueSum
    {
        $result = $this->decisionJour($zone);
        $this->entityManager->flush();

        return $result;
    }

    /**
     * Algorithme de decision d'arrosage
     *
     * Donnees utilisees:
     * - Zone: kc, ru, seuil_bas, seuil_haut, surface, uniformity
     * - Meteo: t_c, hr_pct, wind, rain, rain_prob, sun_hours
     * - HydroliqueSum precedent: stock
     */
    private function decisionJour(Zone $zone): ?HydroliqueSum
    {
        $today = new \DateTimeImmutable('today');

        // Verifier si bilan existe deja pour aujourd'hui
        $existingBilan = $this->findTodayBilan($zone);
        if ($existingBilan !== null) {
            return null;
        }

        // Recuperer les donnees
        $meteo = $zone->getMeteo();
        $stockHier = $this->getStockHier($zone);

        // Parametres zone
        $kc = $zone->getKc() ?? 0.85;
        $ru = $zone->getRu() ?? 100;
        $seuilBas = ($zone->getSeuilBas() ?? 40) / 100;
        $seuilHaut = ($zone->getSeuilHaut() ?? 80) / 100;
        $surface = $zone->getSurface() ?? 100;
        $uniformite = $zone->getUniformity() ?? 0.8;

        // Donnees meteo
        $temperature = $meteo?->getTC() ?? 20;
        $humidite = $meteo?->getHrPct() ?? 50;
        $vent = $meteo?->getWind() ?? 10;
        $sunHours = $meteo?->getSunHours() ?? 8;
        $pluie24 = $meteo?->getRain() ?? 0;
        $proba24 = ($meteo?->getRainProb() ?? 0) / 100;

        // Calcul ET0 (formule simplifiee basee sur Hargreaves)
        $et0 = $this->calculateET0($temperature, $humidite, $vent, $sunHours);

        // ETc = ET0 * Kc
        $etc = $et0 * $kc;

        // Pluie effective (80% si proba >= 60%, max 10mm pris en compte)
        $pluieEff = 0;
        if ($proba24 >= 0.6) {
            $pluieEff = min($pluie24, 10) * 0.8;
        }

        // Stock fin de journee
        $stockFin = max(0, min($ru, $stockHier - $etc + $pluieEff));
        $ratio = $stockFin / $ru;

        // Decision
        $decision = 'ANNULER';
        $volume = 0;

        // Si pluie significative prevue (>= 5mm avec proba >= 60%), reporter
        if ($pluie24 >= 5 && $proba24 >= 0.6) {
            $decision = 'REPORTER';
        }
        // Si stock sous seuil bas, arroser
        elseif ($ratio < $seuilBas) {
            $decision = 'ARROSER';
            $besoin = ($seuilHaut * $ru) - $stockFin;
            $volume = ($besoin * $surface) / max($uniformite, 0.5);
        }

        // Creer le bilan
        $hydroliqueSum = new HydroliqueSum();
        $hydroliqueSum->setDate($today);
        $hydroliqueSum->setZone($zone);
        $hydroliqueSum->setDecision($decision);
        $hydroliqueSum->setVolume((int) round($volume));
        $hydroliqueSum->setStock((int) round($stockFin));
        $hydroliqueSum->setEtc((int) round($etc));
        $hydroliqueSum->setRain((int) round($pluieEff));

        if ($zone->getSensor() !== null) {
            $hydroliqueSum->setSensor($zone->getSensor());
        }

        $this->entityManager->persist($hydroliqueSum);

        return $hydroliqueSum;
    }

    /**
     * Calcul ET0 simplifie
     */
    private function calculateET0(int $temperature, int $humidite, int $vent, int $sunHours): float
    {
        // Base: environ 0.2 mm par degre au-dessus de 5°C
        $et0Base = max(0, ($temperature - 5) * 0.2);

        // Correction humidite (air sec = plus d'evaporation)
        $corrHumidite = 1 + (50 - $humidite) * 0.01;

        // Correction vent
        $corrVent = 1 + ($vent * 0.02);

        // Correction ensoleillement (plus de soleil = plus d'evaporation)
        $corrSoleil = 0.5 + ($sunHours / 16);

        return $et0Base * $corrHumidite * $corrVent * $corrSoleil;
    }

    /**
     * Recupere le stock de la veille
     */
    private function getStockHier(Zone $zone): int
    {
        $lastBilan = $this->hydroliqueSumRepository->findOneBy(
            ['zone' => $zone],
            ['date' => 'DESC']
        );

        if ($lastBilan !== null) {
            return $lastBilan->getStock() ?? $zone->getRu() ?? 100;
        }

        // Premier bilan: on part du RU max (sol plein)
        return $zone->getRu() ?? 100;
    }

    /**
     * Verifie si un bilan existe pour aujourd'hui
     */
    private function findTodayBilan(Zone $zone): ?HydroliqueSum
    {
        $today = new \DateTimeImmutable('today');
        $tomorrow = new \DateTimeImmutable('tomorrow');

        return $this->hydroliqueSumRepository->createQueryBuilder('h')
            ->where('h.zone = :zone')
            ->andWhere('h.date >= :today')
            ->andWhere('h.date < :tomorrow')
            ->setParameter('zone', $zone)
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
