<?php

namespace App\DataFixtures;

use App\Entity\Host;
use App\Entity\User;
use App\Entity\Address;
use Doctrine\ORM\Mapping\Id;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\AddressFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class HostAddressFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $addresses = $manager->getRepository(Address::class)->findAll();
        $hosts = $manager->getRepository(Host::class)->findAll();
        $users = $manager->getRepository(User::class)->findAll();

        // dump($addresses);
        // dump($users); 
        // dd($hosts); 
        
        // création de HostAddress
        // foreach ($hosts as $key => $host){
        //     $addressRandom = $addresses[array_rand($addresses)];
        //     $host->addAddress($addressRandom);
        //     $manager->persist($host);
        // }

        foreach ($addresses as $key => $address){
            $hostRandom = $hosts[array_rand($hosts)];
            $address->addHost($hostRandom);
            $manager->persist($address);
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
