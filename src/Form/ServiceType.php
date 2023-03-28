<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Service;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ServiceType extends AbstractType
{
    private $token; 
    public function __construct(TokenStorageInterface $token)
    {
       $this->token = $token;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $user = $this->token->getToken()->getUser();

        $builder
            ->add('name')
            ->add('description', TextareaType::class)
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name'
            ])
            // drop down menu avec les adresses du host
            ->add('address', EntityType::class, [
                'class' => Address::class,
                'choices' => $user->getAddresses(),
                'choice_label' => function ($adresse){
                    return $adresse->getNumber(). " " .$adresse->getStreet() . ", boite " . $adresse->getBox() . ", " . $adresse->getCity() . ", " . $adresse->getState() . ", " . $adresse->getPostalCode() . ", " . $adresse->getCountry();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Service::class,
        ]);
    }
}
