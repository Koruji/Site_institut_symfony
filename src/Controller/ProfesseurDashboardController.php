<?php

namespace App\Controller;

use App\Entity\Stage;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProfesseurDashboardController extends AbstractController
{
    #[Route('/dashboard/professeur', name: 'app_professeur_dashboard')]
    #[IsGranted('ROLE_PROFESSEUR')]
    public function dashboard(EntityManagerInterface $entityManager): Response
    {
        //Récupération de l'utilisateur
        $user = $this->getUser();
        $professeur = $user->getIdProfesseur();

        if (!$user) {
            throw $this->createNotFoundException('User introuvable.');
        }

        //Récupération des stages
        $stagePasse = [];
        $stagePrevu = [];

        $stages = $entityManager->getRepository(Stage::class)->findStageByProfesseur($professeur);
        foreach ($stages as $stage) {
            if($stage->getDateFin() <= new \DateTime('now')) {
                array_push($stagePasse, $stage);
            } else {
                array_push($stagePrevu, $stage);
            }
        }

        return $this->render('professeur_dashboard/dashboard.html.twig', [
            'professeur' => $professeur,
            'stagePasse' => $stagePasse,
            'stagePrevu' => $stagePrevu
        ]);
    }
}
