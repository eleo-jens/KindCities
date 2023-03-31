<?php

namespace App\Controller;

use DateTime;
use App\Entity\Host;
use App\Entity\Refugee;
use App\Entity\Service;
use App\Entity\Reservation;
use App\Entity\Disponibilite;
use Doctrine\ORM\EntityManager;
use App\Entity\DetailReservation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{

    // CECI EST JUSTE UN TEST EN DUR
    #[Route('/reservation', name: 'app_reservation')]
    public function index(EntityManagerInterface $em): Response
    {
        $service = $em->getRepository(Service::class)->find(1);
        $host = $em->getRepository(Host::class)->find(1);
        $refugee = $em->getRepository(Refugee::class)->find(5);
        
        // créer une disponibilité
        $dispo = new Disponibilite();
        $dispo->setHost($host);
        $dispo->setService($service);
        $dispo->setBeginDateDispo(new DateTime("01-01-2023"));
        $dispo->setEndDateDispo(new DateTime("07-01-2023"));
        $em->persist($dispo);
        $em->flush();
        $dispoOriginale = $em->getRepository(Disponibilite::class)->find(1);
        
        // créer une réservation 
        $res = new Reservation();
        $res->setHost($host);
        $res->setRefugee($refugee);
        $res->setDateReservation(new DateTime("22-10-2022"));
        $res->setCodeReservation("AB123");
        $em->persist($res);
        $em->flush();
        $reservation = $em->getRepository(Reservation::class)->find(1);
        
        // créer une DetailReservation
        $detail = new DetailReservation();
        $detail->setService($service);
        $detail->setReservation($reservation);

        //TEST DE DIFFERENTES DATES
        // $detail->setBeginDate(new DateTime("03-01-2023"));
        // $detail->setEndDate(new DateTime("05-01-2023"));

        // $detail->setBeginDate(new DateTime("01-01-2023"));
        // $detail->setEndDate(new DateTime("07-01-2023"));

        // $detail->setBeginDate(new DateTime("04-01-2023"));
        // $detail->setEndDate(new DateTime("07-01-2023"));

        $detail->setBeginDate(new DateTime("01-01-2023"));
        $detail->setEndDate(new DateTime("04-01-2023"));

        $em->persist($detail);
        $em->flush();

        // gestion des créanaux de disponibilité après une réservation
        // dates pour les dispos
        $debutDispo = $dispo->getBeginDateDispo();
        $finDispo = $dispo->getEndDateDispo();

        // dates pour la reservation qu'on veut faire
        $debutReservation = $detail->getBeginDate();
        $finReservation = $detail->getEndDate();

        // On peut comparer DateTimes avec ==, < et >
        // https://thevaluable.dev/php-datetime-create-compare-format 

        // 1. Toutes les dates pareils: créneau occupé totalement
        // Partez de cette disponibilité:
        // dispo: 1/1/2030 - 10/1/2030
        // reservation: 1/1/2030 - 10/1/2030
        // Rien ne plus disponible: effacer disponibilité (remove)

        if ($debutReservation == $debutDispo  && $finReservation == $finDispo) {
            // - Effacer disponibilité du tableau, tout es pris
            $em->remove($dispoOriginale);
        } else {

            // 2. La réservation commence après le début de la disponibilité et/ou finit avant la fin de la disponibilité
            // Dans ce cas on effacera la disponibilité originale aussi et on créera 
            // 1 créneau (si seulement 2.1 arrive ou si seulement 2.2. arrive) 
            // ou 2 créneaux (si 2.1. et 2.2. arrivent en même temps).
            // Partez de cette disponibilité:
            // dispo: 1/1/2030 - 10/1/2030

            // 2.1. debutReservation > debutDispo => créer un créneau de disponibilité 
            // qui commence en debutDispo et finit en debutReservation.
            // ex: 
            // reservation qui commence le 4/1/2030 ---> créer disponibilité entre le 1/1/2030-3/1/2030 inclus
            // Il faut laisser disponibles les dates de disponibilité avant le début de la réservation
            // Effacer la dispo originale
            if ($debutReservation > $debutDispo) {
                $creneau1 = new Disponibilite();
                $creneau1->setBeginDateDispo($debutDispo); // date original de dispo
                // $creneau1->setEndDateDispo($debutReservation->modify('-1 day')); // le prémier créneau finit le jour avant que la Reservation commence 
                $creneau1->setEndDateDispo($debutReservation);
                $creneau1->setService($service);
                $creneau1->setHost($host);
                $em->persist($creneau1);
                // $em->remove($dispoOriginale);
            }
            // 2.2. finReservation < finDispo => créer un créneau de disponibilité 
            // qui commence après le dernier jour de la reservation et finit le dernier jour disponible.
            // Il faut laisser disponibles les dates de disponibilité après la fin de la réservation
            // ex: 
            // reservation qui finit le 7/1/2030 ---> créer disponibilité entre le 8/1/2030-10/1/2030 inclus
            if ($finReservation < $finDispo) {
                $creneau2 = new Disponibilite();
                // $creneau2->setBeginDateDispo($finReservation->modify('+1 day')); // dispo 1 jour après la fin de la réservation
                $creneau2->setBeginDateDispo($finReservation);
                $creneau2->setEndDateDispo($finDispo); // dispo jusqu'à la fin de la disponibilité
                $creneau2->setService($service);
                // dd($host);
                $creneau2->setHost($host);
                $em->persist($creneau2);
                // $em->remove($dispoOriginale);
            }
        }
        // on aurait pu factoriser le code et mettre un seul remove ici 
        // (dans tous les cas il faut effacer la dispo originale!)
        // au lieu de dans chaque cas de figure. C'est juste pour l'explication qu'on les a mis en haut
        $em->remove ($dispoOriginale);
        $em->flush();

        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }

    #[Route('/reservations', name: 'create_reservation')]
    public function createReservation(EntityManagerInterface $em, Request $req): Response
    {
        $reservation = new Reservation();
        $reservation->setDateReservation(new DateTime('now'));
        

        if ($req->get('endDate')){

        }
        $req->get('beginDate');
        dd($req);

        // return $this->render('reservation/index.html.twig', [
        //     'controller_name' => 'ReservationController',
        // ]);
    }
}
