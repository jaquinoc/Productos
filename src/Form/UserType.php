<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'Email', 'attr' => ['placeholder' => 'Email']])
            ->add('password', PasswordType::class, ['label' => 'Contraseña', 'attr' => ['placeholder' => 'Password', 'maxlength' => 10, 'minlength' => 4]])
            ->add('name', TextType::class, ['label' => 'Nombre completo', 'attr' => ['placeholder' => 'Nombre completo', 'maxlength' => 100, 'minlength' => 4]])
            ->add('Registrar', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
