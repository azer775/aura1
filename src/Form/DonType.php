<?php

namespace App\Form;

use App\Entity\Don;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class DonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('date_Don')
          //  ->add('id_Membre')
          //  ->add('id_Assoc')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Don::class,
        ]);
    }
}
