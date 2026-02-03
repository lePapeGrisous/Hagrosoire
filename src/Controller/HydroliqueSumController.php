<?php

namespace App\Controller;

use App\Entity\Zone;
use App\Service\HydroliqueService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/hydrolique')]
final class HydroliqueSumController extends AbstractController
{
    public function __construct(
        private HydroliqueService $hydroliqueService
    ) {
    }

    #[Route('/sum', name: 'app_hydrolique_sum')]
    public function index(): Response
    {
        return $this->render('hydrolique_sum/index.html.twig', [
            'controller_name' => 'HydroliqueSumController',
        ]);
    }

    /**
     * Execute l'algorithme pour toutes les zones
     */
    #[Route('/calculate-all', name: 'app_hydrolique_calculate_all')]
    public function calculateAll(): Response
    {
        $results = $this->hydroliqueService->calculateAllZones();

        return $this->json([
            'success' => true,
            'date' => (new \DateTimeImmutable())->format('Y-m-d'),
            'results' => $results,
        ]);
    }

    /**
     * Execute l'algorithme pour une zone specifique
     */
    #[Route('/calculate/{id}', name: 'app_hydrolique_calculate_zone')]
    public function calculateForZone(Zone $zone): Response
    {
        $result = $this->hydroliqueService->calculateForZone($zone);

        if ($result) {
            $this->addFlash('success', sprintf(
                'Bilan calcule : %s - Volume : %d L',
                $result->getDecision(),
                $result->getVolume()
            ));
        } else {
            $this->addFlash('warning', 'Bilan deja existant pour aujourd\'hui.');
        }

        return $this->redirectToRoute('app_zone_show', ['id' => $zone->getId()]);
    }
}
