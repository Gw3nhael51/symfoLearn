<?php

namespace App\Tests\Controller;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class SecurityControllerTest extends WebTestCase
{
    /**
     * Vérifie que l'accès à l'administration sans authentification redirige vers la connexion
     *
     * @return void
     */
    public function testAdminAccessRequiresAuthentication(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin');

        self::assertResponseRedirects('/login');
    }

    /**
     * Vérifie que la page de connexion répond avec succès
     *
     * @return void
     */
    public function testLoginPageLoadsSuccessfully(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Espace d\'administration');
    }

    /**
     * Vérifie qu'un administrateur valide peut se connecter et accéder au panneau d'administration
     *
     * @return void
     */
    public function testSuccessfulLoginRedirectsToAdmin(): void
    {
        $client = static::createClient();

        $container = static::getContainer();
        /** @var EntityManagerInterface $em */
        $em = $container->get(EntityManagerInterface::class);
        /** @var UserPasswordHasherInterface $hasher */
        $hasher = $container->get(UserPasswordHasherInterface::class);

        $adminRepo = $em->getRepository(Admin::class);
        $admin = $adminRepo->findOneBy(['username' => 'testadmin']);
        if (!$admin) {
            $admin = new Admin();
            $admin->setUsername('testadmin');
            $admin->setRoles(['ROLE_ADMIN']);
            $admin->setPassword($hasher->hashPassword($admin, 'testpass'));
            $em->persist($admin);
            $em->flush();
        }

        $crawler = $client->request('GET', '/login');
        $form = $crawler->selectButton('Se connecter')->form([
            'username' => 'testadmin',
            'password' => 'testpass',
        ]);

        $client->submit($form);
        self::assertResponseRedirects('/admin');

        $crawler = $client->followRedirect();
        self::assertResponseRedirects();

        $client->followRedirect();
        self::assertResponseIsSuccessful();
    }
}
