<?php

// src/Form/ImageType.php

namespace App\Form;

use App\Entity\Image;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('imageFile', FileType::class, [
                'label' => 'Image',
                'required' => $options['required'],
                'mapped' => $options['mapped'],
                'attr' => ['accept' => 'image/*'],
                
            ]);
            
            
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Image::class, // Assurez-vous d'ajuster la classe d'entité
            'required' => false,
            'mapped' => true,
        ]);
    }
}
