<?php

namespace App\Controller;

use App\Entity\Game;
use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ReviewController extends AbstractController
{
    #[Route('/game/{id}/review', name: 'app_game_review_add', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function add(Game $game, Request $request, EntityManagerInterface $em): Response
    {
        $rating = (int) $request->request->get('rating');
        $content = trim((string) $request->request->get('content'));

        // Տվյալների վավերացում (Validation)
        if ($rating < 1 || $rating > 5 || empty($content)) {
            $this->addFlash('error', 'Խնդրում ենք ընտրել գնահատականը և գրել կարծիքը։');
            return $this->redirectToRoute('app_game_show', ['id' => $game->getId()]);
        }

        $review = new Review();
        $review->setRating($rating);
        $review->setContent($content);
        $review->setUser($this->getUser());
        $review->setGame($game);

        $em->persist($review);
        $em->flush();

        $this->addFlash('success', 'Շնորհակալություն։ Ձեր կարծիքը հաջողությամբ ավելացվեց։');

        return $this->redirectToRoute('app_game_show', ['id' => $game->getId()]);
    }
}