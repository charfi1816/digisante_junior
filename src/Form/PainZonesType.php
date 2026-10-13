<?php

namespace App\Form;

use App\Entity\PainZone;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class PainZonesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Define pain intensity choices.
        $intensities = [
            'Aucune douleur' => 0,
            '1 — Très faible' => 1,
            '2 — Faible' => 2,
            '3 — Moyenne' => 3,
            '4 — Forte' => 4,
            '5 — Très forte' => 5,
        ];

        // Add a field for each body zone.
        foreach (PainZone::ZONES as $zone => $details) {
            $builder->add($zone, ChoiceType::class, [
                'label' => $details['name'],
                'choices' => $intensities,
                'placeholder' => false,
            ]);
        }
    }
}
