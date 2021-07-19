<?php

namespace App\Controller;

use DateTime;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * Contrôleur en mode "sandbox" (bac à sable, on joue ;))
 * 
 * On va appliquer BREAD
 * Browse => liste les enregistrements
 * Read => lit un enregistrement
 * Edit => met à jour
 * Add => ajoute
 * Delete => supprime
 */
class MovieController extends AbstractController
{
    /**
     * création entité
     * 
     * Add
     * 
     * @Route("/movie/add", name="movie_add")
     */
    public function add(): Response
    {
        // On crée une entité PHP
        $movie = new Movie();

        // On renseigne l'entité
        $movie->setTitle('#');
        // Date sourante pour createdAt
        $movie->setCreatedAt(new DateTime());

        dump($movie);

        // 1. On demande au Manager de *se préparer à* "persister" l'entité
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($movie);

        // 2. Exécute les requêtes SQL nécessaires (ici, INSERT INTO)
        $entityManager->flush();

        dump($movie);

        return new Response('Film ajouté : '.$movie->getId(). '</body>');
        // PS : on ajoute le </body> pour afficher la Toolbar
    }

     /**
     * Add Page
     * 
     * @Route("/add", name="addpage")
     */
    public function addpage()
    {
            return $this->render('add/index.html.twig');
    }


    /**
     * Browse Movie
     * 
     * @Route("/", name="homepage")
     */
    public function browse()
    {
        // Pour accéder aux données de la table movie
        // on passe par le Repository de l'entité Movie
        // PS : Movie::class => 'App\Entity\Movie'
        $movieRepository = $this->getDoctrine()->getRepository(Movie::class);

        // On utilise les méthodes d'accès fournies par ce Repository
        $movies = $movieRepository->findAll();

        dump($movies);

        return $this->render('movie/index.html.twig', [
            'movies' => $movies,

        ]);
    }

    /**
     * Read Movie
     * 
     * @Route("/movie/read/{id<\d+>}", name="movie_read")
     */
    public function read (MovieRepository $movieRepository, $id)
    {

        // On utilise les méthodes d'accès fournies par ce Repository
        $movie = $movieRepository->find($id);

        dump($movie);      
   

        return $this->render('movie/read.html.twig', [
            'movie' => $movie,
        ]);
    }


    /**
     * Edit Movie
     * 
     * @Route("/movie/edit/{id<\d+>}")
     */
    public function edit($id)
    {
        // On va chercher le film, on le modifie, on le sauvegarde
        $movieRepository = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $movieRepository->find($id);

        // Mise à jour
        $movie->setUpdatedAt(new DateTime());

        // Sauvegarde
        // 1. On récupère le Manager de Doctrine
        $entityManager = $this->getDoctrine()->getManager();

        // 2. Exécute les requêtes SQL nécessaires (ici, UPDATE)
        $entityManager->flush();

        return $this->redirectToRoute('movie_read', ['id' => $id]);
    }

    /**
     * Delete Movie
     * 
     * @Route("/movie/delete/{id<\d+>}")
     */
    public function delete($id)
    {
        // On va chercher le film, on le modifie, on le sauvegarde
        $movieRepository = $this->getDoctrine()->getRepository(Movie::class);
        $movie = $movieRepository->find($id);



        // Sauvegarde
        // 1. On récupère le Manager de Doctrine
        $entityManager = $this->getDoctrine()->getManager();

        // Suppression
        $entityManager->remove($movie);

        // 2. Exécute les requêtes SQL nécessaires (ici, UPDATE)
        $entityManager->flush();

        return $this->redirectToRoute('movie_browse');
    }
}
