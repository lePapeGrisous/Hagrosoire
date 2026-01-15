<?php

namespace App\Form;

use App\Entity\HydroliqueSum;
use App\Entity\Meteo;
use App\Entity\Sensor;
use App\Entity\Zone;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ZoneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('space_type')
            ->add('ru')
            ->add('surface')
            ->add('uniformity')
            ->add('hydroliqueSum', EntityType::class, [
                'class' => HydroliqueSum::class,
                'choice_label' => 'id',
            ])
            ->add('sensor', EntityType::class, [
                'class' => Sensor::class,
                'choice_label' => 'id',
            ])
            ->add('meteo', EntityType::class, [
                'class' => Meteo::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Zone::class,
        ]);
    }
}
