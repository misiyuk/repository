<?php

namespace App\Repository;

use App\Entity\ReportCondition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReportCondition|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportCondition|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportCondition[]    findAll()
 * @method ReportCondition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportConditionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ReportCondition::class);
    }

    // /**
    //  * @return ReportCondition[] Returns an array of ReportCondition objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReportCondition
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
