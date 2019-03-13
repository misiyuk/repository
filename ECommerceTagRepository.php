<?php

namespace App\Repository;

use App\Entity\ECommerceTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ECommerceTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method ECommerceTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method ECommerceTag[]    findAll()
 * @method ECommerceTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ECommerceTagRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ECommerceTag::class);
    }

    // /**
    //  * @return ECommerceTag[] Returns an array of ECommerceTag objects
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
    public function findOneBySomeField($value): ?ECommerceTag
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
