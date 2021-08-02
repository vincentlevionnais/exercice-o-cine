<?php

namespace App\DataFixtures;

use Faker;
use DateTime;
use App\Entity\User;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Casting;
use App\Service\MySlugger;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\MovieDbProvider;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\DateTime as ConstraintsDateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping\Id;
use phpDocumentor\Reflection\Types\Integer;

class AppFixtures extends Fixture
{
    // Le service MySlugger
    private $mySlugger;

    // Injection des services nécessaires
    public function __construct(MySlugger $mySlugger)
    {
        $this->mySlugger = $mySlugger;
    }

    public function load(ObjectManager $manager)
    {

        
        //  ██████╗  ██████╗ ██╗     ███████╗███████╗
        //  ██╔══██╗██╔═══██╗██║     ██╔════╝██╔════╝
        //  ██████╔╝██║   ██║██║     █████╗  ███████╗
        //  ██╔══██╗██║   ██║██║     ██╔══╝  ╚════██║
        //  ██║  ██║╚██████╔╝███████╗███████╗███████║
        //  ╚═╝  ╚═╝ ╚═════╝ ╚══════╝╚══════╝╚══════╝
        //                                                      
        // 3 users "en dur" : USER, MANAGER, ADMIN
        // mot de passe = user, manager, admin
        print('ROLE_USER' . PHP_EOL);
        $user = new User();
        $user->setEmail('user@user.com');
        // "user" via "bin/console security:hash-password"
        $user->setPassword('$2y$13$h.eZWrS5PJya7zNMNsKcXe8LUSVBtN2PBy8WHxmdHgAFjHG/rW.dG');
        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);

        print('ROLE_MANAGER' . PHP_EOL);
        $userManager = new User();
        $userManager->setEmail('manager@manager.com');
        // "manager" via "bin/console security:hash-password"
        $userManager->setPassword('$2y$13$3YxSfXMdyaKdplTEd07.SuDnbjAQZIAn8NLbhHzTLUnl1N7oegQg2');
        $userManager->setRoles(['ROLE_MANAGER']);

        $manager->persist($userManager);

        print('ROLE_ADMIN' . PHP_EOL);
        $admin = new User();
        $admin->setEmail('admin@admin.com');
        // "admin" via "bin/console security:hash-password"
        $admin->setPassword('$2y$13$L81zK/fTjQikyz3PtBmbL.WdDILXR.Ppn.whBAvLJsbaFu4Fu0zVe');
        $admin->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);


        


        //  ███╗   ███╗███████╗███████╗    ███████╗██╗██╗     ███╗   ███╗███████╗
        //  ████╗ ████║██╔════╝██╔════╝    ██╔════╝██║██║     ████╗ ████║██╔════╝
        //  ██╔████╔██║█████╗  ███████╗    █████╗  ██║██║     ██╔████╔██║███████╗
        //  ██║╚██╔╝██║██╔══╝  ╚════██║    ██╔══╝  ██║██║     ██║╚██╔╝██║╚════██║
        //  ██║ ╚═╝ ██║███████╗███████║    ██║     ██║███████╗██║ ╚═╝ ██║███████║
        //  ╚═╝     ╚═╝╚══════╝╚══════╝    ╚═╝     ╚═╝╚══════╝╚═╝     ╚═╝╚══════╝
        //                                                                       

        //      _______ __              
        //     / ____(_) /___ ___  _____
        //    / /_  / / / __ `__ \/ ___/
        //   / __/ / / / / / / / (__  ) 
        //  /_/   /_/_/_/ /_/ /_/____/  
        //                              
        //
        $mymovies = [
            1 => [
                'title' => 'Interstellar',
                'releaseDate' => '2014-11-05',
                'duration' => 169,
                'poster' => 'https://m.media-amazon.com/images/M/MV5BZjdkOTU3MDktN2IxOS00OGEyLWFmMjktY2FiMmZkNWIyODZiXkEyXkFqcGdeQXVyMTMxODk2OTU@._V1_SX300.jpg',
                //'genre' => ['Science fiction', 'Drame'],
            ],

            2 => [
                'title' => 'Le Loup de Wall Street',
                'releaseDate' => '2013-12-25',
                'duration' => 179,
                'poster' => 'https://fr.web.img4.acsta.net/r_1920_1080/img/37/4f/374f264b6bb0a2333d5747d274fbadc3.jpg',
                //'genre' => ['Biopic', 'Drame'],
            ],

            3 => [
                'title' => 'Forrest Gump',
                'releaseDate' => '2015-10-28',
                'duration' => 140,
                'poster' => 'https://m.media-amazon.com/images/M/MV5BNWIwODRlZTUtY2U3ZS00Yzg1LWJhNzYtMmZiYmEyNmU1NjMzXkEyXkFqcGdeQXVyMTQxNzMzNDI@._V1_SX300.jpg',
                //'genre' => ['Romance', 'Drame', 'Comédie'],
            ],

            4 => [
                'title' => 'Into the Wild',
                'releaseDate' => '2008-01-09',
                'duration' => 148,
                'poster' => 'https://m.media-amazon.com/images/M/MV5BMTAwNDEyODU1MjheQTJeQWpwZ15BbWU2MDc3NDQwNw@@._V1_SX300.jpg',
                //'genre' => ['Aventure', 'Drame', 'Biopic'],
            ],

            5 => [
                'title' => 'Seul sur Mars',
                'releaseDate' => '2015-10-21',
                'duration' => 144,
                'poster' => 'https://fr.web.img3.acsta.net/r_1920_1080/pictures/15/09/08/15/20/305329.jpg',
                //'genre' => ['Science fiction'],
            ],

            6 => [
                'title' => 'Arrête-moi si tu peux',
                'releaseDate' => '2003-02-12',
                'duration' => 141,
                'poster' => 'https://fr.web.img3.acsta.net/r_1920_1080/medias/nmedia/00/02/54/32/aff2.jpg',
                //'genre' => ['Drame', 'Thriller'],

            ],

            7 => [
                'title' => 'Shutter Island',
                'releaseDate' => '2010-02-24',
                'duration' => 137,
                'poster' => 'https://m.media-amazon.com/images/M/MV5BYzhiNDkyNzktNTZmYS00ZTBkLTk2MDAtM2U0YjU1MzgxZjgzXkEyXkFqcGdeQXVyMTMxODk2OTU@._V1_SX300.jpg',
                //'genre' => ['Science fiction'],
            ],
        ];

        for ($i = 1; $i < 8; $i++) {

            $movie = new Movie();
            $movie->setTitle($mymovies[$i]['title']);
            $sluggedTitle = $this->mySlugger->slugify($movie->getTitle());
            $movie->setSlug($sluggedTitle);
            $movie->setCreatedAt(new DateTime('now'));
            $movie->setReleaseDate(new DateTime($mymovies[$i]['releaseDate']));
            $movie->setDuration(intval($mymovies[$i]['duration']));
            $movie->setPoster($mymovies[$i]['poster']);

            $manager->persist($movie);
        }

        //      ____                                            
        //     / __ \___  ______________  ____  ____  ___  _____
        //    / /_/ / _ \/ ___/ ___/ __ \/ __ \/ __ \/ _ \/ ___/
        //   / ____/  __/ /  (__  ) /_/ / / / / / / /  __(__  ) 
        //  /_/    \___/_/  /____/\____/_/ /_/_/ /_/\___/____/  
        //                      
        //  
        $mypersons = [
            1 => [
                'firstname' => 'Matthew', 'lastname' => 'McConaughey',
                //$castings = [ ['movie_id' => '1', 'role' => 'Cooper', 'creditOrder' => 1, ],
                //              ['movie_id' => '2', 'role' => 'Mark Hanna', 'creditOrder' => 4]
                //]
            ],
            2 => [
                'firstname' => 'Anne', 'lastname' => 'Hathaway',
                //$castings = [ ['movie_id' => '1', 'role' => 'Brand', 'creditOrder' => 2, ],
                //]
            ],
            3 => [
                'firstname' => 'Michael', 'lastname' => 'Caine',
                //$castings = [ ['movie_id' => '1', 'role' => 'Professeur Brand', 'creditOrder' => 3, ],
                //]
            ],
            4 => [
                'firstname' => 'Leonardo', 'lastname' => 'DiCaprio',
                //$castings = [ ['movie_id' => '2', 'role' => 'Jordan Belfort', 'creditOrder' => 1, ],
                //              ['movie_id' => '6', 'role' => 'Franck Abagnale Jr.', 'creditOrder' => 1, ],
                //              ['movie_id' => '7', 'role' => 'Teddy Daniels', 'creditOrder' => 1, ],
                //]
            ],
            5 => [
                'firstname' => 'Jonah', 'lastname' => 'Hill',
                //$castings = [ ['movie_id' => '2', 'role' => 'Donnie Azoff', 'creditOrder' => 2, ],
                //]
            ],
            6 => [
                'firstname' => 'Margot', 'lastname' => 'Robbie',
                //$castings = [ ['movie_id' => '2', 'role' => 'Naomi Lapaglia', 'creditOrder' => 3, ],
                //]
            ],
            7 => [
                'firstname' => 'Kyle', 'lastname' => 'Chandler',
                //$castings = [ ['movie_id' => '2', 'role' => 'Patrick Denham', 'creditOrder' => 5, ],
                //]
            ],
            8 => [
                'firstname' => 'Tom', 'lastname' => 'Hanks',
                //$castings = [ ['movie_id' => '3', 'role' => 'Forrest Gump', 'creditOrder' => 1, ],
                //              ['movie_id' => '6', 'role' => 'Agent du FBI Carl Hanratty', 'creditOrder' => 2, ],                
                //]
            ],
            9 => [
                'firstname' => 'Gary', 'lastname' => 'Sinise',
                //$castings = [ ['movie_id' => '3', 'role' => 'Lieutenant Dan Taylor', 'creditOrder' => 2, ],
                //]
            ],
            10 => [
                'firstname' => 'Robin', 'lastname' => 'Wright',
                //$castings = [ ['movie_id' => '3', 'role' => 'Jennifer Curran', 'creditOrder' => 3, ],
                //]
            ],
            11 => [
                'firstname' => 'Emile', 'lastname' => 'Hirsch',
                //$castings = [ ['movie_id' => '4', 'role' => 'Christopher McCandless', 'creditOrder' => 1, ],
                //]
            ],
            12 => [
                'firstname' => 'Marcia', 'lastname' => 'Gay Harden',
                //$castings = [ ['movie_id' => '4', 'role' => 'Billie McCandless', 'creditOrder' => 2, ],
                //]
            ],
            13 => [
                'firstname' => 'William', 'lastname' => 'Hurt',
                //$castings = [ ['movie_id' => '4', 'role' => 'Walt McCandless', 'creditOrder' => 3, ],
                //]
            ],
            14 => [
                'firstname' => 'Jena', 'lastname' => 'Malone',
                //$castings = [ ['movie_id' => '4', 'role' => 'Carine McCandless', 'creditOrder' => 4, ],
                //]
            ],
            15 => [
                'firstname' => 'Brian', 'lastname' => 'Dierker',
                //$castings = [ ['movie_id' => '4', 'role' => 'Rainey', 'creditOrder' => 5, ],
                //]
            ],
            16 => [
                'firstname' => 'Matt', 'lastname' => 'Damon',
                //$castings = [ ['movie_id' => '5', 'role' => 'Mark Watney', 'creditOrder' => 1, ],
                //]
            ],
            17 => [
                'firstname' => 'Jessica', 'lastname' => 'Chastain',
                //$castings = [ ['movie_id' => '5', 'role' => 'Melissa Lewis', 'creditOrder' => 2, ],
                //]
            ],
            18 => [
                'firstname' => 'Kristen', 'lastname' => 'Wiig',
                //$castings = [ ['movie_id' => '5', 'role' => 'Annie Montrose', 'creditOrder' => 3, ],
                //]
            ],
            19 => [
                'firstname' => 'Jeff', 'lastname' => 'Daniels',
                //$castings = [ ['movie_id' => '5', 'role' => 'Teddy Sanders', 'creditOrder' => 4, ],
                //]
            ],
            20 => [
                'firstname' => 'Michael', 'lastname' => 'Peña',
                //$castings = [ ['movie_id' => '5', 'role' => 'Rick Martinez', 'creditOrder' => 5, ],
                //]
            ],
            21 => [
                'firstname' => 'Christopher', 'lastname' => 'Walken',
                //$castings = [ ['movie_id' => '6', 'role' => 'Franck Abagnale Sr.', 'creditOrder' => 3, ],
                //]
            ],
            22 => [
                'firstname' => 'Mark', 'lastname' => 'Ruffalo',
                //$castings = [ ['movie_id' => '7', 'role' => 'Marshal Chuck Aule', 'creditOrder' => 2, ],
                //]
            ],
            23 => [
                'firstname' => 'Ben', 'lastname' => 'Kingsley',
                //$castings = [ ['movie_id' => '7', 'role' => 'Cawley, psychiatre en chef', 'creditOrder' => 3, ],
                //]
            ],

        ];

        for ($i = 1; $i < 24; $i++ ) {

            $person = new Person();
            $person->setfirstname($mypersons[$i]['firstname']);
            $person->setlastname($mypersons[$i]['lastname']);

            $manager->persist($person);
        }    


        //     ______                         
        //    / ____/__  ____  ________  _____
        //   / / __/ _ \/ __ \/ ___/ _ \/ ___/
        //  / /_/ /  __/ / / / /  /  __(__  ) 
        //  \____/\___/_/ /_/_/   \___/____/  
        //                      
        //
        $mygenres = [
        /* 1 */    'Science fiction',
        /* 2 */    'Drame',
        /* 3 */    'Aventure',
        /* 4 */    'Biopic',
        /* 5 */    'Comédie',
        /* 6 */    'Romance',
        /* 7 */    'Thriller',
        /* 8 */    'Romance',
        ];
        
        foreach($mygenres as $name) {
        
            $genre = new Genre();
            $genre->setName($name);

            $manager->persist($genre);
        }


/*
        //  ███████╗ █████╗ ██╗  ██╗███████╗██████╗ 
        //  ██╔════╝██╔══██╗██║ ██╔╝██╔════╝██╔══██╗
        //  █████╗  ███████║█████╔╝ █████╗  ██████╔╝
        //  ██╔══╝  ██╔══██║██╔═██╗ ██╔══╝  ██╔══██╗
        //  ██║     ██║  ██║██║  ██╗███████╗██║  ██║
        //  ╚═╝     ╚═╝  ╚═╝╚═╝  ╚═╝╚══════╝╚═╝  ╚═╝

        
        $faker = \Faker\Factory::create('fr_FR');
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Movie($faker));
        $faker->addProvider(new \Xylis\FakerCinema\Provider\Character($faker));

            // Notre fournisseur de données
            //$movieDbProvider = new MovieDbProvider();

            // Si on veut toujours les mêmes données
            //$faker->seed('BABAR');

                
            //     ______                         
            //    / ____/__  ____  ________  _____
            //   / / __/ _ \/ __ \/ ___/ _ \/ ___/
            //  / /_/ /  __/ / / / /  /  __(__  ) 
            //  \____/\___/_/ /_/_/   \___/____/  
            //                      
            //
                                    
            print('FAKER GENRES' . PHP_EOL);
            $genre = Array();
            for ($i = 1; $i <= 20; $i++) {
                $genre[$i] = new Genre();
                $genre[$i]->setName($faker->unique()->movieGenre());

                $manager->persist($genre[$i]);                
            }
            


            //      _______ __              
            //     / ____(_) /___ ___  _____
            //    / /_  / / / __ `__ \/ ___/
            //   / __/ / / / / / / / (__  ) 
            //  /_/   /_/_/_/ /_/ /_/____/  
            //                              
            //
            
            
            print('FAKER MOVIES' . PHP_EOL);
            $movie = Array();
            for ($i = 1; $i <= 20; $i++) {
                $movie[$i] = new Movie();
                $movie[$i]->setTitle($faker->unique()->movie());

                // Pour le slug, on "slugify" avec notre service
                $sluggedTitle = $this->mySlugger->slugify($movie[$i]->getTitle());
                // On met à jour $movie
                $movie[$i]->setSlug($sluggedTitle);

                $movie[$i]->setReleaseDate($faker->dateTimeBetween('-80years', 'now'));
                $movie[$i]->setDuration($faker->numberBetween(15, 360));
                $movie[$i]->setPoster('images/star.png');
                //$movie[$i]->setPoster($faker->unique()->imageUrl(400, 600, 'city', true, 'Faker'));
                $movie[$i]->setRating($faker->numberBetween(1,5));


                // Association de genres
                for ($r = 1; $r <= mt_rand(1, 3); $r++) {
                    $movie[$i]->addGenre($genre[array_rand($genre)]);
                }

                $manager->persist($movie[$i]);
            }
            

            
            //      ____                                            
            //     / __ \___  ______________  ____  ____  ___  _____
            //    / /_/ / _ \/ ___/ ___/ __ \/ __ \/ __ \/ _ \/ ___/
            //   / ____/  __/ /  (__  ) /_/ / / / / / / /  __(__  ) 
            //  /_/    \___/_/  /____/\____/_/ /_/_/ /_/\___/____/  
            //                      
            //                                
            print('FAKER PERSONS' . PHP_EOL);
            $person = Array();
            for ($i = 1; $i <= 20; $i++) {
                $person[$i] = new Person();
                $person[$i]->setFirstname($faker->firstName());
                $person[$i]->setLastname($faker->lastName());


                $manager->persist($person[$i]);                
            }

             
            //     ______           __  _                 
            //    / ____/___ ______/ /_(_)___  ____ ______
            //   / /   / __ `/ ___/ __/ / __ \/ __ `/ ___/
            //  / /___/ /_/ (__  ) /_/ / / / / /_/ (__  ) 
            //  \____/\__,_/____/\__/_/_/ /_/\__, /____/  
            //                              /____/        
            //
             print('FAKER CASTINGS' . PHP_EOL);
            $casting = Array();
            for ($i = 1; $i < 100; $i++) {
                $casting[$i] = new Casting();
                $casting[$i]->setRole($faker->character());
                $casting[$i]->setCreditOrder(mt_rand(1, 10));

                // On va chercher un film au hasard dans la liste des films créée au-dessus
                // Variante avec mt_rand et count
                $randomMovie = $movie[mt_rand(1, count($movie))];
                $casting[$i]->setMovie($randomMovie);

                $randomPerson = $person[array_rand($person)];
                $casting[$i]->setPerson($randomPerson);

                $manager->persist($casting[$i]);

            }
*/

        $manager->flush();
    }
}
