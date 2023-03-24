<?php

namespace App\DataFixtures;

use App\DataFixtures\UserFixtures;
use App\DataFixtures\LanguageFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LanguageHostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        

        $manager->flush();
    }

    public function getDependencies()
    {
        return ([
            LanguageFixtures::class,
            UserFixtures::class
        ]); 
    }
}
