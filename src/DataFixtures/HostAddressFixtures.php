<?php

namespace App\DataFixtures;

use App\Entity\Host;
use App\Entity\Address;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\Mapping\Id;

class HostAddressFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        $addresses = $manager->getRepository(Address::class)->findAll();
        $hosts = $manager->getRepository(Host::class)->findAll();

        // création de HostAddress
        foreach ($hosts as $key => $host){
            dd($hosts); 
            $addressRandom = $addresses[array_rand($addresses)];
            $host->addAddress($addressRandom);
            $manager->persist($host);
        }

        $manager->flush();
    }
}
