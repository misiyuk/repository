<?php

namespace App\Repository;

use App\Entity\DaySaleStat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DaySaleStat|null find($id, $lockMode = null, $lockVersion = null)
 * @method DaySaleStat|null findOneBy(array $criteria, array $orderBy = null)
 * @method DaySaleStat[]    findAll()
 * @method DaySaleStat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DaySaleStatRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DaySaleStat::class);
    }

    // /**
    //  * @return DaySaleStat[] Returns an array of DaySaleStat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DaySaleStat
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
