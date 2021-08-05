<?php

namespace App\Tests\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Teste les redirection pour le user anonyme
 */
class MovieControllerTest extends WebTestCase
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
        $crawler = $client->request('GET', '/back/movie/read/5');

        $this->assertResponseRedirects();
    }

    /**
     * Edit...
     */
    public function testEdit(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/back/movie/edit/5');

        $this->assertResponseRedirects();
    }

    /**
     * Add
     */
    public function testAdd(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/back/movie/add');

        $this->assertResponseRedirects();
    }

    /**
     * Delete
     */
    public function testDelete(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/back/movie/delete/5');

        $this->assertResponseRedirects();
    }
}
