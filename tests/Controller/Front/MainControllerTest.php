<?php

namespace App\Tests\Controller\Front;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    /**
     * Test de la home
     */
    public function testHome(): void
    {
        // Crée un client HTTP
        $client = static::createClient();
        // Envoie une requête vers l'url '/'
        $crawler = $client->request('GET', '/');

        // Est-ce que la réponse a un statut 2xx
        $this->assertResponseIsSuccessful();
        // Est-ce que je suis bien sur la page d'accueil
        $this->assertSelectorTextContains('main h2', 'All Movies');
    }

    /**
     * Test de la vue d'un film selon son slug
     */
    public function testMovieRead(): void
    {
        // Crée un client HTTP
        $client = static::createClient();
        // Envoie une requête vers l'url '/'
        $crawler = $client->request('GET', '/movie/looper');

        // Est-ce que la réponse a un statut 2xx
        $this->assertResponseIsSuccessful();
        // Est-ce que je suis bien sur la page d'accueil
        $this->assertSelectorTextContains('main h2', 'Looper');
    }

    /**
     * L'anonyme n'a pas accès à l'écriture d'une Review
     * et se trouve redirigé
     */
    public function testReviewAddFailure()
    {
        // Crée un client HTTP
        $client = static::createClient();
        // Envoie une requête vers l'url '/'
        $crawler = $client->request('GET', '/movie/looper/add/review');
        // Si form dans la page show :
        // $crawler = $client->request('POST', '/movie/rambo-2');

        $this->assertResponseRedirects('/login');
    }

    /**
     * Le ROLE_USER à accès à l'écriture d'une Review
     */
    public function testReviewAddSuccess()
    {

        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@user.com');
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/movie/looper/add/review');


        $this->assertResponseIsSuccessful();
    }

    /**
     * Le ROLE_USER peut poster une Review
     */
    public function testReviewAdd()
    {

        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@user.com');
        $client->loginUser($testUser);

        $crawler = $client->request('POST', '/movie/looper/add/review');


        $this->assertResponseIsSuccessful();
    }
}
