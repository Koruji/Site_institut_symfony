<?php

namespace App\Repository;

use App\Entity\Matiere;
use App\Entity\Professeur;
use App\Entity\Stage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Professeur>
 */
class ProfesseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Professeur::class);
    }

    //    /**
    //     * @return Professeur[] Returns an array of Professeur objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Professeur
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Récupère les professeurs associés aux matières spécifiques.
     *
     * @param Matiere[] $matieres
     * @return Professeur[]
     */
    public function findProfesseursByMatieres(Collection $matieres): array
    {
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.matiere', 'm')  // Jointure avec la table Matiere
            ->where('m IN (:matieres)')  // Sélectionne les professeurs liés aux matières
            ->setParameter('matieres', $matieres);  // Passe la collection directement

        return $qb->getQuery()->getResult();  // Retourne le résultat
    }


    /**
     * Trouve tous les professeurs associés à un stage donné.
     *
     * @param Stage $stage
     * @return Professeur[]
     */
    public function findProfesseurByStage(Stage $stage): array
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.stages', 's')
            ->where('s = :stage')
            ->setParameter('stage', $stage)
            ->getQuery()
            ->getResult();
    }
}
