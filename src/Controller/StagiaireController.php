<?php

namespace App\Controller;

use App\Entity\Stagiaire;
use App\Entity\User;
use App\Form\StagiaireType;
use App\Repository\StagiaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class StagiaireController extends AbstractController
{
    #[Route('/stagiaire', name: 'app_stagiaire_index', methods: ['GET'])]
    public function index(StagiaireRepository $stagiaireRepository): Response
    {
        return $this->render('stagiaire/index.html.twig', [
            'stagiaires' => $stagiaireRepository->findAll(),
        ]);
    }

    #[Route('/stagiaire/ajout_stagiaire', name: 'app_stagiaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request,
                        EntityManagerInterface $entityManager,
                        UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $stagiaire = new Stagiaire();
        $user = new User();

        $form = $this->createForm(StagiaireType::class, $stagiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setNom($stagiaire->getNom());
            $user->setPrenom($stagiaire->getPrenom());
            $user->setEmail($stagiaire->getEmail());
            $user->setRoles(['ROLE_STAGIAIRE']);

            $mdp = $form->get('mdp')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $mdp));
            $user->setIdStagiaire($stagiaire);
            $entityManager->persist($user);

            $stagiaire->setDateInscription(new \DateTime());
            $entityManager->persist($stagiaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stagiaire/new.html.twig', [
            'stagiaire' => $stagiaire,
            'form' => $form,
        ]);
    }

    #[Route('/stagiaire/{id}', name: 'app_stagiaire_show', methods: ['GET'])]
    public function show(Stagiaire $stagiaire): Response
    {
        return $this->render('stagiaire/show.html.twig', [
            'stagiaire' => $stagiaire,
        ]);
    }

    #[Route('/stagiaire/{id}/modification_compte', name: 'app_stagiaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stagiaire $stagiaire, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $stagiaireUser = $entityManager->getRepository(User::class)->findByStagiaire($stagiaire);

        $form = $this->createForm(StagiaireType::class, $stagiaire, [
            'hide_mdp' => true,
            'mdp_actuel' => $stagiaireUser->getPassword(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stagiaireUser->setNom($stagiaire->getNom());
            $stagiaireUser->setPrenom($stagiaire->getPrenom());
            $stagiaireUser->setEmail($stagiaire->getEmail());

            $entityManager->persist($stagiaireUser);
            $entityManager->persist($stagiaire);
            $entityManager->flush();

            if($user->getRoles()[0] == "ROLE_STAGIAIRE"){
                return $this->redirectToRoute('app_stagiaire_dashboard', [], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_stagiaire_index', [], Response::HTTP_SEE_OTHER);
            }

        }

        return $this->render('stagiaire/edit.html.twig', [
            'stagiaire' => $stagiaire,
            'form' => $form,
        ]);
    }

    #[Route('stagiaire/{id}/delete', name: 'app_stagiaire_delete', methods: ['POST'])]
    public function delete(Request $request, TokenStorageInterface $tokenStorage, Stagiaire $stagiaire, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $stagiaireUser = $entityManager->getRepository(User::class)->findByStagiaire($stagiaire);

        if ($this->isCsrfTokenValid('delete'.$stagiaire->getId(), $request->get('_token'))){

            $stagiaireUser->setIdStagiaire(null);
            $entityManager->persist($stagiaireUser);
            $entityManager->flush();

            if($user->getRoles()[0] == "ROLE_STAGIAIRE"){
                $tokenStorage->setToken(null);
                $request->getSession()->invalidate();
            }

            if ($stagiaire) {
                $entityManager->remove($stagiaire);
                $entityManager->flush();
            }

            $entityManager->remove($stagiaireUser);
            $entityManager->flush();
        }

        if($user->getRoles()[0] == "ROLE_STAGIAIRE"){
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        } else {
            return $this->redirectToRoute('app_stagiaire_index', [], Response::HTTP_SEE_OTHER);
        }

    }
}
