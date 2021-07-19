<?php

namespace App\Controller;

use App\Repository\GenreRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AddController extends AbstractController
{
     /**
     * Add Genre
     * 
     * @Route("/add/genre", name="add_genre")
     */
    public function addgenre()
    {
            return $this->render('add/genre.html.twig');
    }

     /**
     * Add Person
     * 
     * @Route("/add/person", name="add_person")
     */
    public function addperson()
    {
            return $this->render('add/person.html.twig');
    }

    /**
     * Add Casting
     * 
     * @Route("/add/casting", name="add_casting")
     */
    public function addcasting()
    {
            return $this->render('add/casting.html.twig');
    }

    /**
     * Add Movie
     * 
     * @Route("/add/movie", name="add_movie")
     */
    public function addmovie(GenreRepository $genreRepository)
    {
        $genres = $genreRepository->findAll();


            return $this->render('add/movie.html.twig', ['genres' => $genres]);
    }
}