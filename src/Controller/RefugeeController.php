<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RefugeeController extends AbstractController
{
    #[Route('/refugee', name: 'app_refugee')]
    public function index(): Response
    {
        return $this->render('refugee/index.html.twig', [
            'controller_name' => 'RefugeeController',
        ]);
    }
}
