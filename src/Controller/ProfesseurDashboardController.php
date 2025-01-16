<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProfesseurDashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_professeur_dashboard')]
    #[IsGranted('ROLE_PROFESSEUR')]
    public function dashboard(): Response
    {
        //Récupération de l'utilisateur
        $user = $this->getUser();
        $professeur = $user->getIdProfesseur();

        if (!$user) {
            throw $this->createNotFoundException('User introuvable.');
        }

        return $this->render('professeur_dashboard/dashboard.html.twig', [
            'professeur' => $professeur,
        ]);
    }
}
