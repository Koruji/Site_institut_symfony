<?php

namespace App\Controller;

use App\Entity\Professeur;
use App\Entity\Stagiaire;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Initialisation des valeurs USER
            $userChoice = $form->get('role')->getData();
            $user->setRoles([$userChoice]);

            //Initialisation des utilisateurs en fonction du rôle
            switch($userChoice) {
                case 'ROLE_PROFESSEUR':
                    $professeur = new Professeur();
                    //$professeur->setMatricule();
                    $professeur->setNom($form->get('nom')->getData());
                    $professeur->setPrenom($form->get('prenom')->getData());
                    $professeur->setEmail($form->get('email')->getData());

                    $user->setIdProfesseur($professeur);
                    $entityManager->persist($professeur);
                    $entityManager->flush();

                    exit;

                case 'ROLE_STAGIAIRE':
                    $stagiaire = new Stagiaire();
                    $stagiaire->setNom($form->get('nom')->getData());
                    $stagiaire->setPrenom($form->get('prenom')->getData());
                    $stagiaire->setEmail($form->get('email')->getData());

                    $user->setIdStagiaire($stagiaire);
                    $entityManager->persist($stagiaire);
                    $entityManager->flush();
                    exit;

            }

            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            return $security->login($user, UserAuthenticator::class, 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
