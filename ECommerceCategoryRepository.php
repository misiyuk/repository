<?php

namespace App\Repository;

use App\Entity\ECommerceCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ECommerceCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ECommerceCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ECommerceCategory[]    findAll()
 * @method ECommerceCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ECommerceCategoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ECommerceCategory::class);
    }

    // /**
    //  * @return ECommerceCategory[] Returns an array of ECommerceCategory objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ECommerceCategory
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
