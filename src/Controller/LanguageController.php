<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LanguageController extends AbstractController
{
    #[Route('/change-language/{locale}', name: 'change_language')]
    public function changeLanguage(string $locale, Request $request): Response
    {
        if (in_array($locale, ['am', 'en'])) {
            // Պահում ենք լեզուն սեսիայի մեջ
            $request->getSession()->set('_locale', $locale);
            // Պարտադրում ենք ընթացիկ հարցման լեզվի փոփոխությունը
            $request->setLocale($locale);
        }

        $referer = $request->headers->get('referer');
        return $this->redirect($referer ?: $this->generateUrl('app_home'));
    }
}