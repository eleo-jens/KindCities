<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Host;
use App\Entity\Service;
use App\Entity\Disponibilite;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class HostDisponibiliteServiceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {


        $faker = Factory::create();

        $hosts = $manager->getRepository(Host::class)->findAll();
        $services = $manager->getRepository(Service::class)->findAll();

        foreach ($services as $service) {
            $start = $faker->dateTimeThisYear();
            
            for ($i = 0; $i < 2; $i++) {
                $end = $faker->dateTimeBetween($start, '+6 days');
                
                $disponibilite = new Disponibilite();
                $disponibilite->setBeginDateDispo($start);
                $disponibilite->setEndDateDispo($end);

                $disponibilite->setService($service);
                // fixer le host
                $disponibilite->setHost($hosts[array_rand($hosts)]);
                
                $manager->persist($disponibilite);
                


                // dump($start);
                // dump($end);
                $start = $faker->dateTimeBetween($end, '+6 days');
                // dump($start);
                // dd($end);
                // dd($disponibilite);
            
            }
        }
        $manager->flush();
    }


    public function getDependencies()
    {
        return ([
            UserFixtures::class,
            ServiceFixtures::class,
        ]);
    }
}
