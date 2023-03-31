<?php

namespace App\DataFixtures;

use App\Entity\Disponibilite;
use App\Entity\Service;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DisponibiliteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $services = $manager->getRepository(Service::class)->findAll();

        $dispo = new Disponibilite();
        $manager->persist($dispo);

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
