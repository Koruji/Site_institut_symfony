<?php

namespace App\Controller;

use App\Entity\Stage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class StagiaireDashboardController extends AbstractController
{
    #[Route('/dashboard/stagiaire', name: 'app_stagiaire_dashboard')]
    #[IsGranted('ROLE_STAGIAIRE')]
    public function dashboard(EntityManagerInterface $entityManager): Response
    {
        //Récupération de l'utilisateur
        $user = $this->getUser();
        $stagiaire = $user->getIdStagiaire();

        if (!$user) {
            throw $this->createNotFoundException('User introuvable.');
        }

        //Récupération des stages
        $stagePasse = [];
        $stagePrevu = [];

        $stages = $entityManager->getRepository(Stage::class)->findStageByStagiaire($stagiaire);
        foreach ($stages as $stage) {
            if($stage->getDateFin() <= new \DateTime('now')) {
                array_push($stagePasse, $stage);
            } else {
                array_push($stagePrevu, $stage);
            }
        }

        return $this->render('stagiaire_dashboard/dashboard.html.twig', [
            'stagiaire' => $stagiaire,
            'stagesPrevu' => $stagePrevu,
            'stagesPasse' => $stagePasse,
        ]);
    }
}
