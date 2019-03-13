<?php

namespace App\Repository;

use App\Entity\Purchase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Purchase|null find($id, $lockMode = null, $lockVersion = null)
 * @method Purchase|null findOneBy(array $criteria, array $orderBy = null)
 * @method Purchase[]    findAll()
 * @method Purchase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurchaseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Purchase::class);
    }

    /**
     * @param \DateTime $from
     * @param \DateTime $to
     * @return Purchase[]
     */
    public function findByInterval(\DateTime $from, \DateTime $to): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.act', 'a')
            ->andWhere('a.dateAct >= :from')
            ->andWhere('a.dateAct <= :to')
            ->andWhere('p.confirmed = true')
            ->setParameter('from', $from->setTimezone(new \DateTimeZone('UTC')))
            ->setParameter('to', $to->setTimezone(new \DateTimeZone('UTC')))
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Purchase[] Returns an array of Purchase objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Purchase
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
