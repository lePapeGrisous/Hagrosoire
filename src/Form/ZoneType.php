<?php

namespace App\Form;

use App\Entity\HydroliqueSum;
use App\Entity\Meteo;
use App\Entity\Sensor;
use App\Entity\Zone;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ZoneType extends AbstractType
{
    public const SPACE_TYPES = [
        'Gazon ornemental' => 'gazon_ornemental',
        'Pelouse sportive' => 'pelouse_sportive',
        'Massif de fleurs annuelles' => 'massif_fleurs_annuelles',
        'Massif de vivaces' => 'massif_vivaces',
        'Massif arbustif' => 'massif_arbustif',
        'Haie persistante' => 'haie_persistante',
        'Prairie fleurie' => 'prairie_fleurie',
        'Arbres d\'alignement' => 'arbres_alignement',
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('space_type', ChoiceType::class, [
                'choices' => self::SPACE_TYPES,
                'placeholder' => 'Sélectionnez un type d\'espace',
                'attr' => [
                    'data-zone-form-target' => 'spaceType',
                ],
            ])
            ->add('kc', NumberType::class, [
                'attr' => [
                    'data-zone-form-target' => 'kc',
                    'step' => '0.01',
                    'readonly' => true,
                ],
            ])
            ->add('ru', NumberType::class, [
                'attr' => [
                    'data-zone-form-target' => 'ru',
                    'readonly' => true,
                ],
            ])
            ->add('seuil_bas', NumberType::class, [
                'attr' => [
                    'data-zone-form-target' => 'seuilBas',
                    'readonly' => true,
                ],
            ])
            ->add('seuil_haut', NumberType::class, [
                'attr' => [
                    'data-zone-form-target' => 'seuilHaut',
                    'readonly' => true,
                ],
            ])
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
