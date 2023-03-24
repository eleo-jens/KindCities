<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HostController extends AbstractController
{
    #[Route('/host', name: 'app_host')]
    public function index(): Response
    {
        return $this->render('host/index.html.twig', [
            'controller_name' => 'HostController',
        ]);
    }
}
