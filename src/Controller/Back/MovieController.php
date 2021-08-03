<?php

namespace App\Controller\Back;

use DateTime;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Casting;
use App\Form\MovieType;
use Doctrine\ORM\Mapping\Id;
use App\Service\MessageGenerator;
use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use App\Repository\ReviewRepository;
use App\Repository\CastingRepository;
use App\Service\MySlugger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\String\Slugger\SluggerInterface;


//Pour les jolis titres d'entités:
//https://patorjk.com/software/taag/#p=display&v=0&c=c%2B%2B&f=ANSI%20Shadow&t=Movie%0A

//  ███╗   ███╗ ██████╗ ██╗   ██╗██╗███████╗
//  ████╗ ████║██╔═══██╗██║   ██║██║██╔════╝
//  ██╔████╔██║██║   ██║██║   ██║██║█████╗  
//  ██║╚██╔╝██║██║   ██║╚██╗ ██╔╝██║██╔══╝  
//  ██║ ╚═╝ ██║╚██████╔╝ ╚████╔╝ ██║███████╗
//  ╚═╝     ╚═╝ ╚═════╝   ╚═══╝  ╚═╝╚══════╝   

class MovieController extends AbstractController
{

                                 

        //      __                               
        //     / /_  _________ _      __________ 
        //    / __ \/ ___/ __ \ | /| / / ___/ _ \
        //   / /_/ / /  / /_/ / |/ |/ (__  )  __/
        //  /_.___/_/   \____/|__/|__/____/\___/ 
        /**
         * Movie Browse
         * 
         * @Route("/back/movie/browse", name="back_movie_browse")
         */
        public function browseMovie(MovieRepository $movieRepository) : Response
        {
            // On utilise les méthodes d'accès fournies par ce Repository
            $movies = $movieRepository->findAllOrderedByTitleAscQb();
    
            return $this->render('back/movie/movie_browse.html.twig', [
                'movies' => $movies,
            ]);
        }

        //                          __           
        //     ________  ____ _____/ /           
        //    / ___/ _ \/ __ `/ __  /            
        //   / /  /  __/ /_/ / /_/ /             
        //  /_/   \___/\__,_/\__,_/ 
        /**
         * Movie Read
         * 
         * @Route("back/movie/read/{id<\d+>}", name="back_movie_read", methods={"GET"})
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
                



                return $this->render('back/movie/movie_read.html.twig', [
                'movie' => $movie,
                'castings' => $castings,
                

                ]);
        }

        //              ___ __                   
        //    ___  ____/ (_) /_                  
        //   / _ \/ __  / / __/                  
        //  /  __/ /_/ / / /_                    
        //  \___/\__,_/_/\__/ 
        /**
         * Movie Edit
         * 
         * @Route("/back/movie/edit/{id<\d+>}", name="back_movie_edit", methods={"GET", "POST"})
         */
        public function edit(Request $request, Movie $movie, MessageGenerator $messageGenerator, MySlugger $mySlugger): Response
        {
            // 404 ?
            if ($movie === null) {
                throw $this->createNotFoundException('Film non trouvé.');
            }
    
            $form = $this->createForm(MovieType::class, $movie);
    
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
    
                $em = $this->getDoctrine()->getManager();
                // Pas de persist() pour un edit
                $em->flush();

                $this->addFlash('success', $messageGenerator->getRandomMessage());
                
                return $this->redirectToRoute('back_movie_read', ['id' => $movie->getId()]);
                
            }
    
            // Affiche le form
            return $this->render('back/movie/movie_edit.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        //               __    __                
        //    ____ _____/ /___/ /                
        //   / __ `/ __  / __  /                 
        //  / /_/ / /_/ / /_/ /                  
        //  \__,_/\__,_/\__,_/                   
        /**
         * Movie Add
         * 
         * @Route("/back/movie/add", name="back_movie_add", methods={"GET", "POST"})
         */
        public function add(Request $request, MessageGenerator $messageGenerator, MySlugger $mySlugger): Response
        {
                $movie = new Movie();

                $form = $this->createForm(MovieType::class, $movie);

                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->persist($movie);
                $em->flush();

                $this->addFlash('success', $messageGenerator->getRandomMessage());

                return $this->redirectToRoute('back_movie_read', ['id' => $movie->getId()]);
                }

                // Affiche le form
                return $this->render('back/movie/movie_add.html.twig', [
                        'form' => $form->createView(),
                ]);
        }

        //         __     __     __              
        //    ____/ /__  / /__  / /____          
        //   / __  / _ \/ / _ \/ __/ _ \         
        //  / /_/ /  __/ /  __/ /_/  __/         
        //  \__,_/\___/_/\___/\__/\___/          
        //
        /**
         * Movie Delete
         * 
         * @todo en GET à convertir en POST ou mieux en DELETE
         * 
         * @Route("/back/movie/delete/{id<\d+>}", name="back_movie_delete", methods={"GET"})
         */
        public function delete(Movie $movie = null,MessageGenerator $messageGenerator, EntityManagerInterface $entityManager): Response
        {
            // 404 ?
            // Conditions Yoda
            // @link https://fr.wikipedia.org/wiki/Condition_Yoda
            if ($movie === null){
                throw $this->createNotFoundException("Le film n'existe pas.");
            }
    
            $entityManager->remove($movie);
            $entityManager->flush();

            $this->addFlash('success', $messageGenerator->getRandomMessage());
    
            return $this->redirectToRoute('back_movie_browse');
        }
}