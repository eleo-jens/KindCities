<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Host;
use App\Entity\Refugee;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
         $this->passwordHasher = $passwordHasher;
    }
    
    // attention: probleme de hashage qui ne se fait pas ...
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++){
            $firstname = $faker->firstName();
            $lastname = $faker->lastName();

            $refugee = new Refugee(); 
            // $refugee->setEmail("" . $firstname . "." . $lastname . "@gmail.com");
            $refugee->setEmail("user" . $i . "@gmail.com");
            $refugee->setGender($faker->randomElement(['woman', 'man', 'non-binary', 'other']));
            $refugee->setLastName($lastname);
            $refugee->setFirstName($firstname);
            $refugee->setPhoneNumber($faker->phoneNumber());
            $refugee->setBirthDate($faker->dateTime());
            // $refugee->setPassword("test1234");
            $refugee->setPassword($this->passwordHasher->hashPassword(
                $refugee,
                'test1234'
            ));
            $refugee->setStatus($faker->sentence());
            $refugee->setRoles(["ROLE_REFUGEE"]);
            
            $manager->persist($refugee);
        }
        
        for ($i = 0; $i < 3; $i++){
            $firstname = $faker->firstName();
            $lastname = $faker->lastName();
            $dateBirth = $faker->dateTime(); 

            $host = new Host(); 
            $host->setEmail("" . $firstname . "." . $lastname . "@gmail.com");
            $host->setGender($faker->randomElement(['woman', 'man', 'non-binary', 'other']));
            $host->setLastName($lastname);
            $host->setFirstName($firstname);
            $host->setPhoneNumber($faker->phoneNumber());
            $host->setBirthDate($dateBirth);
            $host->setPassword("test1234");
            $host->setPassword($this->passwordHasher->hashPassword(
                $host,
                'test1234'
            ));
            $host->setNationalNumberId("'". $dateBirth->format('Y-m-d H:i:s') . $i ."'");
            $host->setRoles(["ROLE_HOST"]);
            
            $manager->persist($host);
        }

        $manager->flush();
    }
}
