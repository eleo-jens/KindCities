<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Host;
use App\Entity\Refugee;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++){
            $refugee = new Refugee(); 
            $refugee->setEmail($faker->email());
            $refugee->setGender($faker->randomElement(['woman', 'man', 'non-binary', 'other']));
            $refugee->setLastName($faker->lastName());
            $refugee->setFirstName($faker->firstName($gender = null|'male'|'female'));
            $refugee->setPhoneNumber($faker->phoneNumber());
            $refugee->setBirthDate($faker->dateTime());
            $refugee->setPassword($faker->password(8, 20));
            $refugee->setStatus($faker->randomElement($faker->sentence()));
            
            $manager->persist($refugee);
        }

        for ($i = 0; $i < 3; $i++){
            $host = new Host(); 
            $host->setEmail($faker->email());
            $host->setGender($faker->randomElement(['woman', 'man', 'non-binary', 'other']));
            $host->setLastName($faker->lastName());
            $host->setFirstName($faker->firstName($gender = null|'male'|'female'));
            $host->setPhoneNumber($faker->phoneNumber());
            $host->setBirthDate($faker->dateTime());
            $host->setPassword($faker->password(8, 20));
            $host->setNationalNumberId($faker->randomNumber(20, true));

            $manager->persist($host);
        }

        $manager->flush();
    }
}
