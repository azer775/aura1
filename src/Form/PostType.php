<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('theme', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('image', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('contenu', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
         //   ->add('nbr_Vue')
            ->add('date_Creation', DateType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('Membre')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
