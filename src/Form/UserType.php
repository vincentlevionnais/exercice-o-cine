<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('roles', ChoiceType::class, [
                    'choices' => [
                        // Label => valeur
                        'User' => "ROLE_USER",
                        'Manager' => "ROLE_MANAGER",
                        'Administrateur' => "ROLE_ADMIN",
                    ],
                    // Plusieurs choix possibles ou non
                    'multiple' => true,
                    // Chaque choix à son widget HTML ou non
                    'expanded' => true,
                ])
                ->add('password', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'invalid_message' => 'Les mots de passe ne correspondent pas.',
                    'required' => true,
                    'first_options'  => [
                        'label' => 'Mot de passe',
                        'help' => 'Minimum eight characters, at least one letter, one number and one special character.'
                    ],
                    'second_options' => ['label' => 'Répéter le mot de passe'],
                ])
        ;
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
