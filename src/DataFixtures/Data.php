<?php
/*
$movies = array(
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


$role = [
    "Cooper",
    "Brand",
    "Professeur Brand",
];

$firstname = [
    "Matthew",
    "Anne",
    "Michael",
];

$lastname = [
    "McConaughey",
    "Hathaway",
    "Caine",
];





            $movie = new Movie();
            $movie->setTitle('Film #' . $i);
            $movie->setCreatedAt(new DateTime());
            $movie->setReleaseDate(new DateTime());
            $movie->setDuration(new DateTime());
            $movie->setPoster('url #' . $i);
            $manager->persist($movie);
        }

         // create 10 genres! Bam!
         for ($i = 1; $i <= 10; $i++) {
            $genre = new Genre();
            $genre->setName('Genre #' . $i);
            $manager->persist($genre);
        }

        // create 10 genres! Bam!
        for ($i = 1; $i <= 30; $i++) {
            $person = new Person();
            $person->setFirstname('Firstname #' . $i);
            $person->setLastname('Lastname #' . $i);
            $manager->persist($person);

        }
*/