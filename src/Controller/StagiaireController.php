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
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stagiaire = new Stagiaire();
        $form = $this->createForm(StagiaireType::class, $stagiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stagiaire);
            $entityManager->flush();

            return $this->redirectToRoute('app_stagiaire_index', [], Response::HTTP_SEE_OTHER);
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
        $form = $this->createForm(StagiaireType::class, $stagiaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_stagiaire_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stagiaire/edit.html.twig', [
            'stagiaire' => $stagiaire,
            'form' => $form,
        ]);
    }

//    #[Route('/stagiaire/{id}', name: 'app_stagiaire_delete', methods: ['POST'])]
//    public function delete(Request $request, Stagiaire $stagiaire, EntityManagerInterface $entityManager): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$stagiaire->getId(), $request->getPayload()->getString('_token'))) {
//            $entityManager->remove($stagiaire);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('app_stagiaire_index', [], Response::HTTP_SEE_OTHER);
//    }

    #[Route('stagiaire/{id}/delete', name: 'app_stagiaire_delete', methods: ['POST'])]
    public function delete(Request $request, TokenStorageInterface $tokenStorage, Stagiaire $stagiaire, EntityManagerInterface $entityManager): Response
    {
        $user = $tokenStorage->getToken()?->getUser();

        if ($this->isCsrfTokenValid('delete'.$stagiaire->getId(), $request->get('_token'))) {
            $user->setIdStagiaire(null);
            $entityManager->persist($user);
            $entityManager->flush();

            $tokenStorage->setToken(null);
            $request->getSession()->invalidate();

            if ($stagiaire) {
                $entityManager->remove($stagiaire);
                $entityManager->flush();
            }

            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
