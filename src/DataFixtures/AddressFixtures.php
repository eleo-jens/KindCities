<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Address;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AddressFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 6; $i++) {
            $address = new Address();
            $address->setStreet($faker->streetName());
            $address->setNumber($faker->buildingNumber());
            $address->setBox($faker->buildingNumber());
            $address->setCity($faker->city());
            $address->setState($faker->city());
            $address->setPostalCode($faker->postcode());
            $address->setCountry($faker->country());
            $manager->persist($address);
        }
        $manager->flush();
    }
}
