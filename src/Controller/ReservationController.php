<?php

namespace App\Controller;

use DateTime;
use Faker\Factory;
use App\Entity\Host;
use App\Entity\Refugee;
use App\Entity\Service;
use App\Entity\Reservation;
use App\Entity\Disponibilite;
use Doctrine\ORM\EntityManager;
use App\Entity\DetailReservation;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use App\Repository\DisponibiliteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[Route('/reservations', name: 'user_reservation')]
    public function getReservationsByUser(ReservationRepository $repo, EntityManagerInterface $em): Response
    {
        // $reservations = $findByUser();

        return $this->render('reservation/user_reservations.html.twig', [
                    'controller_name' => 'ReservationController',
                ]);
    }

    #[Route('/reservation', name: 'create_reservation')]
    public function createReservation(EntityManagerInterface $em, DisponibiliteRepository $dispoRepo, Request $req): Response
    {
        $faker = Factory::create();

        $idCategorie = (int)$req->get('disponibilite'); 
        $disponibilite = $dispoRepo->find($idCategorie);

        $reservation = new Reservation();
        $reservation->setDateReservation(new DateTime('now'));

        // s'il existe une endDate nous sommes dans le cas d'un Accommodation avec un créneau
        $beginDate = new DateTime($req->get('beginDate'));
        if ($req->get('endDate') != null){
            $endDate = new DateTime($req->get('endDate'));
            $reservation->setEndDate($endDate);
        }
        // nous sommes dans le cas d'un service qui ne se réserve que sur une seule date (endDate = beginDate)
        else {
            $reservation->setEndDate($beginDate);
        }
        $reservation->setBeginDate($beginDate);
        $reservation->setCodeReservation($faker->regexify('[A-Z]{5}[0-4]{3}'));
        $reservation->setService($disponibilite->getService());
        $reservation->setHost($disponibilite->getHost());
        $reservation->setRefugee($this->getUser());

        $em->persist($reservation);
        $em->flush();

        // appeller la fonction de mise à jour du tableau de disponibilités ! 
        $this->updateDisponibilite($disponibilite, $reservation, $em); 

        // renvoyer vers toutes les réservations du Refugee
        // Donc doit lancer une action index
        return $this->redirectToRoute('user_reservation');
    }


     public function updateDisponibilite(Disponibilite $dispo, Reservation $res, EntityManagerInterface $em)
    {
        // gestion des créanaux de disponibilité après une réservation
        // dates disponibles
        $debutDispo = $dispo->getBeginDateDispo();
        $finDispo = $dispo->getEndDateDispo();
        
        
        // dates pour la reservation qu'on veut faire
        $debutReservation = $res->getBeginDate();
        $finReservation = $res->getEndDate();
        
        // dump($debutReservation);
        // dump($finReservation);
        // dump(date_modify($debutReservation, '-1 day'));
        // dd(date_modify($finReservation, '+2 day'));

        // On peut comparer DateTimes avec ==, < et >
        // https://thevaluable.dev/php-datetime-create-compare-format 

        // 1. Si les dates similaires: créneau occupé totalement
        // Partez de cette disponibilité:
        // dispo: 1/1/2030 - 10/1/2030
        // reservation: 1/1/2030 - 10/1/2030
        // Rien n'est plus disponible: effacer disponibilité (remove)

        if ($debutReservation == $debutDispo  && $finReservation == $finDispo) {
            // - Effacer disponibilité du tableau, tout es pris
            $em->remove($dispo);
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
                // $creneau1->setBeginDateDispo($debutDispo); // date original de dispo
                // $creneau1->setEndDateDispo($debutReservation->modify('-1 day')); // le prémier créneau finit le jour avant que la Reservation commence 
                $debutReservation = date_modify($debutReservation, '-1 day');
                // $creneau1->setEndDateDispo(date_modify($debutReservation, '-1 day')); // le prémier créneau finit le jour avant que la Reservation commence 
                $creneau1->setEndDateDispo($debutReservation);
                $creneau1->setService($dispo->getService());
                $creneau1->setHost($dispo->getHost());
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
                $finReservation = date_modify($finReservation, '+2 day'); 
                // $creneau1->setBeginDateDispo(date_modify($finReservation, '+1 day'));
                // $creneau2->setBeginDateDispo($finReservation);
                $creneau2->setEndDateDispo($finDispo); // dispo jusqu'à la fin de la disponibilité
                $creneau2->setService($dispo->getService());
                // dd($host);
                $creneau2->setHost($dispo->getHost());
                $em->persist($creneau2);
                // $em->remove($dispoOriginale);
            }
        }
        // on aurait pu factoriser le code et mettre un seul remove ici 
        // (dans tous les cas il faut effacer la dispo originale!)
        // au lieu de dans chaque cas de figure. C'est juste pour l'explication qu'on les a mis en haut
        $em->remove ($dispo);
        $em->flush();
    }
}
