<?php

namespace App\Repository;

use App\Entity\Matiere;
use App\Entity\Stage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Matiere>
 */
class MatiereRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Matiere::class);
    }

    //    /**
    //     * @return Matiere[] Returns an array of Matiere objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Matiere
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    /**
     * Retourne toutes les matières associées à un stage donné.
     *
     * @param Stage $stage
     * @return Matiere[]|null
     */
    public function findMatiereByStage(Stage $stage): ?array
    {
        return $this->createQueryBuilder('m')
            ->join('m.stages', 's') // Jointure avec la table des stages
            ->where('s = :stage')  // Filtrer par le stage donné
            ->setParameter('stage', $stage)
            ->getQuery()
            ->getResult();
    }


}
