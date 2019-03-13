<?php

namespace App\Repository;

use App\Entity\SpendCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SpendCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpendCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpendCategory[]    findAll()
 * @method SpendCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpendCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SpendCategory::class);
    }

    // /**
    //  * @return SpendCategory[] Returns an array of SpendCategory objects
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
    public function findOneBySomeField($value): ?SpendCategory
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
