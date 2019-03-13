<?php

namespace App\Repository;

use App\Entity\PromotionProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PromotionProduct|null find($id, $lockMode = null, $lockVersion = null)
 * @method PromotionProduct|null findOneBy(array $criteria, array $orderBy = null)
 * @method PromotionProduct[]    findAll()
 * @method PromotionProduct[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PromotionProduct::class);
    }

    /**
     * @param bool $sync
     * @return PromotionProduct[]
     * @throws
     */
    public function findByInterval(bool $sync)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('pp', 'p')
            ->from('App:PromotionProduct', 'pp')
            ->join('pp.promotion', 'p')
        ;
        $qb
            ->andWhere(
                $qb->expr()->eq('pp.sync', ':sync')
            )
            ->andWhere('p.open <= :now AND p.close >= :now')
            ->setParameter('sync', $sync)
            ->setParameter('now', (new \DateTime())->setTimezone(new \DateTimeZone('UTC')))
        ;

        return $qb->getQuery()->getResult();
    }

    /**
     * @param bool $sync
     * @return PromotionProduct[]
     * @throws
     */
    public function findByOutInterval(bool $sync)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('pp', 'p')
            ->from('App:PromotionProduct', 'pp')
            ->join('pp.promotion', 'p')
        ;
        $qb
            ->andWhere(
                $qb->expr()->eq('pp.sync', ':sync')
            )
            ->andWhere('p.open >= :now OR p.close <= :now')
            ->setParameter('sync', $sync)
            ->setParameter('now', (new \DateTime())->setTimezone(new \DateTimeZone('UTC')))
        ;

        return $qb->getQuery()->getResult();
    }
}
