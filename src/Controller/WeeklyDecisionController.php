<?php

namespace App\Controller;

use App\Entity\WeeklyDecision;
use App\Entity\Zone;
use App\Repository\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/weekly-decision')]
final class WeeklyDecisionController extends AbstractController
{
    #[Route('/', name: 'app_weekly_decision')]
    public function index(): Response
    {
        return $this->render('weekly_decision/index.html.twig', [
            'controller_name' => 'WeeklyDecisionController',
        ]);
    }

    #[Route('/init', name: 'app_weekly_decision_init', methods: ['GET'])]
    public function init(ZoneRepository $zoneRepository, EntityManagerInterface $entityManager): Response
    {
        $zones = $zoneRepository->findAll();
        $count = 0;

        foreach ($zones as $zone) {
            if ($zone->getWeeklyDecision() === null) {
                $weeklyDecision = new WeeklyDecision();
                $weeklyDecision->setMonday(false);
                $weeklyDecision->setTuesday(false);
                $weeklyDecision->setWensday(false);
                $weeklyDecision->setThursday(false);
                $weeklyDecision->setFriday(false);
                $weeklyDecision->setSaturday(false);
                $weeklyDecision->setSunday(false);
                $weeklyDecision->setZone($zone);

                $entityManager->persist($weeklyDecision);
                $count++;
            }
        }

        $entityManager->flush();

        $this->addFlash('success', sprintf('%d WeeklyDecision(s) cree(s).', $count));

        return $this->redirectToRoute('app_zone_index');
    }

    #[Route('/{id}/update', name: 'app_weekly_decision_update', methods: ['POST'])]
    public function update(Request $request, Zone $zone, EntityManagerInterface $entityManager): Response
    {
        $weeklyDecision = $zone->getWeeklyDecision();

        if ($weeklyDecision === null) {
            $weeklyDecision = new WeeklyDecision();
            $weeklyDecision->setZone($zone);
        }

        if (!$this->isCsrfTokenValid('weekly_decision_' . $zone->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_zone_show', ['id' => $zone->getId()]);
        }

        // Mise à jour des jours (checkbox)
        $weeklyDecision->setMonday($request->request->has('monday'));
        $weeklyDecision->setTuesday($request->request->has('tuesday'));
        $weeklyDecision->setWensday($request->request->has('wensday'));
        $weeklyDecision->setThursday($request->request->has('thursday'));
        $weeklyDecision->setFriday($request->request->has('friday'));
        $weeklyDecision->setSaturday($request->request->has('saturday'));
        $weeklyDecision->setSunday($request->request->has('sunday'));

        // Mise à jour de l'heure de démarrage
        $startingTime = $request->request->get('starting_time');
        if ($startingTime) {
            $weeklyDecision->setStartingTime(new \DateTimeImmutable($startingTime));
        } else {
            $weeklyDecision->setStartingTime(null);
        }

        // Mise à jour de la durée d'arrosage
        $sprayDuration = $request->request->get('spray_duration');
        $weeklyDecision->setSprayDuration($sprayDuration !== '' ? (int) $sprayDuration : null);

        $entityManager->persist($weeklyDecision);
        $entityManager->flush();

        $this->addFlash('success', 'Planning d\'arrosage mis a jour.');

        return $this->redirectToRoute('app_zone_show', ['id' => $zone->getId()]);
    }
}
