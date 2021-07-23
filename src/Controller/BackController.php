<?php

namespace App\Controller;

use DateTime;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Casting;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use App\Repository\ReviewRepository;
use App\Repository\CastingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Id;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class BackController extends AbstractController
{

        /**
         * Back
         * 
         * @Route("/back", name="back")
         */
        public function backHome()
        {
                return $this->render('back/index.html.twig');
        }


//Pour les jolis titres d'entités:
//https://patorjk.com/software/taag/#p=display&v=0&c=c%2B%2B&f=ANSI%20Shadow&t=Movie%0A

//  ███╗   ███╗ ██████╗ ██╗   ██╗██╗███████╗
//  ████╗ ████║██╔═══██╗██║   ██║██║██╔════╝
//  ██╔████╔██║██║   ██║██║   ██║██║█████╗  
//  ██║╚██╔╝██║██║   ██║╚██╗ ██╔╝██║██╔══╝  
//  ██║ ╚═╝ ██║╚██████╔╝ ╚████╔╝ ██║███████╗
//  ╚═╝     ╚═╝ ╚═════╝   ╚═══╝  ╚═╝╚══════╝                                      

        /**
         * Movie Browse
         * 
         * @Route("/back/movie/browse", name="back_movie_browse")
         */
        public function browseMovie(MovieRepository $movieRepository) : Response
        {
    
            // On utilise les méthodes d'accès fournies par ce Repository
            $movies = $movieRepository->findAllOrderedByTitleAscQb();
    
            return $this->render('back/movie_browse.html.twig', [
                'movies' => $movies,
            ]);
        }

        /**
         * Movie Read
         * 
         * @Route("back/movie/read/{id<\d+>}", name="back_movie_read")
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

                dump($movie);
                dump($reviews);

                return $this->render('back/movie_read.html.twig', [
                'movie' => $movie,
                'castings' => $castings,

                ]);
        }



        /**
         * Movie Edit
         * 
         * @Route("/back/movie/edit/{id<\d+>}", name="back_movie_edit", methods={"GET", "POST"})
         */
        public function editMovie(Movie $movie,GenreRepository $genreRepository, Request $request)
        {

                $genres = $genreRepository->findAll();
                
                

                 // 404 ?
                if (null === $movie) {
                // Dans notre cas, ce code ne sera jamais exécuté,
                // car la 404 est gérée par le ParamConverter
                // => voir delete() pour récupérer la main sur notre 404
                throw $this->createNotFoundException('404 - Film non trouvé.');
                }


                // Si on est en méthode HTTP POST
                if ($request->isMethod('POST')) {
                        // Mise à jour

                        $movie->setTitle($request->request->get('title'));
                        $movie->setUpdatedAt(new DateTime());
                        $movie->setPoster($request->request->get('poster'));
                        

                        // Sauvegarde
                        // 1. On récupère le Manager de Doctrine
                        $entityManager = $this->getDoctrine()->getManager();
                        // 2. Exécute les requêtes SQL nécessaires (ici, UPDATE)
                        $entityManager->flush();

                        return $this->redirectToRoute('back_movie_read', ['id' => $movie->getId()]);
                }
        // Sinon on affiche le formulaire d'édition
        return $this->render('back/movie_edit.html.twig', [
                'movie' => $movie,
                'genres' => $genres,
               
            ]);
        
        }


        /**
         * Movie Add
         * 
         * @Route("/back/movie/add", name="back_movie_add", methods={"GET", "POST"})
         */
        public function addMovie(Request $request, GenreRepository $genreRepository)
        {
                $genres = $genreRepository->findAll();

                $movie = $this->getDoctrine()->getRepository(Movie::class);
                

                // Si on est en méthode HTTP POST
                if ($request->isMethod('POST')) {

                        // On crée une nouvelle entité avec les infos de la requête
                        $movie = new Movie();

                        // $request->request = $_POST[];
                        $movie->setTitle($request->request->get('title'));
                        
                        $movie->setPoster($request->request->get('poster'));
                    
                        

                        // On demande au Manager de sauvegarder l'entité
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($movie);
                        $entityManager->flush();

                        // On redirige vers la liste
                        return $this->redirectToRoute('backpage');
                }

                return $this->render('back/movie_add.html.twig', [
                        'genres' => $genres,
                        'movie' => $movie,
                ]);
        }

        /**
         * Movie Delete
         * 
         * ParamConverter => si $movie = null, alors notre contrôleur est exécuté
         * 
         * @Route("/back/movie/delete/{id<\d+>}", name="back_movie_delete", methods={"GET"})
         */
        public function deleteMovie(Movie $movie = null, EntityManagerInterface $entityManager)
        {
                // 404 ?
                // ParamConverter => si $post = null, alors notre contrôleur est exécuté
                if (null === $movie) {
                throw $this->createNotFoundException('404 - Film non trouvé.');
                }

                // Via l'injection, on peut utiliser directement $entityManager
                $entityManager->remove($movie);
                $entityManager->flush();

                return $this->redirectToRoute('back');
        }












//   ██████╗ ███████╗███╗   ██╗██████╗ ███████╗
//  ██╔════╝ ██╔════╝████╗  ██║██╔══██╗██╔════╝
//  ██║  ███╗█████╗  ██╔██╗ ██║██████╔╝█████╗  
//  ██║   ██║██╔══╝  ██║╚██╗██║██╔══██╗██╔══╝  
//  ╚██████╔╝███████╗██║ ╚████║██║  ██║███████╗
//   ╚═════╝ ╚══════╝╚═╝  ╚═══╝╚═╝  ╚═╝╚══════╝

        /**
         * Add Genre
         * 
         * @Route("/back/genre/add", name="back_genre_add", methods={"GET", "POST"})
         */
        public function addGenre(Request $request)

        {
                // Si on est en méthode HTTP POST
                if ($request->isMethod('POST')) {

                        // On crée une nouvelle entité avec les infos de la requête
                        $genre = new Genre();

                        // $request->request = $_POST[];
                        $genre->setName($request->request->get('name'));

                        // On demande au Manager de sauvegarder l'entité
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($genre);
                        $entityManager->flush();

                        // On redirige vers la liste
                        return $this->redirectToRoute('backpage');
                }
                return $this->render('back/genre_add.html.twig');
        }


//  ██████╗ ███████╗██████╗ ███████╗ ██████╗ ███╗   ██╗
//  ██╔══██╗██╔════╝██╔══██╗██╔════╝██╔═══██╗████╗  ██║
//  ██████╔╝█████╗  ██████╔╝███████╗██║   ██║██╔██╗ ██║
//  ██╔═══╝ ██╔══╝  ██╔══██╗╚════██║██║   ██║██║╚██╗██║
//  ██║     ███████╗██║  ██║███████║╚██████╔╝██║ ╚████║
//  ╚═╝     ╚══════╝╚═╝  ╚═╝╚══════╝ ╚═════╝ ╚═╝  ╚═══╝

        /**
         * Add Person
         * 
         * @Route("/back/person/add", name="back_person_add", methods={"GET", "POST"})
         */
        public function addperson(Request $request)
        {
                // Si on est en méthode HTTP POST
                if ($request->isMethod('POST')) {

                        // On crée une nouvelle entité avec les infos de la requête
                        $person = new Person();

                        // $request->request = $_POST[];
                        $person->setFirstname($request->request->get('firstname'));
                        $person->setlastname($request->request->get('lastname'));

                        // On demande au Manager de sauvegarder l'entité
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($person);
                        $entityManager->flush();

                        // On redirige vers la liste
                        return $this->redirectToRoute('backpage');
                }
                return $this->render('add/person_add.html.twig');
        }


//   ██████╗ █████╗ ███████╗████████╗██╗███╗   ██╗ ██████╗ 
//  ██╔════╝██╔══██╗██╔════╝╚══██╔══╝██║████╗  ██║██╔════╝ 
//  ██║     ███████║███████╗   ██║   ██║██╔██╗ ██║██║  ███╗
//  ██║     ██╔══██║╚════██║   ██║   ██║██║╚██╗██║██║   ██║
//  ╚██████╗██║  ██║███████║   ██║   ██║██║ ╚████║╚██████╔╝
//   ╚═════╝╚═╝  ╚═╝╚══════╝   ╚═╝   ╚═╝╚═╝  ╚═══╝ ╚═════╝                                                     

        /**
         * Browse Casting
         * 
         * @Route("/back/casting/browse", name="back_casting_browse", methods={"GET", "POST"})
         */
        public function browsecasting(CastingRepository $castingRepository): Response
        {
            // On utilise les méthodes d'accès fournies par ce Repository
            $castings = $castingRepository->findAll();
    
        
                return $this->render('back/casting_browse.html.twig', [
                        'castings' => $castings ]);
        }

        /**
         * Add Casting
         * 
         * @Route("/back/casting/add", name="back_casting_add", methods={"GET", "POST"})
         */
        public function addcasting(Request $request)
        {
                // Si on est en méthode HTTP POST
                if ($request->isMethod('POST')) {

                        // On crée une nouvelle entité avec les infos de la requête
                        $casting = new Casting();

                        // $request->request = $_POST[];
                        $casting->setRole($request->request->get('role'));
                        $casting->setCreditOrder($request->request->get('credit_order'));

                        // On demande au Manager de sauvegarder l'entité
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($casting);
                        $entityManager->flush();

                        // On redirige vers la liste
                        return $this->redirectToRoute('backpage');
                }

                return $this->render('back/casting_add.html.twig');
        }


}
