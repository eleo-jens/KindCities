<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        $c1 = new Categorie("Dinner");
        $c2 = new Categorie("Accomodation");
        $c3 = new Categorie("Shower");
        $c4 = new Categorie("Ride");
        $c5 = new Categorie("Translation");
        $c6 = new Categorie("Other");

        $manager->persist($c1);
        $manager->persist($c2);
        $manager->persist($c3);
        $manager->persist($c4);
        $manager->persist($c5);
        $manager->persist($c6);

        $manager->flush();
    }
}
