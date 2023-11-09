<?php

namespace App\Form;

use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AnnoceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'immobilier',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description de l\'immobilier',
                'attr' => [
                    'rows' => 5, // Définissez le nombre de lignes souhaité
                    'class' => 'custom-textarea', // Ajoutez une classe CSS personnalisée
                    'style' => 'font-family: Arial, sans-serif;', // Personnalisez la police
                ],
            ])
            
            ->add('adress', TextType::class, [
                'label' => 'Localisation de l\'immobilier',
            ])
            // Add a hidden field for userId
            ->add('userId', HiddenType::class, [
                'mapped' => false, // This value will not come from the entity
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type d\'immobilier',
                'choices' => [
                    'Maisons Individuelles' => 'Maisons Individuelles',
                    'Condominiums et Appartements' => 'Condominiums et Appartements',
                    'Propriétés Résidentielles' => 'Propriétés Résidentielles',
                    'Maisons de Ville' => 'Maisons de Ville',
                    'Logements Sociaux' => 'Logements Sociaux',
                    'Logements Étudiants' => 'Logements Étudiants',
                    'Logements de Vacances' => 'Logements de Vacances',
                    'Co-living' => 'Co-living',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Status de l\'annonce',
                'choices' => [
                    'A louer' => 'A louer',
                    'A vendre' => 'A vendre',
                ],
            ])
            ->add('surface', NumberType::class, [
                'label' => 'Superficier de l\'immobilier',
            ])
            ->add('chambre', IntegerType::class, [
                'label' => 'Nombre de chambre',
            ])
            ->add('bain', IntegerType::class, [
                'label' => 'Nombre de salle de bain',
            ])
            ->add('garage', IntegerType::class, [
                'label' => 'Nombre de garage',
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix de l\'immobilier',
            ])
            ->add('images', FileType::class, [
                'label' => 'Ajouter des images',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
            ]);

        // ->add('save', SubmitType::class, ['label' => 'Publier']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
