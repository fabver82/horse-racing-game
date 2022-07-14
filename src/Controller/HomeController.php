<?php

namespace App\Controller;

use App\Entity\Horse;
use App\Repository\HorseRepository;
use App\Entity\Race;
use App\Repository\RaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(HorseRepository $horseRepo): Response
    {

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'race' => $this->getNewRace($horseRepo),
        ]);
    }

    private function getNewRace(HorseRepository $horseRepo): Race
    {
        $race = new Race();
        $lengths = array(900, 1200, 1500, 1800);
        $race->setLength($lengths[random_int(0, count($lengths) - 1)]);

        // get 10 random horses from the DB
        $allHorses = $horseRepo->findAll();
        for ($i = 0; $i < 10; $i++) {
            $randId = random_int(0, count($allHorses) - 1);
            $randHorse = array_splice($allHorses, $randId, 1)[0];
            $race->addHorse($randHorse);
        }

        return $race;
    }
}