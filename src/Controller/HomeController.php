<?php

namespace App\Controller;

use App\Entity\Horse;
use App\Repository\HorseRepository;
use App\Entity\Race;
use App\Repository\RaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;

use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class HomeController extends AbstractController
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    #[Route('/', name: 'home')]
    public function index(HorseRepository $horseRepo): Response
    {
        $session = $this->requestStack->getSession();
        $race = $session->get('newRace', $this->getNewRace($horseRepo));
        $session->set('newRace', $race);
        return $this->render('home/index.html.twig', [
            'race' => $race,
        ]);
    }

    private function getNewRace(HorseRepository $horseRepo): Race
    {
        $race = new Race($horseRepo);
        $lengths = array(900, 1200, 1500, 1800);
        $race->setLength($lengths[random_int(0, count($lengths) - 1)]);

        return $race;
    }
    #[Route('/play', name: 'play')]
    public function play(): Response
    {
        $session = $this->requestStack->getSession();
        $race = $session->get('newRace');

        return $this->render('home/play.html.twig', [
            'race' => $race,
        ]);
    }
    #[Route('/api/horses', name: 'race_data', methods: ['GET'])]
    public function race_data(): JsonResponse
    {
        $encoder = [new JsonEncoder()];
        $normalizer = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizer, $encoder);
        $session = $this->requestStack->getSession();
        $race = $session->get('newRace');
        $horses = $race->getHorses();
        dump($horses);
        // $data = json_encode($horses);
        $data = $serializer->serialize($horses, "json");

        return new JsonResponse($data, Response::HTTP_OK);
    }
}