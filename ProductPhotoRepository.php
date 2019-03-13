<?php

namespace App\Repository;

use App\Entity\ProductPhoto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProductPhoto|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductPhoto|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductPhoto[]    findAll()
 * @method ProductPhoto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductPhotoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductPhoto::class);
    }

    // /**
    //  * @return RoductPhoto[] Returns an array of RoductPhoto objects
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
    public function findOneBySomeField($value): ?RoductPhoto
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
