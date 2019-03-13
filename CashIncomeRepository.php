<?php

namespace App\Repository;

use App\Entity\CashIncome;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CashIncome|null find($id, $lockMode = null, $lockVersion = null)
 * @method CashIncome|null findOneBy(array $criteria, array $orderBy = null)
 * @method CashIncome[]    findAll()
 * @method CashIncome[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CashIncomeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CashIncome::class);
    }

    // /**
    //  * @return CashIncome[] Returns an array of CashIncome objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CashIncome
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
