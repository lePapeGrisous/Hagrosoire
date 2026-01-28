<?php
namespace App\Controller;

use App\Repository\ZoneRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StaticPages extends AbstractController
{
	#[Route('/', name: 'home')]
	public function index(ZoneRepository $zoneRepository): Response
	{
		$zones = $zoneRepository->findAll();

		$zonesData = array_map(function ($zone) {
			return [
				'id' => $zone->getId(),
				'name' => $zone->getName(),
				'lat' => $zone->getLat(),
				'long' => $zone->getLong(),
				'spaceType' => $zone->getSpaceType(),
				'surface' => $zone->getSurface(),
			];
		}, $zones);

		return $this->render('home.html.twig', [
			'zonesJson' => json_encode($zonesData),
		]);
	}

	#[Route('/cgu', name: 'app_cgu')]
	public function cgu(): Response
	{
		return $this->render('cgu.html.twig');
	}
}