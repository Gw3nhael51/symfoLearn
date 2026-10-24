<?php

namespace App\Controller;

use App\Repository\ConferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ConferenceController extends AbstractController
{
    /**
     * Affiche la liste des conférences enregistrées en base de données
     *
     * @param ConferenceRepository $conferenceRepository Répertoire Doctrine pour accéder aux conférences
     * @return Response Réponse HTTP avec la vue Twig
     */
    #[Route('/conferences', name: 'app_conference_index')]
    public function index(ConferenceRepository $conferenceRepository): Response
    {
        return $this->render('conference/index.html.twig', [
            'conferences' => $conferenceRepository->findAll(),
        ]);
    }
}
