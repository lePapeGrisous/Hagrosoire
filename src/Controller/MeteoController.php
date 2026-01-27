<?php

namespace App\Controller;

use App\Entity\Meteo;
use App\Entity\Zone;
use App\Repository\MeteoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/meteo')]
final class MeteoController extends AbstractController
{
    private const API_URL = 'https://api.meteo-concept.com/api/forecast/daily';
    private const API_TOKEN = '962f0b3f2801a1b577d5e9cdfc178c139647c4d74d7f695b3df30d1867a05232';

    public function __construct(
        private HttpClientInterface $httpClient,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route('/update/{id}', name: 'app_meteo_update', methods: ['GET'])]
    public function updateWeatherForZone(Zone $zone, MeteoRepository $meteoRepository): Response
    {
        $meteo = $this->fetchAndUpdateMeteo($zone);

        if ($meteo) {
            $this->addFlash('success', 'Meteo mise a jour avec succes');
        } else {
            $this->addFlash('error', 'Impossible de recuperer les donnees meteo');
        }

        return $this->redirectToRoute('app_zone_show', ['id' => $zone->getId()]);
    }

    public function fetchAndUpdateMeteo(Zone $zone): ?Meteo
    {
        $lat = $zone->getLat();
        $long = $zone->getLong();

        if ($lat === null || $long === null) {
            return null;
        }

        // Verifier si on a deja une meteo du jour pour cette zone
        $existingMeteo = $zone->getMeteo();
        $today = new \DateTimeImmutable('today');

        if ($existingMeteo !== null && $existingMeteo->getDate()?->format('Y-m-d') === $today->format('Y-m-d')) {
            return $existingMeteo;
        }

        try {
            $response = $this->httpClient->request('GET', self::API_URL, [
                'query' => [
                    'token' => self::API_TOKEN,
                    'latlng' => sprintf('%f,%f', $lat, $long),
                ],
            ]);

            $data = $response->toArray();

            if (!isset($data['forecast']) || empty($data['forecast'])) {
                return null;
            }

            $forecast = $data['forecast'][0];

            $meteo = $existingMeteo ?? new Meteo();
            $meteo->setDate($today);
            $meteo->setTC($forecast['tmax'] ?? null);
            $meteo->setWind($forecast['wind10m'] ?? 0);
            $meteo->setRain($forecast['rr10'] ?? $forecast['rr1'] ?? 0);
            $meteo->setRainProb($forecast['probarain'] ?? 0);
            $meteo->setHrPct($forecast['rh10'] ?? $forecast['rh2m'] ?? 0);
            $meteo->setSunHours($forecast['sun_hours'] ?? null);

            if ($existingMeteo === null) {
                $this->entityManager->persist($meteo);
                $zone->setMeteo($meteo);
            }

            $this->entityManager->flush();

            return $meteo;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getOrFetchMeteo(Zone $zone): ?Meteo
    {
        $existingMeteo = $zone->getMeteo();
        $today = new \DateTimeImmutable('today');

        if ($existingMeteo !== null && $existingMeteo->getDate()?->format('Y-m-d') === $today->format('Y-m-d')) {
            return $existingMeteo;
        }

        return $this->fetchAndUpdateMeteo($zone);
    }
}
