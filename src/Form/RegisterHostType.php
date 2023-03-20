<?php

namespace App\Form;

use App\Entity\Host;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegisterHostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class)
        ->add('lastName')
        ->add('firstName')
        ->add('phoneNumber', TelType::class)
        ->add('birthDate', DateType::class)
        ->add('gender', 
        ChoiceType::class,
            [
                'choices' => [
                    'Woman' => 'woman',
                    'Man' => 'man',
                    'Non-binary' => 'non-binary',
                    'Other' => 'other'
                ],
                'expanded' => true,
                'multiple' => false,
                'mapped' => false
            ],)
        ->add('plainPassword', PasswordType::class, [
            // instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
            'attr' => ['autocomplete' => 'new-password'],
            'constraints' => [
                new NotBlank([
                    'message' => 'Please enter a password',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Your password should be at least {{ limit }} characters',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
        ])
        // ->add('agreeTerms', CheckboxType::class, [
        //     'mapped' => false,
        //     'constraints' => [
        //         new IsTrue([
        //             'message' => 'You should agree to our terms.',
        //         ]),
        //     ],
        // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Host::class,
        ]);
    }
}
