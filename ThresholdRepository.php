<?php

namespace App\Repository;

use App\Entity\Threshold;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Threshold|null find($id, $lockMode = null, $lockVersion = null)
 * @method Threshold|null findOneBy(array $criteria, array $orderBy = null)
 * @method Threshold[]    findAll()
 * @method Threshold[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ThresholdRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Threshold::class);
    }

    public function getAll()
    {
        $qb = $this->createQuerybuilder('t')
            ->orderBy('t.value')
            ->getQuery();

        return $qb->execute();
    }
}
