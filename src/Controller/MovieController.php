<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Repository\CastingRepository;
use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

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
        $movies = $movieRepository->findAllOrderedByTitleAscQb();

        return $this->render('main/home.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * Affiche un film
     * 
     * @Route("/movie/{id<\d+>}", name="movie_show")
     */
    public function movieShow(Movie $movie = null, CastingRepository $castingRepository)
    {
        // Si film non trouvé
        if ($movie === null) {
            throw $this->createNotFoundException('Film non trouvé.');
        }

        // Pour classer les castings, on utilise notre requête custom
        // qu'on oublie pas d'envoyer à la vue
        $castings = $castingRepository->findAllByMovieJoinedToPerson($movie);

        dump($movie);

        return $this->render('main/movie_show.html.twig', [
            'movie' => $movie,
            'castings' => $castings,
        ]);
    }

    /**
     * Commenter un film
     * 
     * @Route("/movie/{id<\d+>}/review", name="movie_review")
     */
    public function movieReview(Movie $movie = null)
    {
        // Si film non trouvé
        if ($movie === null) {
            throw $this->createNotFoundException('Film non trouvé.');
        }

        $form = $this->createFormBuilder()

            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('content', TextType::class)
            ->add('rating')
            ->add('reactions')
            ->add('watchedAt')
            ->getForm();



        return $this->render('main/movie_review.html.twig', [
            'movie' => $movie,
            'form' => $form->createView()
        ]);
    }



}
