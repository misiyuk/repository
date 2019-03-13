<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\ProductStock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProductStock|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductStock|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductStock[]    findAll()
 * @method ProductStock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductStockRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductStock::class);
    }

    /**
     * @param ProductStock $productStock
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(ProductStock $productStock)
    {
        $this->_em->persist($productStock);
        $this->_em->flush();
    }

    public function findByProductQty(Product $product)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('SUM(ps.qty)')
            ->from('App:ProductStock', 'ps')
            ->where('ps.product = :product')
            ->setParameter('product', $product->getId())
        ;

        return $qb->getQuery()->getResult()[0][1] ?? null;
    }
}
