<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class StagiaireDashboardController extends AbstractController
{
    #[Route('/dashboard/stagiaire', name: 'app_stagiaire_dashboard')]
    #[IsGranted('ROLE_STAGIAIRE')]
    public function dashboard(): Response
    {
        //Récupération de l'utilisateur
        $user = $this->getUser();
        $stagiaire = $user->getIdStagiaire();

        if (!$user) {
            throw $this->createNotFoundException('User introuvable.');
        }

        return $this->render('stagiaire_dashboard/dashboard.html.twig', [
            'stagiaire' => $stagiaire,
        ]);
    }
}
