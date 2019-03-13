<?php

namespace App\Repository;

use App\Entity\Relocation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Relocation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Relocation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Relocation[]    findAll()
 * @method Relocation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelocationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Relocation::class);
    }

    // /**
    //  * @return Relocation[] Returns an array of Relocation objects
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
    public function findOneBySomeField($value): ?Relocation
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
