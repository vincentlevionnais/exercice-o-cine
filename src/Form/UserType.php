<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                    // @link https://symfony.com/doc/current/reference/forms/types/choice.html#choices
                    'placeholder' => '-- choisissez --',
                    'choices' => [
                        // Label => valeur
                        'Admin' => 'ROLE_ADMIN',
                        'User' => 'ROLE_USER',
                    ],
                        
                    // Pas nécessaire ici car valeurs par défaut pour un SELECT,
                    // maos pour info :
                    // Plusieurs choix possibles ou non
                    'multiple' => false,
                    // Chaque choix à son widget HTML ou non
                    'expanded' => false,
                ])
            ->add('password')
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
