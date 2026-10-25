<?php

namespace App\Command;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Crée un nouvel utilisateur administrateur',
)]
class CreateAdminCommand extends Command
{
    /**
     * Initialise la commande avec les dépendances requises
     *
     * @param EntityManagerInterface $entityManager Gestionnaire des entités de la base de données
     * @param UserPasswordHasherInterface $passwordHasher Service de hachage sécurisé des mots de passe
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
        parent::__construct();
    }

    /**
     * Configure les arguments acceptés en ligne de commande
     *
     * @return void
     */
    protected function configure(): void
    {
        $this
            ->addArgument('username', InputArgument::OPTIONAL, "Identifiant de l'administrateur")
            ->addArgument('password', InputArgument::OPTIONAL, "Mot de passe de l'administrateur");
    }

    /**
     * Exécute l'enregistrement du compte administrateur en base de données
     *
     * @param InputInterface $input Flux d'entrée des arguments et options
     * @param OutputInterface $output Flux de sortie console
     * @return int Statut de réussite ou d'erreur de la commande
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $username = $input->getArgument('username');
        if (!$username) {
            $username = $io->ask("Identifiant de l'administrateur", 'admin');
        }

        $password = $input->getArgument('password');
        if (!$password) {
            $password = $io->askHidden("Mot de passe de l'administrateur");
        }

        $existing = $this->entityManager->getRepository(Admin::class)->findOneBy(['username' => $username]);
        $admin = $existing ?? new Admin();
        $admin->setUsername($username);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, $password));

        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        $io->success(sprintf('Compte administrateur "%s" enregistré avec succès.', $username));

        return Command::SUCCESS;
    }
}
