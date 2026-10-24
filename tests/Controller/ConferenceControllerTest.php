<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ConferenceControllerTest extends WebTestCase
{
    /**
     * Vérifie que la page des conférences répond avec succès
     *
     * @return void
     */
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/conferences');

        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('h1', 'Gestion des conférences');
    }
}
