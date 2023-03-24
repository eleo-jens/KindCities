<?php

namespace App\DataFixtures;

use App\Entity\Language;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class LanguageFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $languages = ["Français", "Nederlands", "English", "Hindi", "Urdu"];
        
        foreach ($languages as $key => $value){
            $language = new Language();
            $language->setName($value);
            $manager->persist($language);
        }
        $manager->flush();
    }
}
