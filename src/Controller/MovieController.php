<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\CastingRepository;
use App\Repository\MovieRepository;
use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
    public function movieShow(Movie $movie = null, CastingRepository $castingRepository, ReviewRepository $reviewRepository)
    {
        // Si film non trouvé
        if ($movie === null) {
            throw $this->createNotFoundException('Film non trouvé.');
        }

        // Pour classer les castings, on utilise notre requête custom
        // qu'on oublie pas d'envoyer à la vue
        $castings = $castingRepository->findAllByMovieJoinedToPerson($movie);
        $reviews = $reviewRepository->findAll();

        dump($movie);
        dump($reviews);

        return $this->render('main/movie_show.html.twig', [
            'movie' => $movie,
            'castings' => $castings,

        ]);
    }

    /**
     * Commenter un film
     * 
     * @Route("/movie/{id<\d+>}/add/review", name="movie_add_review", methods={"GET", "POST"})
     */
    public function movieAddReview(Movie $movie = null, Request $request)
    {
        // Lorsque film non trouvé
        if ($movie === null) {
            throw $this->createNotFoundException('Film non trouvé.');
        }

        // Nouvelle critique
        $review = new Review();

        // Création du form, associé à l'entité $review
        $form = $this->createForm(ReviewType::class, $review);

        // Prendre en charge la requête
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Relation review <> movie
            $review->setMovie($movie);

            // On sauve la Review
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            return $this->redirectToRoute('movie_show', ['id' => $movie->getId()]);
        }
        

        // afficher le form
        return $this->render('main/movie_add_review.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
        ]);
    }



}
