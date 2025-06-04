<?php

namespace App\Form;

use App\Entity\Aeroport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AeroportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, [
                'attr' => ['class' => 'px-2 py-2']
            ])
            ->add('pays', null, [
                'attr' => ['class' => 'px-2 py-2']
            ])
            ->add('ville', null, [
                'attr' => ['class' => 'px-2 py-2']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Aeroport::class,
        ]);
    }
}
