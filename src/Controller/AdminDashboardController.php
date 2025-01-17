<?php

namespace App\Controller;

use App\Repository\MatiereRepository;
use App\Repository\ProfesseurRepository;
use App\Repository\StageRepository;
use App\Repository\StagiaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class AdminDashboardController extends AbstractController
{
    #[Route('/dashboard/admin', name: 'app_admin_dashboard', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function dashboard(MatiereRepository $matiereRepository,
                              ProfesseurRepository $professeurRepository,
                              StagiaireRepository $stagiaireRepository,
                              StageRepository $stageRepository): Response
    {
        //Récupération de l'utilisateur
        $user = $this->getUser();

        //Récupération des données
        $nbMatieres = count($matiereRepository->findAll());
        $nbProfesseur = count($professeurRepository->findAll());
        $nbStagiaire = count($stagiaireRepository->findAll());
        $nbStages = count($stageRepository->findAll());

        if (!$user) {
            throw $this->createNotFoundException('User introuvable.');
        }

        return $this->render('admin_dashboard/dashboard.html.twig', [
            'user' => $user,
            'matieres' => $nbMatieres,
            'professeurs' => $nbProfesseur,
            'stagiaires' => $nbStagiaire,
            'stages' => $nbStages,
        ]);
    }
}
