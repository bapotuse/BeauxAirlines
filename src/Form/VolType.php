<?php

namespace App\Form;

use App\Entity\Aeroport;
use App\Entity\Avion;
use App\Entity\Pilote;
use App\Entity\Vol;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateHeureDepart', null,  [
                'label' => 'Date & heure de départ',
            ])
            ->add('dateHeureArrivee', null,  [
                'label' => 'Date & heure d\'arrivée',
            ])
            ->add('avion', EntityType::class, [
                'class' => Avion::class,
                'choice_label' => 'modele',
            ])
            ->add('aeroportDepart', EntityType::class, [
                'class' => Aeroport::class,
                'label' => 'Aéroport de départ',
                'choice_label' => 'nom',
            ])
            ->add('aeroportArrivee', EntityType::class, [
                'class' => Aeroport::class,
                'label' => 'Aéroport d\'arrivée',
                'choice_label' => 'nom',
            ])
            ->add('pilote', EntityType::class, [
                'class' => Pilote::class,
                'choice_label' => 'matricule',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vol::class,
        ]);
    }
}
