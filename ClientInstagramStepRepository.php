<?php

namespace App\Repository;

use App\Entity\ClientInstagramStep;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ClientInstagramStep|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientInstagramStep|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientInstagramStep[]    findAll()
 * @method ClientInstagramStep[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientInstagramStepRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ClientInstagramStep::class);
    }

    // /**
    //  * @return ClientInstagramStep[] Returns an array of ClientInstagramStep objects
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
    public function findOneBySomeField($value): ?ClientInstagramStep
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
