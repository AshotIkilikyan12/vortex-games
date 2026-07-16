<?php

namespace App\Controller;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LibraryController extends AbstractController
{
    #[Route('/library', name: 'app_library')]
    #[IsGranted('ROLE_USER')] // Միայն մուտք գործած օգտատերերի համար
    public function index(): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();
        $games = $user->getGames();

        return $this->render('library/index.html.twig', [
            'games' => $games,
        ]);
    }

    #[Route('/library/add/{id}', name: 'app_library_add', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function add(Game $game, EntityManagerInterface $em): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        // Եթե խաղն արդեն գրադարանում չէ, ավելացնում ենք
        if (!$user->getGames()->contains($game)) {
            $user->addGame($game);
            $em->flush();
            $this->addFlash('success', 'Խաղը հաջողությամբ ավելացվեց ձեր գրադարանին:');
        } else {
            $this->addFlash('warning', 'Այս խաղն արդեն ձեր գրադարանում է։');
        }

        return $this->redirectToRoute('app_library');
    }
}