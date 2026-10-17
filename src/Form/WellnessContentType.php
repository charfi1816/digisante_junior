<?php

namespace App\Form;

use App\Entity\WellnessContent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class WellnessContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', null, [
                'label' => 'Type',
            ])
            ->add('content', null, [
                'label' => 'Contenu',
            ])
            ->add('title', null, [
                'label' => 'Titre',
            ])
            ->add('createdAt', null, [
                'widget' => 'single_text',
                'label' => 'Date de création',
            ])
            ->add('url', null, [
                'label' => 'URL',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => WellnessContent::class,
        ]);
    }
}
