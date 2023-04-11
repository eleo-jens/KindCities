<?php

namespace App\Controller;

use App\Repository\LanguageRepository;
use App\Repository\UserRepository;
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

    #[Route('/refugee/profile', name: 'refugee_profile')]
    public function getProfile(UserRepository $repo): Response
    {
        if ($this->getUser()){
            $user = $repo->find($this->getUser()->getId());
            $languages = $user->getLanguages();
        }

        $vars = [ 'user' => $user,
                 'languages' => $languages ]; 

        return $this->render('refugee/profile.html.twig', $vars);
    }
}
