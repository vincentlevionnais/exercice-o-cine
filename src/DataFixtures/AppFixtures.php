<?php

namespace App\DataFixtures;

use DateTime;
use Faker;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Casting;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\MovieDbProvider;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
/*
        $movie = array(
            0 => array('title' => 'Interstellar', 'releaseDate' => '2014-11-05', 'duration' => '02:49:00', 'poster' => 'https://fr.web.img6.acsta.net/r_1920_1080/img/08/fe/08feaecbc56c480c082003c632f3bc2f.jpg'),
            1 => array('title' => 'Le Loup de Wall Street', 'releaseDate' => '2015-11-05', 'duration' => '02:54:00', 'poster' => 'https://fr.web.img4.acsta.net/r_1920_1080/img/37/4f/374f264b6bb0a2333d5747d274fbadc3.jpg'),
            2 => array('title' => 'Forrest Gump', 'releaseDate' => '2016-11-05', 'duration' => '01:49:00', 'poster' => 'https://fr.web.img4.acsta.net/r_1920_1080/pictures/15/10/13/15/12/514297.jpg'), 
            3 => array('title' => 'Arrête-moi si tu peux', 'releaseDate' => '2017-11-05', 'duration' => '01:55:00', 'poster' => 'https://fr.web.img3.acsta.net/r_1920_1080/medias/nmedia/00/02/54/32/aff2.jpg'),
            4 => array('title' => 'Into The Wild', 'releaseDate' => '2018-11-05', 'duration' => '02:30:00', 'poster' => 'https://fr.web.img4.acsta.net/r_1920_1080/medias/nmedia/18/64/47/78/18869162.jpg'),
            5 => array('title' => 'Jobs', 'releaseDate' => '2019-11-05', 'duration' => '03:01:00', 'poster' => 'https://fr.web.img4.acsta.net/r_1920_1080/pictures/210/244/21024430_20130805132418528.jpg'),
            6 => array('title' => 'Shutter Island', 'releaseDate' => '2020-11-05', 'duration' => '02:29:00', 'poster' => ''),
            7 => array('title' => 'Looper', 'releaseDate' => '2021-11-05', 'duration' => '02:10:00', 'poster' => ''),
            8 => array('title' => 'Seul sur Mars', 'releaseDate' => '2010-11-05', 'duration' => '02:01:00', 'poster' => ''),
            9 => array('title' => 'Gangs of New-York', 'releaseDate' => '2009-11-05', 'duration' => '02:33:00', 'poster' => ''),
        );
        
        for ([$i] = 0; [$i] < 9; [$i++]) {
            $movie[$i] = new Movie();
            $movie[$i]->setTitle($movie[$i]['title']);
            $movie[$i]->setCreatedAt(new DateTime());
            $movie[$i]->setReleaseDate(new DateTime($movie[$i]['releaseDate']));
            $movie[$i]->setDuration(new DateTime($movie[$i]['duration']));
            $movie[$i]->setPoster($movie[$i]['poster']);

            $manager->persist($movie[$i]);
        }
*/
   
        $faker = \Faker\Factory::create('fr_FR');
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Character($faker));

            // Notre fournisseur de données
            //$movieDbProvider = new MovieDbProvider();

            // Si on veut toujours les mêmes données
            //$faker->seed('BABAR');


            // Genres
            $genre = Array();
            for ($i = 1; $i <= 20; $i++) {
                $genre[$i] = new Genre();
                $genre[$i]->setName($faker->unique()->movieGenre());

                $manager->persist($genre[$i]);                
            }

            // Films
            $movie = Array();
            for ($i = 1; $i <= 20; $i++) {
                $movie[$i] = new Movie();
                $movie[$i]->setTitle($faker->unique()->movie());
                $movie[$i]->setReleaseDate($faker->dateTimeBetween('-80years', 'now'));
                $movie[$i]->setDuration($faker->numberBetween(15, 360));
                $movie[$i]->setPoster($faker->unique()->imageUrl(400, 600, 'city', true, 'Faker'));
                $movie[$i]->setRating($faker->numberBetween(1,5));


                // Association de genres
                for ($r = 1; $r <= mt_rand(1, 3); $r++) {
                    $movie[$i]->addGenre($genre[array_rand($genre)]);
                }

                $manager->persist($movie[$i]);
            }

            // Personnes
            $person = Array();
            for ($i = 1; $i <= 20; $i++) {
                $person[$i] = new Person();
                $person[$i]->setFirstname($faker->firstName());
                $person[$i]->setLastname($faker->lastName());


                $manager->persist($person[$i]);                
            }

             // Castings
            $casting = Array();
            for ($c = 1; $c < 100; $c++) {
                $casting[$i] = new Casting();
                $casting[$i]->setRole($faker->character());
                $casting[$i]->setCreditOrder(mt_rand(1, 10));

                // On va chercher un film au hasard dans la liste des films créée au-dessus
                // Variante avec mt_rand et count
                $randomMovie = $movie[array_rand($movie)];
                $casting[$i]->setMovie($randomMovie);

                $randomPerson = $person[array_rand($person)];
                $casting[$i]->setPerson($randomPerson);

                $manager->persist($casting[$i]);

            }


        $manager->flush();
     
    }
}