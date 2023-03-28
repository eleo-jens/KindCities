- Ajouter un service 
- Un service à une seule adresses
- Créer un formulaire pour le service (servicetype) où on peut choisir une adresse parmis les adresses existantes de l'host connecté
- On veut en plus pouvoir ajouter/créer une nouvelle adresse

- idem pour les disponibilités

1. Créer un formulaire pour le service (servicetype) 
2. Drop down uniquement des adresses existantes de l'host connecté

```php
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
                'choice_label' => 'street'
            ]);
    }
```

