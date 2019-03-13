<?php

namespace App\Repository;

use App\Entity\Spend;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Spend|null find($id, $lockMode = null, $lockVersion = null)
 * @method Spend|null findOneBy(array $criteria, array $orderBy = null)
 * @method Spend[]    findAll()
 * @method Spend[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpendRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Spend::class);
    }

    // /**
    //  * @return Spend[] Returns an array of Spend objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Spend
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
