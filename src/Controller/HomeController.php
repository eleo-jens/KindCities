<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        // dd($this->getUser()->getRoles()[0]);
        if ($this->getUser()->getRoles()[0] == "ROLE_HOST"){
            return $this->redirectToRoute('app_host');
        }
        else if ($this->getUser()->getRoles()[0] == "ROLE_REFUGEE"){
            return $this->redirectToRoute('app_refugee');
        }; 
    }
}
