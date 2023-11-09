<?php

// src/Form/PropertySearchType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PropertySearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyword', TextType::class, [
                'required' => false,
                'label' => 'Keyword',
            ])
            ->add('type', ChoiceType::class, [
                'required' => false,
                'label' => 'Type',
                'choices' => [
                    'All Type' => null,
                    'For Rent' => 'For Rent',
                    'For Sale' => 'For Sale',
                    'Open House' => 'Open House',
                ],
            ])
            ->add('city', ChoiceType::class, [
                'required' => false,
                'label' => 'City',
                'choices' => [
                    'All City' => null,
                    'Alabama' => 'Alabama',
                    'Arizona' => 'Arizona',
                    'California' => 'California',
                    'Colorado' => 'Colorado',
                ],
            ])
            ->add('bedrooms', ChoiceType::class, [
                'required' => false,
                'label' => 'Bedrooms',
                'choices' => [
                    'Any' => null,
                    '01' => '01',
                    '02' => '02',
                    '03' => '03',
                ],
            ])
            ->add('garages', ChoiceType::class, [
                'required' => false,
                'label' => 'Garages',
                'choices' => [
                    'Any' => null,
                    '01' => '01',
                    '02' => '02',
                    '03' => '03',
                    '04' => '04',
                ],
            ])
            ->add('bathrooms', ChoiceType::class, [
                'required' => false,
                'label' => 'Bathrooms',
                'choices' => [
                    'Any' => null,
                    '01' => '01',
                    '02' => '02',
                    '03' => '03',
                ],
            ])
            ->add('price', ChoiceType::class, [
                'required' => false,
                'label' => 'Min Price',
                'choices' => [
                    'Unlimite' => null,
                    '$50,000' => '$50,000',
                    '$100,000' => '$100,000',
                    '$150,000' => '$150,000',
                    '$200,000' => '$200,000',
                ],
            ])
            ->add('search', SubmitType::class, [
                'label' => 'Search Property',
                'attr' => ['class' => 'btn btn-b'],
            ]);
    }
}
