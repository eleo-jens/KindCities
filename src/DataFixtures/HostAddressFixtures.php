<?php

namespace App\DataFixtures;

use App\Entity\Host;
use App\Entity\Address;
use Doctrine\ORM\Mapping\Id;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\AddressFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class HostAddressFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $addresses = $manager->getRepository(Address::class)->findAll();
        $hosts = $manager->getRepository(Host::class)->findAll();

        // dump($addresses);
        dd($hosts); 
        
        // création de HostAddress
        foreach ($hosts as $key => $host){
            $addressRandom = $addresses[array_rand($addresses)];
            $host->addAddress($addressRandom);
            $manager->persist($host);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return ([
            UserFixtures::class,
            AddressFixtures::class
        ]);
    }
}
