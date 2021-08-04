<?php

namespace App\Controller\Api;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MovieController extends AbstractController
{
    /**
     * Get movies collection
     * 
     * @Route("/api/movies", name="api_movies_get", methods="GET")
     */
    public function index(MovieRepository $movieRepository): Response
    {
        $movies = $movieRepository->findAll();

        // On demande à Symfony de "sérialiser" nos entités
        // sous forme de JSON
        return $this->json($movies, 200, [], ['groups' => 'movies_get']);
    }

    /**
     * Get a movie by id
     * 
     * @Route("/api/movies/{id<\d+>}", name="api_movies_get_item", methods="GET")
     */
    public function show(Movie $movie): Response
    {       
        return $this->json(['movie' => $movie], Response::HTTP_OK, [], ['groups' => 'movies_get']);
    }

    /**
     * Create a new movie
     * 
     * @Route("/api/movies", name="api_movies_post", methods="POST")
     */
    public function create(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $jsonContent = $request->getContent();

        // On désérialise le JSON vers une entité Movie
        // @see https://symfony.com/doc/current/components/serializer.html#deserializing-an-object
        $movie = $serializer->deserialize($jsonContent, Movie::class, 'json');

        // On valide l'entité avec le service Validator
        $errors = $validator->validate($movie);

        // Si la validation rencontre des erreurs
        // ($errors se comporte comme un tableau et contient un élément par erreur)
        if (count($errors) > 0) {
            return $this->json(["errors" => $errors],Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //dd($movie);

        // On persist, on flush
        $entityManager->persist($movie);
        $entityManager->flush();

        // REST nous demande un statut 201 et un header Location: url
        return $this->redirectToRoute(
            'api_movies_get_item',
            ['id' => $movie->getId()],
            // C'est cool d'utiliser les constantes de classe !
            // => ça aide à la lecture du code et au fait de penser objet
            Response::HTTP_CREATED
        );
    }
}
