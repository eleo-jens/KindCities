<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Host;
use App\Entity\Address;
use App\Entity\Service;
use App\Entity\Disponibilite;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\AddressFixtures;
use App\DataFixtures\ServiceFixtures;
use App\DataFixtures\CategorieFixtures;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\HostAddressFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AddressServiceDisponibiliteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        
        $addresses = $manager->getRepository(Address::class)->findAll();
        $services = $manager->getRepository(Service::class)->findAll();

        foreach ($services as $key => $service){

            $addressRandom = $addresses[array_rand($addresses)];
            $service->setAddress($addressRandom);
            $manager->persist($service);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return ([
            UserFixtures::class,
            AddressFixtures::class,
            HostAddressFixtures::class,
            CategorieFixtures::class,
            ServiceFixtures::class,
        ]);
    }
}
