<?php

namespace App\Form;

use App\Entity\JournalEntry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JournalEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Define the screen time fields.
        $fields = [
            'tvScreen' => 'Télévision',
            'pcScreen' => 'Ordinateur',
            'phoneScreen' => 'Téléphone',
            'tabletScreen' => 'Tablette',
            'consoleScreen' => 'Console de jeux',
            'otherScreen' => 'Autres écrans',
        ];

        // Add the screen time fields.
        foreach ($fields as $name => $label) {
            $builder->add($name, IntegerType::class, [
                'label' => $label,
                'attr' => [
                    'min' => JournalEntry::SCREEN_TIME_MIN,
                    'max' => JournalEntry::SCREEN_TIME_MAX,
                    'step' => 1,
                ],
            ]);
        }

        // Add the pain zones subform.
        $builder->add('painZones', PainZonesType::class, [
            'mapped' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JournalEntry::class,
        ]);
    }
}
