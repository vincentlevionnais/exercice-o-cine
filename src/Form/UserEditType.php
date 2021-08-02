<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints\NotCompromisedPassword;
use Symfony\Component\Validator\Constraints\Regex;

class UserEditType extends AbstractType
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
                    // Si besoin de remplacer un "null" par une valeur, on peut utiliser
                    // @link https://symfony.com/doc/current/reference/forms/types/password.html#empty-data
                    // 'empty_data' => '',
                    'mapped' => false,
                    //'required' => true,
                    'first_options'  => [
                        'constraints' => [
                            new Regex("/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&-\/])[A-Za-z\d@$!%*#?&-\/]{8,}$/"),
                            new NotCompromisedPassword(),
                    ],

                        'attr' => [ 'placeholder' => 'Laisser vide si inchangé...'],

                        'label' => 'Mot de passe',
                        'help' => '8 caractères minimum, au moins une lettre, un chiffre et un caractère spécial.',
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
