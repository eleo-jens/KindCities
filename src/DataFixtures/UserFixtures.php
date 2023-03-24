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
            $refugee->setFirstName($faker->firstName());
            $refugee->setPhoneNumber($faker->phoneNumber());
            $refugee->setBirthDate($faker->dateTime());
            $refugee->setPassword($faker->password(8, 20));
            $refugee->setStatus($faker->sentence());
            $refugee->setRoles(["ROLE_REFUGEE"]);
            
            $manager->persist($refugee);
        }
        
        for ($i = 0; $i < 3; $i++){
            $dateBirth = $faker->dateTime(); 
            $host = new Host(); 
            $host->setEmail($faker->email());
            $host->setGender($faker->randomElement(['woman', 'man', 'non-binary', 'other']));
            $host->setLastName($faker->lastName());
            $host->setFirstName($faker->firstName());
            $host->setPhoneNumber($faker->phoneNumber());
            $host->setBirthDate($dateBirth);
            $host->setPassword($faker->password(8, 20));
            $host->setNationalNumberId("'". $dateBirth->format('Y-m-d H:i:s') . $i ."'");
            $refugee->setRoles(["ROLE_HOST"]);
            
            $manager->persist($host);
        }

        $manager->flush();
    }
}
