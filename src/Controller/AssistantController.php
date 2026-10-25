<?php

namespace App\Controller;

use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\AI\Platform\PlatformInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
final class AssistantController extends AbstractController
{
    /**
     * Affiche l'interface de l'assistant IA et traite les requêtes d'inférence
     *
     * @param Request $request Requête HTTP entrante contenant le prompt et le modèle
     * @param PlatformInterface $ollama Service de plateforme Symfony AI pour interroger Ollama
     * @return Response Réponse HTTP (HTML Twig ou JsonResponse pour les requêtes AJAX)
     */
    #[Route('/assistant', name: 'app_assistant', methods: ['GET', 'POST'])]
    public function index(Request $request, PlatformInterface $ollama): Response
    {
        $prompt = (string) $request->request->get('prompt', '');
        $model = (string) $request->request->get('model', 'llama3.1:latest');
        $response = null;
        $error = null;

        if ($request->isMethod('POST') && '' !== trim($prompt)) {
            try {
                $messages = new MessageBag(Message::ofUser($prompt));
                $result = $ollama->invoke($model, $messages, [
                    'think' => false,
                    'num_predict' => 400,
                    'temperature' => 0.5,
                ]);
                $response = $result->asText();
            } catch (\Throwable $e) {
                $error = $e->getMessage();
            }

            if ($request->isXmlHttpRequest() || str_contains((string) $request->headers->get('Accept'), 'application/json')) {
                return new JsonResponse([
                    'success' => null === $error,
                    'model' => $model,
                    'response' => $response,
                    'error' => $error,
                ]);
            }
        }

        return $this->render('assistant/index.html.twig', [
            'prompt' => $prompt,
            'model' => $model,
            'response' => $response,
            'error' => $error,
        ]);
    }
}
