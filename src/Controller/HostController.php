<?php

namespace App\Controller;

use App\Entity\Host;
use App\Form\RegisterHostType;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HostController extends AbstractController
{
    #[Route('/host', name: 'app_host')]
    public function index(): Response
    {
        return $this->render('host/index.html.twig', [
            'controller_name' => 'HostController',
        ]);
    }

    #[Route('/host/profile', name: 'host_profile')]
    public function getProfile(UserRepository $repo,): Response
    {
        if ($this->getUser()){
            $user = $repo->find($this->getUser()->getId());
            $languages = $user->getLanguages();
        }

        $vars = [ 'user' => $user,
                 'languages' => $languages ]; 

        return $this->render('host/profile.html.twig', $vars);
    }

    #[Route('/host/update/{id}', name: 'host_update')]
    public function listeUpdate(Host $host, ManagerRegistry $doctrine, Request $req): Response
    {
        // il suffit d'envoyer l'id dans l'URL et d'injecter un objet Livre.
        // Symfony (ParamConverter) obtient le repo et fait un findBy (id)

        $formulaireHost = $this->createForm(
            RegisterHostType::class,
            $host // voici le pré-remplissage
        );
        $formulaireHost->handleRequest($req);

        if ($formulaireHost->isSubmitted() && $formulaireHost->isValid()) {

            $em = $doctrine->getManager();
            $em->persist($host);
            $em->flush();

            return $this->redirectToRoute('host_profile');
        }

        else {
            return $this->render(
                '/host/update_profile.html.twig',
                ['formulaireLivre' => $formulaireHost->createView()]
            );
        }
    }

}
