<?php

namespace App\Controller;

use App\Entity\Professeur;
use App\Form\ProfesseurType;
use App\Repository\ProfesseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class ProfesseurController extends AbstractController
{
    #[Route('/professeur', name: 'app_professeur_index', methods: ['GET'])]
    public function index(ProfesseurRepository $professeurRepository): Response
    {
        return $this->render('professeur/index.html.twig', [
            'professeurs' => $professeurRepository->findAll(),
        ]);
    }

    #[Route('/professeur/ajout_professeur', name: 'app_professeur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $professeur = new Professeur();
        $form = $this->createForm(ProfesseurType::class, $professeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($professeur);
            $entityManager->flush();

            return $this->redirectToRoute('app_professeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('professeur/new.html.twig', [
            'professeur' => $professeur,
            'form' => $form,
        ]);
    }

    #[Route('/professeur/{id}', name: 'app_professeur_show', methods: ['GET'])]
    public function show(Professeur $professeur): Response
    {
        return $this->render('professeur/show.html.twig', [
            'professeur' => $professeur,
        ]);
    }

    #[Route('/professeur/{id}/modification_compte', name: 'app_professeur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Professeur $professeur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProfesseurType::class, $professeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $user->setEmail($professeur->getEmail());
            $user->setPrenom($professeur->getPrenom());
            $user->setNom($professeur->getNom());

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_professeur_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('professeur/edit.html.twig', [
            'professeur' => $professeur,
            'form' => $form,
        ]);
    }

//    #[Route('/professeur/{id}', name: 'app_professeur_delete', methods: ['POST'])]
//    public function delete(Request $request, Professeur $professeur, EntityManagerInterface $entityManager): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$professeur->getId(), $request->getPayload()->getString('_token'))) {
//            $entityManager->remove($professeur);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('app_professeur_index', [], Response::HTTP_SEE_OTHER);
//    }

    #[Route('professeur/{id}/delete', name: 'app_professeur_delete', methods: ['POST'])]
    public function delete(Request $request, TokenStorageInterface $tokenStorage, Professeur $professeur, EntityManagerInterface $entityManager): Response
    {
        $user = $tokenStorage->getToken()?->getUser();

        if ($this->isCsrfTokenValid('delete'.$professeur->getId(), $request->get('_token'))) {
            $user->setIdProfesseur(null);
            $entityManager->persist($user);
            $entityManager->flush();

            $tokenStorage->setToken(null);
            $request->getSession()->invalidate();

            if ($professeur) {
                $entityManager->remove($professeur);
                $entityManager->flush();
            }

            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
