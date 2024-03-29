<?php

namespace App\Controller;

use App\Entity\Host;
use App\Entity\User;
use App\Entity\Refugee;
use App\Form\RegisterHostType;
use App\Security\Authenticator;
use App\Form\RegisterRefugeeType;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'show_register')]
    public function showRegister(){
        return $this->render('registration/showRegister.html.twig');
    }

    #[Route('/register/{role<refugee|host>}', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, Authenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        if ($request->get('role') == "refugee"){
            $user = new Refugee();
            $user->setRoles(["ROLE_REFUGEE"]);
            $form = $this->createForm(RegisterRefugeeType::class, $user);
        }
        else if ($request->get('role') == "host"){
            $user = new Host();
            $user->setRoles(["ROLE_HOST"]);
            $form = $this->createForm(RegisterHostType::class, $user);
        }
        // dd($user);

        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            
            // dd($form->getErrors());

            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        $vars = [
            'registrationForm' => $form->createView(),
            'role' => $request->get('role')
        ];

        return $this->render('registration/register.html.twig', $vars);
    }
}
