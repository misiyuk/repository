<?php

namespace App\Repository;

use App\Entity\Batch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Batch|null find($id, $lockMode = null, $lockVersion = null)
 * @method Batch|null findOneBy(array $criteria, array $orderBy = null)
 * @method Batch[]    findAll()
 * @method Batch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BatchRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Batch::class);
    }

    /**
     * @return Batch[]
     */
    public function findBySupply()
    {
        return $this->createQueryBuilder('b')
            ->join('b.act', 'a')
            ->join('a.actInvoices', 'ai')
            ->join('ai.supplyProduct', 'sp')
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Batch[] Returns an array of Batch objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Batch
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
