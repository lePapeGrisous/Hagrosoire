<?php

namespace App\Controller;

use App\Entity\Meteo;
use App\Entity\Zone;
use App\Form\ZoneType;
use App\Repository\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/zone')]
final class ZoneController extends AbstractController
{
    private const METEO_API_URL = 'https://api.meteo-concept.com/api/forecast/daily';
    private const METEO_API_TOKEN = '962f0b3f2801a1b577d5e9cdfc178c139647c4d74d7f695b3df30d1867a05232';

    public function __construct(
        private HttpClientInterface $httpClient,
        private EntityManagerInterface $entityManager
    ) {
    }

    #[Route(name: 'app_zone_index', methods: ['GET'])]
    public function index(ZoneRepository $zoneRepository): Response
    {
        return $this->render('zone/index.html.twig', [
            'zones' => $zoneRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_zone_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $zone = new Zone();
        $form = $this->createForm(ZoneType::class, $zone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($zone);
            $entityManager->flush();

            return $this->redirectToRoute('app_zone_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('zone/new.html.twig', [
            'zone' => $zone,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_zone_show', methods: ['GET'])]
    public function show(Zone $zone): Response
    {
        $meteo = $this->getOrFetchMeteo($zone);

        return $this->render('zone/show.html.twig', [
            'zone' => $zone,
            'meteo' => $meteo,
        ]);
    }

    private function getOrFetchMeteo(Zone $zone): ?Meteo
    {
        $lat = $zone->getLat();
        $long = $zone->getLong();

        if ($lat === null || $long === null) {
            return null;
        }

        $existingMeteo = $zone->getMeteo();
        $today = new \DateTimeImmutable('today');

        // Si on a deja une meteo du jour, on la retourne
        if ($existingMeteo !== null && $existingMeteo->getDate()?->format('Y-m-d') === $today->format('Y-m-d')) {
            return $existingMeteo;
        }

        // Sinon on appelle l'API
        try {
            $response = $this->httpClient->request('GET', self::METEO_API_URL, [
                'query' => [
                    'token' => self::METEO_API_TOKEN,
                    'latlng' => sprintf('%f,%f', $lat, $long),
                ],
            ]);

            $data = $response->toArray();

            if (!isset($data['forecast']) || empty($data['forecast'])) {
                return $existingMeteo;
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
            return $existingMeteo;
        }
    }

    #[Route('/{id}/edit', name: 'app_zone_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Zone $zone, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ZoneType::class, $zone);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_zone_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('zone/edit.html.twig', [
            'zone' => $zone,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_zone_delete', methods: ['POST'])]
    public function delete(Request $request, Zone $zone, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$zone->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($zone);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_zone_index', [], Response::HTTP_SEE_OTHER);
    }
}
