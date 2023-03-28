<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Service;
use App\Form\AddressType;
use App\Form\ServiceType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ServiceController extends AbstractController
{
    // Voir tous les services disponibles 
    #[Route('/service', name: 'app_service')]
    public function index(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }

    // Page pour créer un service
    #[IsGranted('ROLE_HOST')]
    #[Route('/service/create/', name: 'create_service')]
    public function create(Request $req, ManagerRegistry $doctrine): Response
    {
        $service  = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        // créer un form adresse sans handlerequest
        $address = new Address(); 
        $formAddress = $this->createForm(AddressType::class, $address);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $doctrine->getManager();
            $em->persist($service);
            $em->flush();

            return new Response("C'est ajouté");
        }

        $vars = ['form' => $form->createView(),
                 'form_address' => $formAddress->createView()];

        return $this->render('service/createService.html.twig', $vars);
    }

    #[Route ("serice/create/add/address", name: "add_address")]
    public function addAddressService(Request $ajaxRequest){
        
        // créer une nouvelle Address
        // renvoyer l'id ? L'ajouter dans le service ?
        
        $street = $ajaxRequest->get('street');

        // retourner une reponse Json
        //return new JsonResponse();

    }

    // page de recherche d'un service
        #[Route('/service/search', name: 'search_service')]
    public function search(): Response
    {
        return $this->render('service/index.html.twig', [
            'controller_name' => 'ServiceController',
        ]);
    }
}
