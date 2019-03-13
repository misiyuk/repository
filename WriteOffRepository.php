<?php

namespace App\Repository;

use App\Entity\WriteOff;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WriteOff|null find($id, $lockMode = null, $lockVersion = null)
 * @method WriteOff|null findOneBy(array $criteria, array $orderBy = null)
 * @method WriteOff[]    findAll()
 * @method WriteOff[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WriteOffRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WriteOff::class);
    }

    // /**
    //  * @return WriteOff[] Returns an array of WriteOff objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WriteOff
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
