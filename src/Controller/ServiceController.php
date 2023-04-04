<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Service;
use App\Form\SearchType;
use App\Form\AddressType;
use App\Form\ServiceType;
use App\Request\SearchRequest;
use App\Repository\AddressRepository;
use App\Repository\ServiceRepository;
use App\Repository\CategorieRepository;
use App\Repository\DisponibiliteRepository;
use App\Repository\HostRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ServiceController extends AbstractController
{
    // Voir tous les services disponibles 
    #[Route('/services', name: 'app_service')]
    public function index(ServiceRepository $repo, DisponibiliteRepository $dispoRepo, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();

        $services = $repo->findAllWithDisponibilites();

        // dd($services);

        // createQuery ne fonctionne pas !
        // $query = $em->createQuery('SELECT service, disponibilites FROM App\Entity\Service service '
        //         . 'JOIN service.disponibilites disponibilites');
        // dd($services);

        $vars = ['services' => $services];

        return $this->render('service/index.html.twig', $vars);
    }

    // Page pour créer un service
    #[IsGranted('ROLE_HOST')]
    #[Route('/service/create/', name: 'create_service')]
    public function create(Request $req, ManagerRegistry $doctrine): Response
    {
        $service  = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        // créer un form address sans handlerequest
        $address = new Address();
        $formAddress = $this->createForm(AddressType::class, $address);
        $form->handleRequest($req);
        // && $form->isValid()

        if ($form->isSubmitted()) {
            foreach ($service->getDisponibilites() as $key => $dispo) {
                $dispo->setHost($this->getUser());
            }
            $em = $doctrine->getManager();
            $em->persist($service);
            $em->flush();

            // ici il faudrait redirct vers la page dashboard de l'host à créer
            return new Response("C'est ajouté");
        }

        $vars = [
            'form' => $form->createView(),
            'form_address' => $formAddress->createView()
        ];

        return $this->render('service/createService.html.twig', $vars);
    }

    // créer une nouvelle Address (AJAX)
    #[IsGranted('ROLE_HOST')]
    #[Route("service/create/add/address", name: "add_address")]
    public function addAddressService(Request $ajaxRequest, ManagerRegistry $doctrine, SerializerInterface $serialiser)
    {

        $address = $ajaxRequest->get('address');
        $newAddress = new Address($address);
        $newAddress->addHost($this->getUser());
        $em = $doctrine->getManager();
        $em->persist($newAddress);
        $em->flush();

        // retourner une reponse Json
        $json = $serialiser->serialize($newAddress, 'json', [AbstractNormalizer::IGNORED_ATTRIBUTES => ['hosts', 'services']]);

        return new JsonResponse($json);
    }

    // page de recherche de services
    #[Route('/service/search', name: 'search_service')]
    public function searchService(Request $request, DisponibiliteRepository $repo): Response
    {
        $searchRequest = new SearchRequest();

        $form = $this->createForm(SearchType::class, $searchRequest, [
            'method' => 'GET'
        ]);

        $vars = [
            'form' => $form->handleRequest($request)
        ];

        if ($form->isSubmitted()) {

            $disponibilites = $repo->findByFilters($searchRequest->getCategorie()?->getId(), $searchRequest->getFrom(), $searchRequest->getTo());

            // dump($services[0]->getPictures()[0]->getName());
            // dd($disponibilites);
            return $this->render('service/results.html.twig', [
                'results' => $disponibilites,
                'categoryName' => $searchRequest->getCategorie()->getName(),
                'fromDate' => $searchRequest->getFrom(),
                'toDate' => $searchRequest->getFrom()
            ]);
        }
        return $this->render('service/search.html.twig', $vars);
    }

    #[Route('/service/disponibilite/{id}', name: 'disponibilite_details')]
    public function disponibiliteDetails(ManagerRegistry $doctrine, DisponibiliteRepository $repo, ServiceRepository $repoService, Request $req)
    {

        $id = $req->get('id');
        $disponibilite = $repo->find($id);
        $idService = $disponibilite->getService();
        $categorie = $repoService->find($idService)->getCategorie()->getName();

        $vars = [
            'disponibilite' => $disponibilite,
            'id' => $id,
            'categorie' => $categorie
        ];
        return $this->render('service/disponibiliteDetails.html.twig', $vars);
    }

    #[Route('/service/{id}', name: 'service_details')]
    public function serviceDetails(ServiceRepository $repo, Request $req)
    {

        $id = $req->get('id');
        $service = $repo->findOneWithDisponibilites($id);

        $disponibilites = $service->getDisponibilites();

        $arrayDisposCalendar = [];
        foreach ($disponibilites as $disponibilite) {
            $arrayDisposCalendar[] = [
                'from' => $disponibilite->getBeginDateDispo()->format('d-m-Y'),
                'to' => $disponibilite->getEndDateDispo()->format('d-m-Y')
            ];
        }

        $vars = [
            'service' => $service,
            'disponibilitesJson' => json_encode($arrayDisposCalendar)
        ];

        // dd($vars);
        return $this->render('service/serviceDetails.html.twig', $vars);
    }
}
