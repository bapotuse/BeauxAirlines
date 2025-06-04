<?php

namespace App\Form;

use App\Entity\Aeroport;
use App\Entity\Hangar;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HangarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('capacite')
            ->add('aeroport', EntityType::class, [
                'class' => Aeroport::class,
                'choice_label' => function (Aeroport $aeroport) {
                    return $aeroport->getNom() . ' - ' . $aeroport->getVille();
                },
                'placeholder' => 'Sélectionnez un aéroport',
                'required' => false,
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Hangar::class,
        ]);
    }
}
