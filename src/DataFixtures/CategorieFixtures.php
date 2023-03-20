<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = ["Dinner", "Accomodation", "Shower", "Ride", "Translation", "Other"];
        
        foreach ($categories as $key => $value){
            $categorie = new Categorie();
            $categorie->setName($value);
            $manager->persist($categorie);
        }
        $manager->flush();
    }
}
