<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Pseudo',
                'attr' => ['placeholder' => 'ex : Toto']
            ])

            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'attr' => ['placeholder' => 'toto@toto.com']
            ])

            ->add('content',TextareaType::class, [
                'label' => 'Critique',
                'attr' => ['placeholder' => 'Votre texte...']
            ])

            ->add('rating', ChoiceType::class, [
                'label' => 'Appréciation',
                // @link https://symfony.com/doc/current/reference/forms/types/choice.html#choices
                'placeholder' => '-- choisissez --',
                'choices' => [
                    // Label => valeur
                    'Excellent' => 5,
                    'Très bon' => 4,
                    'Bon' => 3,
                    'Peut mieux faire' => 2,
                    'A éviter' => 1],
                // Pas nécessaire ici car valeurs par défaut pour un SELECT,
                // maos pour info :
                // Plusieurs choix possibles ou non
                'multiple' => false,
                // Chaque choix à son widget HTML ou non
                'expanded' => false,
            ])

            ->add('reactions', ChoiceType::class, [
                'label' => 'Ce film nous a fait',
                'choices' => [
                    // Label => ce qu'on va stocker en base
                    'Rire' => 'smile',
                    'Pleurer' => 'cry',
                    'Réfléchir' => 'think',
                    'Dormir' => 'sleep',
                    'Rêver' => 'dream',
                ],
                // Plusieurs réactions possibles
                'multiple' => true,
                // Une checkbox pour chaque
                'expanded' => true,
            ])

            ->add('watchedAt', DateType::class, [
                'label' => 'Vous avez vu ce film le :',
                'input' => 'datetime_immutable',  
                'years' => range( date('Y'), date('Y') - 10 ),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
        ]);
    }
}
