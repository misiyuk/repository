<?php

namespace App\Repository;

use App\Entity\SaleReturn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SaleReturn|null find($id, $lockMode = null, $lockVersion = null)
 * @method SaleReturn|null findOneBy(array $criteria, array $orderBy = null)
 * @method SaleReturn[]    findAll()
 * @method SaleReturn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SaleReturnRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SaleReturn::class);
    }

    // /**
    //  * @return SaleReturn[] Returns an array of SaleReturn objects
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
    public function findOneBySomeField($value): ?SaleReturn
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
