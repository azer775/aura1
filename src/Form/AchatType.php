<?php

namespace App\Form;

use App\Entity\Achat;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AchatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbr_Piece', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('prix', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('facture')
            ->add('membre')
            ->add('produit')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Achat::class,
        ]);
    }
}
