<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\Conference;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    /**
     * Redirige l'accueil de l'administration vers la liste des conférences
     *
     * @return Response Redirection HTTP
     */
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ConferenceCrudController::class)->generateUrl());
    }

    /**
     * Définit le titre et les paramètres généraux du tableau de bord
     *
     * @return Dashboard Configuration du tableau de bord
     */
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('EventPulse Admin');
    }

    /**
     * Configure les entrées du menu de navigation latérale
     *
     * @return iterable Liste des éléments de menu
     */
    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Retour au site', 'fa fa-home', 'homepage');
        yield MenuItem::linkTo(ConferenceCrudController::class, 'Conferences', 'fas fa-map-marker-alt');
        yield MenuItem::linkTo(CommentCrudController::class, 'Comments', 'fas fa-comments');
        yield MenuItem::section();
        yield MenuItem::linkToLogout('Déconnexion', 'fas fa-sign-out-alt');
    }

    /**
     * Configure le menu de l'utilisateur connecté dans la barre supérieure
     *
     * @param UserInterface $user Administrateur connecté
     * @return UserMenu Configuration du menu avec lien de déconnexion
     */
    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user)
            ->setName($user->getUserIdentifier())
            ->displayUserName();
    }
}
