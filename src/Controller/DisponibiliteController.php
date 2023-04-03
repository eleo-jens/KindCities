<?php

namespace App\Controller;

use DateTime;
use App\Entity\Reservation;
use App\Entity\Disponibilite;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DisponibiliteController extends AbstractController
{
    #[Route('/disponibilite', name: 'app_disponibilite')]
    public function index(): Response
    {
        return $this->render('disponibilite/index.html.twig', [
            'controller_name' => 'DisponibiliteController',
        ]);
    }
}
