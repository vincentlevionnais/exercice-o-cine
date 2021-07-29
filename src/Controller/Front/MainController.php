<?php

namespace App\Controller\Front;

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

class MainController extends AbstractController
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

        return $this->render('front/main/home.html.twig', [
            'movies' => $movies,
        ]);
    }

    /**
     * Affiche un film
     * 
     * @Route("/movie/{titleSlug}", name="movie_read")
     */
    public function movieRead(Movie $movie = null, CastingRepository $castingRepository, ReviewRepository $reviewRepository)
    {
        // Si film non trouvé
        if ($movie === null) {
            throw $this->createNotFoundException('Film non trouvé.');
        }

        // Pour classer les castings, on utilise notre requête custom
        // qu'on oublie pas d'envoyer à la vue
        $castings = $castingRepository->findAllByMovieJoinedToPerson($movie);
        $reviews = $reviewRepository->findAll();

        return $this->render('front/main/movie_read.html.twig', [
            'movie' => $movie,
            'castings' => $castings,
        ]);
    }

    /**
     * Commenter un film
     * 
     * @Route("/movie/{titleSlug}/add/review", name="movie_add_review", methods={"GET", "POST"})
     */
    public function movieAddReview(Movie $movie = null, Request $request): Response
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

            // Si le form était sur la page qui affiche le film
            // on aurait pu protéger l'accès à ajout du form dans le code, via
            // $this->denyAccessUnlessGranted('ROLE_USER');
            // OU BIEN
            // utiliser l'option "methods" de l'ACL
            // @link https://symfony.com/doc/current/security/access_control.html
            // - { path: ^/movie/show/\d+, roles: ROLE_USER, methods: POST }


            // Relation review <> movie
            $review->setMovie($movie);

            // On sauve la Review
            $em = $this->getDoctrine()->getManager();
            $em->persist($review);
            $em->flush();

            return $this->redirectToRoute('movie_read', ['id' => $movie->getId()]);
        }
        

        // afficher le form
        return $this->render('front/main/movie_add_review.html.twig', [
            'form' => $form->createView(),
            'movie' => $movie,
        ]);
    }



}
