<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Game;
use App\Controller\Admin\GameCrudController;
use App\Controller\Admin\UserCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        // Միանգամից վերահասցեավորում ենք դեպի Խաղերի (Game) կառավարման էջ
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(GameCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Vortex Games Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Գլխավոր', 'fa fa-home');

        yield MenuItem::linkTo(
            GameCrudController::class,
            'Խաղեր',
            'fas fa-gamepad'
        );

        yield MenuItem::linkTo(
            UserCrudController::class,
            'Օգտատերեր',
            'fas fa-users'
        );
    }
}