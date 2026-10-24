<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ConferenceController extends AbstractController
{
    /**
     * Affiche la liste des conférences de la plateforme
     *
     * @return Response Réponse HTTP avec la vue Twig
     */
    #[Route('/conferences', name: 'app_conference_index')]
    public function index(): Response
    {
        $conferences = [];

        return $this->render('conference/index.html.twig', [
            'conferences' => $conferences,
        ]);
    }
}
