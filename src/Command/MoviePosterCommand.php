<?php

namespace App\Command;

use App\Service\OmdbApi;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Récupère les posters des films
 * Soit un film si titre demandé
 * Soit tous les films
 */
class MoviePosterCommand extends Command
{   
    // Nom de la commande. Bonne pratique, on la préfixe avec "app:"
    protected static $defaultName = 'app:movie:poster';
    // Description de la commande
    protected static $defaultDescription = 'Fetch movie posters from OMDB API';

    // Les services nécessaires à notre commande...
    private $movieRepository;
    private $entityManager;
    private $omdbApi;


    /**
     * ...qu'on récupère en injection de dépendances ici
     */
    public function __construct(MovieRepository $movieRepository, EntityManagerInterface $entityManager, OmdbApi $omdbApi)
    {
        $this->movieRepository = $movieRepository;
        $this->entityManager = $entityManager;
        $this->omdbApi = $omdbApi;

        // En PHP on doit appeler le constructeur de la classe parent
        // si on en a dans l'enfant et le parent également    
        parent :: __construct();
    }

    protected function configure(): void
    {
        $this
            // Message d'aide supplémentaire
            // Argument = valeur à transmettre à la commande
            ->addArgument('title', InputArgument::OPTIONAL, 'Movie title to fetch')
            // "Flag/Modifieur/Option" qui change le comportement de la commande
            ->addOption('dump', 'd', InputOption::VALUE_NONE, 'Dump title movies')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Permet des "fioritures graphiques" dans le terminal
        $io = new SymfonyStyle($input, $output);
        // On récupère l'argument "title" si présent
        $title = $input->getArgument('title');

        // Si un titre est présent, on ne traite que ce film
        if ($title) {
            $io->note(sprintf('Movie to fetch: %s', $title));
            $movie = $this->movieRepository->findOneBy(['title'=>$title]);
            // On colle $movie dans un tableau $movies (pour simplifier le foreach plus bas)
            $movies = [$movie];
        } else {
            // Sinon on traite tous les films
            $io->note(sprintf('Fetching all movies'));
            $movies = $this->movieRepository->findAll();
        }
        

        // La logique métier / L'objectif de la commande

        
        // On récupère les films concernés
        //dd($movies);

        // On boucle dessus, on va chercher les données associées sur OMDB API
        foreach ($movies as $movie){

            if ($input->getOption('dump')) {
                $io->info('Fetching : ' . $movie->getTitle());
            }

            // On envoie le titre de notre film à notre service OMDB API
            $moviePoster = $this->omdbApi->fectchPoster($movie->getTitle());

            if ($moviePoster === null) {
                $io->warning('Poster not found : '. $movie->getTitle());
            }

            // On met à jour l'URL du poster dans le film
            $movie->setPoster($moviePoster);
        }

        // On flush
        $this->entityManager->flush();
        

        $io->success('All movies fetched!');

        // Indique que la commande a fonctionné comme attendu
        return Command::SUCCESS;
    }
}