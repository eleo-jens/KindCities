<?php

namespace App\Controller;

use DateTime;
use Exception;
use Faker\Factory;
use App\Entity\Host;
use App\Entity\Refugee;
use App\Entity\Service;
use App\Entity\Reservation;
use Doctrine\ORM\Mapping\Id;
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
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReservationController extends AbstractController
{
    #[IsGranted('ROLE_REFUGEE')]
    #[Route('/reservations', name: 'user_reservation')]
    public function getReservationsByUser(ReservationRepository $repo, EntityManagerInterface $em): Response
    {
        if($this->getUser()){
            $user = $this->getUser();
            $upcomingReservations = $repo->findByUserUpcoming($user->getId());
            $passedReservations = $repo->findByUserPassed($user->getId());
        }

        $vars = ['upcomingBookings' => $upcomingReservations,
                 'passedBookings' => $passedReservations];

        return $this->render('reservation/user_reservations.html.twig', $vars);
    }

    #[IsGranted('ROLE_HOST')]
    #[Route('/bookings', name: 'host_reservation')]
    public function getReservationsByHost(ReservationRepository $repo, EntityManagerInterface $em): Response
    {
        if($this->getUser()){
            $user = $this->getUser();
            $upcomingReservations = $repo->findByHostUpcoming($user->getId());
            $passedReservations = $repo->findByHostPassed($user->getId());
        }

        $vars = ['upcomingBookings' => $upcomingReservations,
                 'passedBookings' => $passedReservations];

        return $this->render('reservation/host_reservations.html.twig', $vars);
    }

    #[Route('/reservation', name: 'create_reservation')]
    public function createReservation(EntityManagerInterface $em, DisponibiliteRepository $dispoRepo, Request $req): Response
    {
        // dd($req);

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
            $endDate = date_modify(new DateTime($req->get('beginDate')), '+22 hours');
            $reservation->setEndDate($endDate);
        }
        $reservation->setBeginDate($beginDate);
        $reservation->setCodeReservation($faker->regexify('[A-Z]{5}[0-4]{3}'));
        $reservation->setService($disponibilite->getService());
        $reservation->setHost($disponibilite->getHost());
        $reservation->setRefugee($this->getUser());

        // dd($reservation);

        $em->persist($reservation);
        $em->flush();

        // appeller la fonction de mise à jour du tableau de disponibilités ! 
        $this->updateDisponibilite($disponibilite, $reservation, $em); 

        // renvoyer vers toutes les réservations du Refugee
        // Donc doit lancer une action index
        return $this->redirectToRoute('user_reservation');
    }

    #[Route('/reservation/cancel/{id}', name: 'delete_reservation')]
    public function deleteReservation(ReservationRepository $repo, EntityManagerInterface $em, Request $req){
        //Attention si la reservation est supprimée, il faut mettre à jour de nouveau les disponibilités
        $id = $req->get("id");
        $reservation = $repo->Find($id);
        $em->remove($reservation);
        $em->flush();
    }

    #[Route('/reservation/edit/{id}', name: 'edit_reservation')]
    public function editReservation(Reservation $reservation, Request $req, EntityManagerInterface $em){
        //Attention si la reservation est mise à jour, il faut mettre à jour de nouveau les disponibilités
    }

     public function updateDisponibilite(Disponibilite $disponibilite, Reservation $reservation, EntityManagerInterface $em)
    {
        // gestion des crénaux de disponibilité après une réservation
        // dates disponibles
        $dispo = $em->getRepository(Disponibilite::class)->find($disponibilite->getId());
        $debutDispo = $dispo->getBeginDateDispo();
        $finDispo = $dispo->getEndDateDispo();
        
        // dates pour la reservation qu'on veut faire
        $res = $em->getRepository(Reservation::class)->find($reservation->getId());
        $debutReservation = $res->getBeginDate();
        $finReservation = $res->getEndDate();
        
        // dump('Debut res');
        // dump($debutReservation);
        // dump('Fin res');
        // dump($finReservation);
        // dump('Debut dispo');
        // dump($debutDispo);
        // dump('Fin dispo');
        // dump($finDispo);
        // dump('New Fin Dispo1');
        // dump(date_modify($debutReservation, '-1 day'));
        // dump('New Debut Dispo2');
        // dd(date_modify($finReservation, '+1 day'));

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
        } 
        else {

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
                try {
                    $copyDebutReservation = $res->getBeginDate();
                    // dump($copyDebutReservation);
                    // dd(date_modify($copyDebutReservation, '- 1 day'));
                    $creneau1 = new Disponibilite();
                    $creneau1->setBeginDateDispo($debutDispo); // date original de dispo
                    $creneau1->setEndDateDispo(date_modify($copyDebutReservation, '-1 day'));
                    $creneau1->setService($dispo->getService());
                    $creneau1->setHost($dispo->getHost());
                    // dump($creneau1);
                    $em->persist($creneau1);
                    $em->flush();

                }
                catch (Exception $e){
                    dump($e);
                }
            }
            // 2.2. finReservation < finDispo => créer un créneau de disponibilité 
            // qui commence après le dernier jour de la reservation et finit le dernier jour disponible.
            // Il faut laisser disponibles les dates de disponibilité après la fin de la réservation
            // ex: 
            // reservation qui finit le 7/1/2030 ---> créer disponibilité entre le 8/1/2030-10/1/2030 inclus
            if ($finReservation < $finDispo) {
                try {
                    $copyFinReservation = $res->getEndDate();
                    // dump($copyFinReservation);
                    // dd(date_modify($copyFinReservation, '+2 day'));
                    $creneau2 = new Disponibilite();
                    $creneau2->setBeginDateDispo(date_modify($copyFinReservation, '+1 day'));
                    $creneau2->setEndDateDispo($finDispo); // dispo jusqu'à la fin de la disponibilité
                    $creneau2->setService($dispo->getService());
                    $creneau2->setHost($dispo->getHost());
                    // dd($creneau2);
                    $em->persist($creneau2);
                    $em->flush();
                }
                catch (Exception $e){
                    dump($e);
                }
            }
        }
        // on aurait pu factoriser le code et mettre un seul remove ici 
        // (dans tous les cas il faut effacer la dispo originale!)
        // au lieu de dans chaque cas de figure. C'est juste pour l'explication qu'on les a mis en haut
        $em->remove($dispo);
        $em->flush();
    }
}
