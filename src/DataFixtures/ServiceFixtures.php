<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Service;
use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ServiceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $services = [
            "Notre chambre d'amis à Schaerbeek" => 2, "Repas végétarien dans notre appartement à Roodbeek" => 1, "Prenez une douche chez nous" => 3, 
            "Une chambre d'accueil dnas notre appartement à Uccle" => 2, "Un dîner en famille à Etterbeek" => 1, "Un repas halal/végétarien à Saint-Gilles" => 1, "Un studio libre à Bruxelles-Centrale" => 2, "Service de traduction Urdu-Français" => 5
        ];

        foreach ($services as $name => $idCategorie){
            $service = new Service(); 
            $service->setName($name);
            $service->setCategorie($manager->getRepository(Categorie::class)->find($idCategorie)); 
            $service->setDescription($faker->text());  
            $manager->persist($service);
        }

        $manager->flush();
    }
}
