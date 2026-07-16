<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleListener implements EventSubscriberInterface
{
    private string $defaultLocale;

    public function __construct(string $defaultLocale = 'am')
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        // Եթե սեսիա չկա, ոչինչ չենք անում
        if (!$request->hasSession()) {
            return;
        }

        // Փորձում ենք ստանալ սեսիայում պահված լեզուն
        $session = $request->getSession();
        if ($locale = $session->get('_locale')) {
            $request->setLocale($locale);
        } else {
            // Եթե սեսիայում դեռ չկա, դնում ենք հիմնական լեզուն և պահում սեսիայի մեջ
            $request->setLocale($this->defaultLocale);
            $session->set('_locale', $this->defaultLocale);
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // Սահմանում ենք priority: 15, որպեսզի այն աշխատի Symfony-ի հիմնական LocaleListener-ից (16) ՀԵՏՈ
            KernelEvents::REQUEST => [['onKernelRequest', 15]],
        ];
    }
}