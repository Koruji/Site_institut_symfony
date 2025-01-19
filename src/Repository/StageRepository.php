<?php

namespace App\Repository;

use App\Entity\Professeur;
use App\Entity\Stage;
use App\Entity\Stagiaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Stage>
 */
class StageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stage::class);
    }

    /**
     * Retourne tous les stages associés à un stagiaire donné.
     *
     * @param Stagiaire $stagiaire
     * @return Stage[]|null
     */
    public function findStageByStagiaire(Stagiaire $stagiaire): array
    {
        return $this->createQueryBuilder('st')
            ->join('st.stagiaires', 's') // Jointure avec la table des stages
            ->where('s = :stagiaire')   // Filtrer par le stage donné
            ->setParameter('stagiaire', $stagiaire)
            ->getQuery()
            ->getResult();
    }

    /**
     * Retourne tous les stages associés à un professeur donné.
     *
     * @param Professeur $professeur
     * @return Stage[]|null
     */
    public function findStageByProfesseur(Professeur $professeur): array
    {
        return $this->createQueryBuilder('st')
            ->join('st.professeurs', 's')
            ->where('s = :professeur')
            ->setParameter('professeur', $professeur)
            ->getQuery()
            ->getResult();
    }
}
