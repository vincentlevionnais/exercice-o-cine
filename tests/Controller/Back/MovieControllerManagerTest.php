<?php

namespace App\Tests\Controller\Back;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * Teste les accès pour le ROLE_MANAGER
 */
class MovieControllerManagerTest extends WebTestCase
{
    /**
     * Browse
     */
    public function testBrowse(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('manager@manager.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);
        
        $crawler = $client->request('GET', '/back/movie/browse');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Read
     */
    public function testRead(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('manager@manager.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/back/movie/read/5');

        $this->assertResponseIsSuccessful();
    }

    /**
     * Edit...
     */
    public function testEdit(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('manager@manager.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/back/movie/edit/5');

        $this->assertResponseStatusCodeSame(403);
    }

    /**
     * Add
     */
    public function testAdd(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('manager@manager.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/back/movie/add');

        $this->assertResponseStatusCodeSame(403);
    }

    /**
     * Delete
     */
    public function testDelete(): void
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('manager@manager.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/back/movie/delete/5');

        $this->assertResponseStatusCodeSame(403);
    }
}
