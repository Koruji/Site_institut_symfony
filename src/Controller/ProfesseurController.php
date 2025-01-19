<?php

namespace App\Controller;

use App\Entity\Matiere;
use App\Entity\Professeur;
use App\Entity\User;
use App\Form\ProfesseurType;
use App\Repository\ProfesseurRepository;
use App\Service\GenerateMatricule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    #[IsGranted("ROLE_ADMIN")]
    public function new(Request $request,
                        EntityManagerInterface $entityManager,
                        UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $professeur = new Professeur();
        $user = new User();
        $matricule = (new GenerateMatricule($entityManager))->generateMatricule();

        $form = $this->createForm(ProfesseurType::class, $professeur, [
            'hide_matricule' => true,
            'matricule' => $matricule,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setNom($professeur->getNom());
            $user->setPrenom($professeur->getPrenom());
            $user->setEmail($professeur->getEmail());
            $user->setRoles(['ROLE_PROFESSEUR']);
            $user->setIdProfesseur($professeur);

            $mdp = $form->get('mdp')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $mdp));
            $entityManager->persist($user);

            $entityManager->persist($professeur);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_dashboard', [], Response::HTTP_SEE_OTHER);
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
        $user = $this->getUser();
        $professeurUser = $entityManager->getRepository(User::class)->findByProfesseur($professeur);

        $form = $this->createForm(ProfesseurType::class, $professeur,  [
            'hide_mdp' => true,
            'mdp_actuel' => $professeurUser->getPassword(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $professeurUser->setNom($professeur->getNom());
            $professeurUser->setPrenom($professeur->getPrenom());
            $professeurUser->setEmail($professeur->getEmail());

            $matiere = ($entityManager->getRepository(Matiere::class))->find($professeur->getMatiere());
            $matiere->addProfesseur($professeur);

            $entityManager->persist($professeurUser);
            $entityManager->persist($matiere);
            $entityManager->persist($professeur);
            $entityManager->flush();

            if($user->getRoles()[0] == "ROLE_PROFESSEUR"){
                return $this->redirectToRoute('app_professeur_dashboard', [], Response::HTTP_SEE_OTHER);
            } else {
                return $this->redirectToRoute('app_professeur_index', [], Response::HTTP_SEE_OTHER);
            }

        }

        return $this->render('professeur/edit.html.twig', [
            'professeur' => $professeur,
            'form' => $form,
        ]);
    }

    #[Route('professeur/{id}/delete', name: 'app_professeur_delete', methods: ['POST'])]
    public function delete(Request $request, TokenStorageInterface $tokenStorage, Professeur $professeur, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $professeurUser = $entityManager->getRepository(User::class)->findByProfesseur($professeur);

        if ($this->isCsrfTokenValid('delete'.$professeur->getId(), $request->get('_token'))) {

            $professeurUser->setIdProfesseur(null);
            $entityManager->persist($professeurUser);
            $entityManager->flush();

            if($user->getRoles()[0] == "ROLE_PROFESSEUR"){
                $tokenStorage->setToken(null);
                $request->getSession()->invalidate();
            }

            if ($professeur) {
                $entityManager->remove($professeur);
                $entityManager->flush();
            }

            $entityManager->remove($professeurUser);
            $entityManager->flush();
        }

        if($user->getRoles()[0] == "ROLE_PROFESSEUR"){
            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        } else {
            return $this->redirectToRoute('app_professeur_index', [], Response::HTTP_SEE_OTHER);
        }

    }
}
