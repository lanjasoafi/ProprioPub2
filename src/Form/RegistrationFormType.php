<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
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
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'attr' => ['class'=>'form-check-input'],
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                
                 
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password',
                            'class' => 'form-control mb-3'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'le mot de passe doit etre minimum {{ limit }} caracteres',
                        // max length allowed by Symfony for security reasons
                        'max' => 20,
                        'maxMessage' => 'le mot de passe doit etre maximum {{ limit }} caracteres',
                        
                    ]),
                ],
                'label_attr'=>['class'=>'text-light'],
            ])
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
