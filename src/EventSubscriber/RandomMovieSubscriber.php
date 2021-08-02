<?php

namespace App\EventSubscriber;

use Twig\Environment as Twig;
use App\Repository\MovieRepository;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RandomMovieSubscriber implements EventSubscriberInterface
{
    /**
     * On appelle le service MovieRepository
     */
    private $movieRepository;

    /**
     * Twig
     */
    private $twig;

    public function __construct(MovieRepository $movieRepository, Twig $twig)
    {
        $this->movieRepository = $movieRepository;
        $this->twig = $twig;
    }

    public function onKernelController(ControllerEvent $event)
    {
        // Récupérer le contrôleur
        $controller = $event->getController();

        if (is_array($controller)) {
            $controller = $controller[0];
        };

        // On récupère le nom de la classe de contrôleur
        $controllerClassName = (get_class($controller));

        // Notre écouteur ne s'exécute pas partout,
        // uniquement depuis nos contrôleurs

        //dump($controllerClassName);

        if (strpos($controllerClassName, 'App\Controller') === false) {
            // On sort du suscriber
            return;
        }

        dump("Subscriber appelé !");

        // 2. On va chercher un film au hasard
        // @todo Utiliser ORDER BY RAND() LIMIT 1
        // dans une requête custom dans le Respository

        // En attendant, on va faire un shuffle() sur tous les films
        $movies = $this->movieRepository->findAll();
        // On mélange, on prend le premier
        shuffle($movies);
        $randomMovie = $movies[0];
        // dump($randomMovie);

        // 3. On le transmet à Twig
        $this->twig->addGlobal('randomMovie', $randomMovie);

    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }
}
