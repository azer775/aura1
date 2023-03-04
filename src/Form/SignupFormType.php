<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class SignupFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom', TextType::class, [
            'attr' => ['class' => 'form-control']
        ])
        ->add('prenom', TextType::class, [
            'attr' => ['class' => 'form-control']
        ])
        ->add('email', emailType::class, [
            'attr' => ['class' => 'form-control']
        ])
        ->add('password', PasswordType::class, [
            'attr' => ['class' => 'form-control']
        ])
        ->add('dateNais',DateType::class)
        ->add('tel', TextType::class, [
            'attr' => ['class' => 'form-control']
        ])
        ->add('adresse', TextType::class, [
            'attr' => ['class' => 'form-control']
        ])
        ->add('submit', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
