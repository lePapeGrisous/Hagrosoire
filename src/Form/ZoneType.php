<?php

namespace App\Form;

use App\Entity\HydroliqueSum;
use App\Entity\Meteo;
use App\Entity\Sensor;
use App\Entity\Zone;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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

    public const SPACE_TYPE_DATA = [
        'gazon_ornemental' => ['kc' => 0.85, 'ru' => 25, 'seuil_bas' => 10, 'seuil_haut' => 21],
        'pelouse_sportive' => ['kc' => 0.95, 'ru' => 45, 'seuil_bas' => 18, 'seuil_haut' => 38],
        'massif_fleurs_annuelles' => ['kc' => 1.00, 'ru' => 40, 'seuil_bas' => 16, 'seuil_haut' => 34],
        'massif_vivaces' => ['kc' => 0.75, 'ru' => 70, 'seuil_bas' => 28, 'seuil_haut' => 60],
        'massif_arbustif' => ['kc' => 0.65, 'ru' => 90, 'seuil_bas' => 36, 'seuil_haut' => 77],
        'haie_persistante' => ['kc' => 0.60, 'ru' => 110, 'seuil_bas' => 44, 'seuil_haut' => 94],
        'prairie_fleurie' => ['kc' => 0.55, 'ru' => 80, 'seuil_bas' => 32, 'seuil_haut' => 68],
        'arbres_alignement' => ['kc' => 0.70, 'ru' => 160, 'seuil_bas' => 64, 'seuil_haut' => 136],
    ];

    public const IRRIGATION_TYPES = [
        'Goutte-à-goutte enterré' => 'goutte_a_goutte',
        'Aspersion fixe bien réglée' => 'aspersion_fixe',
        'Aspersion rotative' => 'aspersion_rotative',
    ];

    public const IRRIGATION_TYPE_DATA = [
        'goutte_a_goutte' => 0.95,
        'aspersion_fixe' => 0.825,
        'aspersion_rotative' => 0.775,
    ];

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('space_type', ChoiceType::class, [
                'choices' => self::SPACE_TYPES,
                'placeholder' => 'Sélectionnez un type d\'espace',
            ])
            ->add('kc', HiddenType::class)
            ->add('ru', HiddenType::class)
            ->add('seuil_bas', HiddenType::class)
            ->add('seuil_haut', HiddenType::class)
            ->add('surface')
            ->add('irrigation_type', ChoiceType::class, [
                'choices' => self::IRRIGATION_TYPES,
                'placeholder' => 'Sélectionnez un type d\'arrosage',
                'mapped' => false,
            ])
            ->add('uniformity', HiddenType::class)
            ->add('sensor', EntityType::class, [
                'class' => Sensor::class,
                'choice_label' => 'name',
            ])
            ->add('lat', NumberType::class, [
                'label' => 'Latitude',
                'required' => false,
                'attr' => [
                    'id' => 'zone_lat',
                    'step' => 'any',
                    'readonly' => true,
                ],
            ])
            ->add('long', NumberType::class, [
                'label' => 'Longitude',
                'required' => false,
                'attr' => [
                    'id' => 'zone_long',
                    'step' => 'any',
                    'readonly' => true,
                ],
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();

            if (isset($data['space_type']) && isset(self::SPACE_TYPE_DATA[$data['space_type']])) {
                $spaceTypeData = self::SPACE_TYPE_DATA[$data['space_type']];
                $data['kc'] = $spaceTypeData['kc'];
                $data['ru'] = $spaceTypeData['ru'];
                $data['seuil_bas'] = $spaceTypeData['seuil_bas'];
                $data['seuil_haut'] = $spaceTypeData['seuil_haut'];
            }

            if (isset($data['irrigation_type']) && isset(self::IRRIGATION_TYPE_DATA[$data['irrigation_type']])) {
                $data['uniformity'] = self::IRRIGATION_TYPE_DATA[$data['irrigation_type']];
            }

            $event->setData($data);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Zone::class,
        ]);
    }
}
