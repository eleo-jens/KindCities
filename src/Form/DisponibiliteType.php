<?php

namespace App\Form;

use App\Entity\Host;
use App\Entity\Disponibilite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class DisponibiliteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('beginDateDispo', DateType::class, [
                'widget' => 'single_text'
            ])
            ->add('endDateDispo', DateType::class, [
                'widget' => 'single_text', 
                // 'html5' => false, 
                // 'attr' => ['class' => 'js-datepicker']
            ])

            
            // ->add('host') 
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Disponibilite::class,
        ]);
    }
}
