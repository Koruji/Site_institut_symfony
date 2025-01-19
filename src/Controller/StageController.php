<?php

namespace App\Controller;

use App\Entity\Professeur;
use App\Entity\Stage;
use App\Entity\Stagiaire;
use App\Form\ParticipantType;
use App\Form\StageType;
use App\Repository\MatiereRepository;
use App\Repository\ProfesseurRepository;
use App\Repository\StageRepository;
use App\Repository\StagiaireRepository;
use App\Service\GenerateCode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class StageController extends AbstractController
{
    #[Route('/stage', name: 'app_stage_index', methods: ['GET'])]
    public function index(StageRepository $stageRepository, MatiereRepository $matiereRepository, ProfesseurRepository $professeurRepository, StagiaireRepository $stagiaireRepository): Response
    {
        $professeurs = [];
        $matieres = [];
        $stagiaires = [];

        foreach ($stageRepository->findAll() as $stage) {
            $professeurs[$stage->getId()] = [];
            foreach ($stage->getProfesseurs() as $professeur) {
                $professeurs[$stage->getId()][] = $professeur->getNom() . " " . $professeur->getPrenom(); // On récupère les libellés
            }
        }

        foreach ($stageRepository->findAll() as $stage) {
            $matieres[$stage->getId()] = [];
            foreach ($stage->getMatieres() as $matiere) {
                $matieres[$stage->getId()][] = $matiere->getLibelle(); // On récupère les libellés
            }
        }

        foreach ($stageRepository->findAll() as $stage) {
            array_push($stagiaires, $stagiaireRepository->findStagiaireByStage($stage));
        }

        return $this->render('stage/index.html.twig', [
            'stages' => $stageRepository->findAll(),
            'professeurs' => $professeurs,
            'stagiaires' => count($stagiaires),
            'matieres' => $matieres,
        ]);
    }

    #[Route('/stage/ajout_stage', name: 'app_stage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, GenerateCode $code): Response
    {
        $stage = new Stage();

        $form = $this->createForm(StageType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $stage->setCodeStage($code->code());

            $matieres = $form->get('matieres')->getData();
            foreach ($matieres as $matiere) {
                $stage->addMatiere($matiere);
                $matiere->addStage($stage);
            }

            $entityManager->persist($stage);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_dashboard', [], Response::HTTP_SEE_OTHER);
        }

        // Rendu du formulaire pour l'ajout d'un stage
        return $this->render('stage/new.html.twig', [
            'stage' => $stage,
            'form' => $form,
        ]);
    }


    #[Route('/stage/{id}', name: 'app_stage_show', methods: ['GET'])]
    public function show(Stage $stage): Response
    {
        $matieres = $stage->getMatieres();
        $professeurs = $stage->getProfesseurs();
        $stagiaires = $stage->getStagiaires();

        $libelleMatiere = [];
        $libelleProfesseur = [];
        $libelleStagiaire = [];

        foreach ($matieres as $matiere) {
            $libelleMatiere[] = $matiere->getLibelle();
        }

        foreach ($professeurs as $professeur) {
            $libelleProfesseur[] = $professeur->getNom() . " " . $professeur->getPrenom();
        }

        foreach ($stagiaires as $stagiaire) {
            $libelleStagiaire[] = $stagiaire->getNom() . " " . $stagiaire->getPrenom();
        }

        return $this->render('stage/show.html.twig', [
            'stage' => $stage,
            'matieres' => $libelleMatiere,
            'professeurs' => $libelleProfesseur,
            'stagiaires' => $libelleStagiaire,
        ]);
    }

    #[Route('/stage/{id}/participant', name: 'app_stage_show_participant', methods: ['GET'])]
    public function showParticipant(Stage $stage): Response
    {
        $matieres = $stage->getMatieres();
        $professeurs = $stage->getProfesseurs();
        $stagiaires = $stage->getStagiaires();

        $libelleMatiere = [];
        $libelleProfesseur = [];
        $libelleStagiaire = [];

        foreach ($matieres as $matiere) {
            $libelleMatiere[] = $matiere->getLibelle();
        }

        foreach ($professeurs as $professeur) {
            $libelleProfesseur[] = $professeur->getNom() . " " . $professeur->getPrenom();
        }

        foreach ($stagiaires as $stagiaire) {
            $libelleStagiaire[] = $stagiaire->getNom() . " " . $stagiaire->getPrenom();
        }

        return $this->render('stage/show_participant.html.twig', [
            'stage' => $stage,
            'matieres' => $libelleMatiere,
            'professeurs' => $libelleProfesseur,
            'stagiaires' => $libelleStagiaire,
        ]);
    }

    #[Route('/stage/{id}/modification_stage', name: 'app_stage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stage $stage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StageType::class, $stage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $matieres = $form->get('matieres')->getData();
            foreach ($matieres as $matiere) {
                $stage->addMatiere($matiere);
                $matiere->addStage($stage);
            }

            $entityManager->persist($stage);
            $entityManager->flush();

            return $this->redirectToRoute('app_stage_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('stage/edit.html.twig', [
            'stage' => $stage,
            'form' => $form,
        ]);
    }

    #[Route('/stage/{id}/suppression_stage', name: 'app_stage_delete', methods: ['POST'])]
    public function delete(Request $request, Stage $stage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $stage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($stage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stage_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/stage/{id}/ajout_participant', name: 'app_stage_participant', methods: ['GET', 'POST'])]
    public function ajoutParticipantStage(Request $request, Stage $stage, EntityManagerInterface $entityManager): Response
    {
        $matieresStage = $stage->getMatieres();
        $professeursForm = $entityManager->getRepository(Professeur::class)->findProfesseursByMatieres($matieresStage);

        $stagiairesForm = $entityManager->getRepository(Stagiaire::class)->findAll();

        $form = $this->createForm(ParticipantType::class, null, [
            'professeurs' => $professeursForm,
            'stagiaires' => $stagiairesForm,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $professeurs = $form->get('professeurs')->getData();
            foreach ($professeurs as $professeur) {
                $stage->addProfesseur($professeur);
                $professeur->addStage($stage);
            }

            $stagiaires = $form->get('stagiaires')->getData();
            foreach ($stagiaires as $stagiaire) {
                $stage->addStagiaire($stagiaire);
                $stagiaire->addStage($stage);
            }

            $entityManager->persist($stage);
            $entityManager->flush();

            return $this->redirectToRoute('app_stage_index');
        }

        return $this->render('stage/add_participant.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}
