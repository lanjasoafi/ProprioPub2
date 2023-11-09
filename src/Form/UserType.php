<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class,[
            'attr'=>['class' => 'form-control mb-3'],
            'label_attr'=>['class'=>'text-light']
        ])
        ->add('name', TextType::class,[
            'attr'=>['class' => 'form-control mb-3'],
            'label_attr'=>['class'=>'text-light']
        ])
        ->add('firstname', TextType::class,[
            'attr'=>['class' => 'form-control mb-3'],
            'label_attr'=>['class'=>'text-light']
        ])
        ->add('phone', TelType::class,[
            'attr'=>['class' => 'form-control mb-3'],
            'label_attr'=>['class'=>'text-light']
        ])
        ->add('skype', EmailType::class,[
            'attr'=>['class' => 'form-control mb-3'],
            'label_attr'=>['class'=>'text-light']
        ])
        ->add('photo', FileType::class,[
            'label' => false,
            'multiple'=>false,
            'mapped'=>false,
            'required'=>false,
        ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
