<?php

namespace App\Command;

use Symfony\AI\Platform\Message\Message;
use Symfony\AI\Platform\Message\MessageBag;
use Symfony\AI\Platform\PlatformInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:assistant',
    description: 'Interroger l\'assistant IA via le composant Symfony AI',
)]
class AssistantCommand extends Command
{
    /**
     * Initialise la commande avec la plateforme d'inférence Symfony AI
     *
     * @param PlatformInterface $ollama Service de plateforme pour interroger les modèles locaux Ollama
     */
    public function __construct(
        private readonly PlatformInterface $ollama,
    ) {
        parent::__construct();
    }

    /**
     * Configure les arguments et options acceptés en ligne de commande
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->addArgument(
                'prompt',
                InputArgument::OPTIONAL,
                'Le texte ou la consigne à soumettre à l\'assistant',
                'Explique en 10 mots ce qu est un contrat commercial.',
            )
            ->addOption(
                'model',
                'm',
                InputOption::VALUE_OPTIONAL,
                'Le nom du modèle Ollama à utiliser',
                'llama3.1:latest',
            );
    }

    /**
     * Exécute la requête d'inférence et affiche la réponse de l'assistant
     *
     * @param InputInterface $input Flux d'entrée des arguments et options
     * @param OutputInterface $output Flux de sortie console
     * @return int Code de statut de sortie de la commande
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $prompt = (string) $input->getArgument('prompt');
        $model = (string) $input->getOption('model');

        $io->title('Assistant IA EventPulse');
        $io->note(sprintf('Modèle actif : %s', $model));
        $io->section('Prompt :');
        $io->writeln($prompt);

        $messages = new MessageBag(Message::ofUser($prompt));

        $io->section('Réponse :');
        $result = $this->ollama->invoke($model, $messages, [
            'think' => false,
            'num_predict' => 400,
            'temperature' => 0.5,
        ]);

        $io->success($result->asText());

        return Command::SUCCESS;
    }
}
