<?php

namespace App\Form;

use DateTime;
use App\Entity\Genre;
use App\Entity\Movie;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Avec make:form, le form est génété
        // selon *toutes* les propriétés de l'entité
        // Question : que veut-on exposer à l'utilisateur
        // pour ce formulaire back ?
        // => on supprime ce qui n'est pas utile/souhaitable
        $builder
            ->add('title')
            // Ici, pas besoin car géré automatiquement par notre code
            // ->add('createdAt')
            // ->add('updatedAt')
            ->add('releaseDate', DateType::class, [
                'years' => range( date('Y') - 100, date('Y') + 5 ),
                // Date actuelle par défaut
                //'data' => new DateTime(),
                // Se besoin d'initialiser la date sur une date précise
                // 'data' => new DateTime('1986-04-12'),
            ])
            ->add('duration')
            ->add('poster', UrlType::class)
            // Cette note sera calculée via les Reviews front
            // on ne la manipule pas manuellement
            // ->add('rating')
            // @link https://symfony.com/doc/current/reference/forms/types/entity.html
            ->add('genres', EntityType::class, [
                'class' => Genre::class,
                // On a plusieurs choix possibles (ArrayCollection)
                'multiple' => true,
                // Le libellé de l'option ! (ou de la checkbox etc.)
                'choice_label' => 'name',
                // Un élément HTML par choix
                'expanded' => true,
                // Cette option permet d'écrire une requête custom
                // en lui transmettant le Repository de l'entité concernée
                // et en retournant un objet QueryBuilder construit pour notre besoin
                // ici : SELECT * FROM genre ORDER BY ASC                
                'query_builder' => function (EntityRepository $gr) {
                    return $gr->createQueryBuilder('g')
                        ->orderBy('g.name', 'ASC');
                },
           ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
