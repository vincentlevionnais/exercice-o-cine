<?php

namespace App\Controller;

use DateTime;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Casting;
use App\Repository\GenreRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\DateType;
use Doctrine\DBAL\Types\PhpDateTimeMappingType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType as TypeDateType;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime as ConstraintsDateTime;
use Symfony\Component\Validator\Constraints\Time;

class AddController extends AbstractController
{

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
         * Add Genre
         * 
         * @Route("/add/genre", name="add_genre", methods={"GET", "POST"})
         */
        public function addgenre(Request $request)

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
                        return $this->redirectToRoute('addpage');
                }
                return $this->render('add/genre.html.twig');
        }

        /**
         * Add Person
         * 
         * @Route("/add/person", name="add_person", methods={"GET", "POST"})
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
                        return $this->redirectToRoute('addpage');
                }
                return $this->render('add/person.html.twig');
        }

        /**
         * Add Casting
         * 
         * @Route("/add/casting", name="add_casting", methods={"GET", "POST"})
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
                        return $this->redirectToRoute('addpage');
                }

                return $this->render('add/casting.html.twig');
        }

        /**
         * Add Movie
         * 
         * @Route("/add/movie", name="add_movie", methods={"GET", "POST"})
         */
        public function addmovie(Request $request, GenreRepository $genreRepository)
        {
                $genres = $genreRepository->findAll();

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
                        return $this->redirectToRoute('addpage');
                }

                return $this->render('add/movie.html.twig', ['genres' => $genres]);
        }
}
