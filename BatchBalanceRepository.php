<?php

namespace App\Repository;

use App\Entity\BatchBalance;
use App\Entity\ProductStock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BatchBalance|null find($id, $lockMode = null, $lockVersion = null)
 * @method BatchBalance|null findOneBy(array $criteria, array $orderBy = null)
 * @method BatchBalance[]    findAll()
 * @method BatchBalance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BatchBalanceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BatchBalance::class);
    }

    /**
     * @param ProductStock $productStock
     * @return BatchBalance
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByProductStock(ProductStock $productStock): ?BatchBalance
    {
        $result = $this->createQueryBuilder('bb')
            ->join('bb.batch', 'b')
            ->andWhere('bb.product = :product')
            ->andWhere('bb.stock = :stock')
            ->andWhere('bb.qty > 0')
            ->setParameter('product', $productStock->getProduct())
            ->setParameter('stock', $productStock->getStockUuid())
            ->setMaxResults(1)
            ->orderBy('b.act', 'ASC')
            ->getQuery()
            ->getOneOrNullResult();

        return $result;
    }

    /**
     * @param ProductStock $productStock
     * @return BatchBalance
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByProductStockForUp(ProductStock $productStock): ?BatchBalance
    {
        $result = $this->createQueryBuilder('bb')
            ->join('bb.batch', 'b')
            ->join('b.act', 'a')
            ->join('a.actInvoices', 'ai')
            ->andWhere('bb.product = :product')
            ->andWhere('bb.stock = :stock')
            ->andWhere('bb.product = ai.product')
            ->andWhere('ai.qty > bb.qty')
            ->setParameter('product', $productStock->getProduct())
            ->setParameter('stock', $productStock->getStockUuid())
            ->orderBy('b.act', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();

        return $result;
    }

    /**
     * @param BatchBalance $batchBalance
     * @return int
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function maxQty(BatchBalance $batchBalance): int
    {
        $result = $this->createQueryBuilder('bb')
            ->select('ai.qty maxQty')
            ->join('bb.batch', 'b')
            ->join('b.act', 'a')
            ->join('a.actInvoices', 'ai')
            ->andWhere('bb = :batchBalance')
            ->andWhere('ai.product = bb.product')
            ->setParameter('batchBalance', $batchBalance)
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult();

        return $result;
    }

    /*
    public function findOneBySomeField($value): ?BatchResidue
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
