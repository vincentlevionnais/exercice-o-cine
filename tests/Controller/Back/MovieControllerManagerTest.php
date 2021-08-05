<?php

namespace App\Tests\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Teste les redirection pour le user anonyme
 */
class MovieControllerManagerTest extends WebTestCase
{
    /**
     * Browse
     */
    public function testBrowse(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/back/movie/browse');

        $this->assertResponseRedirects();
    }

    /**
     * Read
     */
    public function testRead(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/back/movie/read/1');

        $this->assertResponseRedirects();
    }

    /**
     * Edit...
     */
}
