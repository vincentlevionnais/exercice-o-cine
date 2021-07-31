<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 *  Service qui cause à OMDB API
 */
 class OmdbApi
 {
    /**
     * 
     */
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetch(string $title): array
    {
        $response = $this->client->request(
            'GET',
            'http://omdbapi.com/?apiKey=83bfb8c6&t='. $title
        );

       
        // On converti en JSON
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]

        // On retourne le tableau
        return $content;
    }

    /**
     * Renvoie l'URL du poster
     * 
     * @param string $title Titre du film
     * @return null|string
     */
    public function fectchPoster(string $title)
    {
        $content = $this->fetch($title);

        // Clé Poster existe?
        if (array_key_exists('Poster', $content)) {
            return $content['Poster'];
        }

        return null;
    }
 }



 