<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Language;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\LanguageFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Validator\Constraints\Length;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LanguageHostFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $languages = $manager->getRepository(Language::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();
        $nbLangue = random_int(1, count($languages));
        
        foreach($users as $key => $user){
            for($i = 1; $i <= $nbLangue; $i++){
                $languageRandom = $languages[array_rand($languages)];
                $user->addLanguage($languageRandom);
            }
            $manager->persist($user);
        }
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
