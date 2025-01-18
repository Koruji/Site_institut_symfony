<?php

namespace App\Repository;

use App\Entity\Matiere;
use App\Entity\Professeur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Collection;

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
        $matiere = array($matieres);
        $qb = $this->createQueryBuilder('p')
            ->innerJoin('p.matiere', 'm')
            ->where('m IN (:matieres)')
            ->setParameter('matieres', $matiere);

        return $qb->getQuery()->getResult();
    }
}
