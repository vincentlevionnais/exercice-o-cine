<?php

namespace App\Controller;

use DateTime;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class MovieController extends AbstractController
{


    /**
     * Liste des films
     * 
     * @Route("/", name="home")
     */
    public function home(MovieRepository $movieRepository) : Response
    {

        // On utilise les méthodes d'accès fournies par ce Repository
        $movies = $movieRepository->findBy(
            [],
            ['title' => 'ASC']
        );

        return $this->render('main/home.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * Affiche un film
     * 
     * @Route("/movie/{id<\d+>}", name="movie_show")
     */
    public function movieShow(Movie $movie = null)
    {

        // Film non trouvé
        if ($movie === null) {
             throw $this->createNotFoundException('Film non trouvé.');
        }

        return $this->render('main/movie_show.html.twig', [
            'movie' => $movie,
        ]);
    }

}
