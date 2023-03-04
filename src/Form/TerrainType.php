<?php

namespace App\Form;

use App\Entity\Terrain;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
class TerrainType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('surface'/*, TextType::class, [
                'attr' => ['class' => 'form-control']
            ]*/)
            ->add('adresse', TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('potentiel'/*, TextType::class, [
                'attr' => ['class' => 'form-control']
            ]*/)
            ->add('Membre')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Terrain::class,
        ]);
    }
}
