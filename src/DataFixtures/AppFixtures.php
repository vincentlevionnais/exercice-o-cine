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
                $movie[$i]->setPoster('images/default-movie.jpg');
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


        $manager->flush();
    }
}
