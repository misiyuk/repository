<?php

namespace App\Repository;

use App\Entity\ActInvoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ActInvoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActInvoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActInvoice[]    findAll()
 * @method ActInvoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActInvoiceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ActInvoice::class);
    }

    /**
     * @param ActInvoice $actInvoice
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(ActInvoice $actInvoice)
    {
        $this->_em->persist($actInvoice);
        $this->_em->flush();
    }
}
