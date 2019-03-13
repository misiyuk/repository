<?php

namespace App\Repository;

use App\Entity\ClientCartItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ClientCartItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientCartItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientCartItem[]    findAll()
 * @method ClientCartItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientCartItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ClientCartItem::class);
    }

    // /**
    //  * @return ClientCartItem[] Returns an array of ClientCartItem objects
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
    public function findOneBySomeField($value): ?ClientCartItem
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
