<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\AtLeastOneOf;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class,
                [
                    'choices' => [
                        'User' => 'ROLE_USER',
                        'Staff' => 'ROLE_STAFF',
                        'Admin' => 'ROLE_ADMIN'
                    ],
                    'help' => 'Sélectionnez un rôle',
                    'expanded' => true,
                    'multiple' => false
                ])
            ->add('password', PasswordType::class, 
            [
                'mapped' => false,
                'constraints' => [
                    new AtLeastOneOf([$Blank])
                ]
            ])
            ->add('save', SubmitType::class, ['label' => "Créer un nouvel utilisateur"]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
