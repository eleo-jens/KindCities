<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Refugee;
use App\Entity\Reservation;
use App\Entity\Disponibilite;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\ServiceFixtures;
use App\DataFixtures\CategorieFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\HostDisponibiliteServiceFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ReservationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        
        $refugees = $manager->getRepository(Refugee::class)->findAll();
        $disponibilites = $manager->getRepository(Disponibilite::class)->findAll();

        foreach ($refugees as $key => $refugee) {
            for ($i=0; $i < 2 ; $i++) { 
                $reservation = new Reservation();
                $dispoRandom = new Disponibilite();
                $dispoRandom = $disponibilites[array_rand($disponibilites)];
                $reservation->setRefugee($refugee);
                $reservation->setHost($dispoRandom->getHost());
                $reservation->setService($dispoRandom->getService());
                $reservation->setCodeReservation($faker->regexify('[A-Z]{5}[0-4]{3}'));
                // dump($dispoRandom->getBeginDateDispo());
                $endDateDispo =  new DateTime();
                $endDateDispo = $dispoRandom->getEndDateDispo();
                // dump($endDateDispo);
                $beginDate = new DateTime();
                $beginDateDispo = new DateTime();
                $beginDateDispo = $dispoRandom->getBeginDateDispo();
                $beginDate = $faker->dateTimeBetween($beginDateDispo, $endDateDispo);
                // dump($beginDate);
                $reservation->setBeginDate($beginDate);
                $idCategorie = $dispoRandom->getService()->getCategorie()->getId() ;
                // dump($idCategorie);
                if ($idCategorie == 2){
                    // dump('Une accommodation');
                    $endMin = new DateTime();
                    $endMin = $faker->dateTimeBetween($beginDate, $endDateDispo);
                    // dump($endMin);
                    // dump($endDateDispo);
                    $reservation->setEndDate($faker->dateTimeBetween($endMin, $endDateDispo));

                }
                else {
                    // dump('Une service one shot');
                    // $date = new DateTime();
                    // $date = $faker->dateTimeBetween($beginDate, '+10 hours');
                    // dump($date);
                    $reservation->setEndDate(clone $beginDate);
                }
                // dd($reservation);
                $manager->persist($reservation);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return ([
            CategorieFixtures::class,
            UserFixtures::class,
            ServiceFixtures::class,
            HostDisponibiliteServiceFixtures::class,
        ]);
    }
}
