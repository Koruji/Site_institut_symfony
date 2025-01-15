<?php

namespace App\Service;
use App\Entity\Professeur;
use Doctrine\ORM\EntityManagerInterface;

class GenerateMatricule
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function generateMatricule(): string {

        do {
            $matricule = random_int(100, 1000);

            $existingUser = $this->entityManager->getRepository(Professeur::class)
                ->findOneBy(['matricule' => $matricule]);

        } while ($existingUser !== null);

        return strval($matricule);
    }
}