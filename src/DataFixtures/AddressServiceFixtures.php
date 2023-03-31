<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\DataFixtures\AddressFixtures;
use App\DataFixtures\ServiceFixtures;
use App\Entity\Service;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AddressServiceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $addresses = $manager->getRepository(Address::class)->findAll();
        $services = $manager->getRepository(Service::class)->findAll();

        foreach ($services as $key => $service){
            $addressRandom = $addresses[array_rand($addresses)];
            $service->addHost($addressRandom);
            $manager->persist($service);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return ([
            ServiceFixtures::class,
            AddressFixtures::class
        ]);
    }
}
