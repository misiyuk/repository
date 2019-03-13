<?php

namespace App\Repository;

use App\Entity\CashOutcome;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CashOutcome|null find($id, $lockMode = null, $lockVersion = null)
 * @method CashOutcome|null findOneBy(array $criteria, array $orderBy = null)
 * @method CashOutcome[]    findAll()
 * @method CashOutcome[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CashOutcomeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CashOutcome::class);
    }

    // /**
    //  * @return CashOutcome[] Returns an array of CashOutcome objects
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
    public function findOneBySomeField($value): ?CashOutcome
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
