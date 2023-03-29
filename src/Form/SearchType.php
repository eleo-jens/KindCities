<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Request\SearchRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'expanded' => true,
                'required' => false
            ])
            ->add('from', DateType::class, [
                'widget' => 'single_text', 
                ])
            ->add('to', DateType::class, [
                'widget' => 'single_text', 
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SearchRequest::class,
        ]);
    }
}
