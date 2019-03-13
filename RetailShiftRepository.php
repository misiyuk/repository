<?php

namespace App\Repository;

use App\Entity\RetailShift;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method RetailShift|null find($id, $lockMode = null, $lockVersion = null)
 * @method RetailShift|null findOneBy(array $criteria, array $orderBy = null)
 * @method RetailShift[]    findAll()
 * @method RetailShift[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RetailShiftRepository extends ServiceEntityRepository
{
    // /**

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RetailShift::class);
    }

    /**
     * @param RetailShift $retailShift
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(RetailShift $retailShift)
    {
        $this->_em->persist($retailShift);
        $this->_em->flush();
    }
}
