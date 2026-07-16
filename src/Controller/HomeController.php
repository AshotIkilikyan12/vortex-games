<?php

namespace App\Controller;

use App\Entity\Game; // Ավելացրեք այս տողը
use App\Repository\GameRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(GameRepository $gameRepository): Response
    {
        // Բազայից վերցնում ենք բոլոր խաղերը
        $games = $gameRepository->findAll();

        return $this->render('home/index.html.twig', [
            'games' => $games,
        ]);
    }

    // Ավելացնում ենք խաղի անհատական էջի Route-ը
    #[Route('/game/{id}', name: 'app_game_show')]
    public function show(Game $game): Response
    {
        return $this->render('home/show.html.twig', [
            'game' => $game,
        ]);
    }
}