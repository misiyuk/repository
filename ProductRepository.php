<?php

namespace App\Repository;

use App\Entity\Act;
use App\Entity\CategoryInterface;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @param $limit
     * @param $offset
     * @return Product[]
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        if (!array_key_exists('status', $criteria)) {
            $criteria['status'] = Product::STATUS_ACTIVE;
        }

        return parent::findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findAllByBarcodeAndName($barcode, $name)
    {
        $query = $this->_em->createQueryBuilder()
            ->select('p', 'b')
            ->from('App:Product', 'p')
            ->join('p.barcodes', 'b')
        ;
        $query
            ->where(
                $query->expr()->orX(
                    $query->expr()->like('b.value', ':like'),
                    $this->tsMatchOp('p.name', ':ts')
                )
            )
            ->andWhere(
                $query->expr()->eq('p.status', Product::STATUS_ACTIVE)
            )
            ->setParameter('like', $this->likeParam($name))
            ->setParameter('ts', $this->tsParam($name))
        ;

        return $query->getQuery()->getResult();
    }

    /**
     * @param $page
     * @param $limit
     * @param Paginator $paginator
     * @param null $filter
     * @param CategoryInterface[] $categories
     * @return PaginationInterface
     */
    public function findAllByPaginator($page, $limit, Paginator $paginator, $filter = null, array $categories = null): PaginationInterface
    {
        $query = $this->_em->createQueryBuilder()
            ->select('p', 'b')
            ->from('App:Product', 'p')
            ->join('p.barcodes', 'b')
            ->join('p.internalCodes', 'ic')
        ;
        $filter = trim($filter);
        if (!empty($filter)) {
            $query
                ->where(
                    $query->expr()->orX(
                        $this->tsMatchOp('p.name', ':ts'),
                        $query->expr()->like(
                            $query->expr()->upper('ic.value'),
                            ':like'
                        ),
                        $query->expr()->like(
                            $query->expr()->upper('p.name'),
                            ':like'
                        ),
                        $query->expr()->like(
                            'b.value',
                            ':like'
                        )
                    )
                )
                ->setParameter('like', $this->likeParam($filter))
                ->setParameter('ts', $this->tsParam($filter))
            ;
        }
        if ($categories !== null) {
            $query
                ->andWhere(
                    $query->expr()->in('p.category', array_map(function (CategoryInterface $category) {
                        return $category->getId();
                    }, $categories))
                )
            ;
        }
        $query->andWhere(
            $query->expr()->eq('p.status', Product::STATUS_ACTIVE)
        );

        return $paginator->paginate(
            $query,
            $page,
            $limit
        );
    }

    private function tsParam(string $query): string
    {
        return preg_replace('#\s+#', '+', trim($query)).':*';
    }

    private function likeParam(string $query): string
    {
        return '%'.mb_strtoupper($query).'%';
    }

    private function tsMatchOp(string $vector, string $query): string
    {
        return 'TS_MATCH_OP(TO_TSVECTOR('.$vector.'), TO_TSQUERY('.$query.')) = true';
    }

    /**
     * @param string $internalCode
     * @return Product|null
     * @throws
     */
    public function findOneByInternalCode(string $internalCode)
    {
        $qb = $this->createQueryBuilder('p')
            ->join('p.internalCodes', 'ic')
            ->where('ic.value = :internalCode')
            ->setParameter('internalCode', $internalCode)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string $barcode
     * @return Product|null
     * @throws
     */
    public function findOneByBarcode(string $barcode)
    {
        $qb = $this->createQueryBuilder('p')
            ->join('p.barcodes', 'b')
            ->where('b.value = :barcode')
            ->setParameter('barcode', $barcode)
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @return Product[]
     */
    public function findByPurchaseHelper(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.purchaseHelper = true')

            ->getQuery()
            ->getResult()
        ;
    }

    public function countNotConfirmedPurchase(Product $product)
    {
        $notConfirmedPurchaseQty = $this->createQueryBuilder('p')
            ->select('SUM(ai.qty)')
            ->join('p.actInvoices', 'ai')
            ->join('ai.act', 'a')
            ->join('a.purchase', 'purchase')

            ->where('purchase.confirmed != true')
            ->andWhere('p = :product')
            ->setParameter('product', $product->getId())

            ->getQuery()
            ->getResult()[0][1]
        ;

        return $notConfirmedPurchaseQty;
    }

    public function countConfirmedPurchaseNotSupply(Product $product)
    {
        $confirmedPurchaseQty = $this->createQueryBuilder('p')
            ->select('SUM(ai.qty)')
            ->join('p.actInvoices', 'ai')
            ->join('ai.act', 'a')
            ->join('a.purchase', 'purchase')

            ->where('purchase.confirmed = true')
            ->andWhere('p = :product')
            ->setParameter('product', $product->getId())

            ->getQuery()
            ->getResult()[0][1]
        ;
        $supplyQty = $this->createQueryBuilder('p')
            ->select('SUM(ai.qty)')
            ->join('p.actInvoices', 'ai')
            ->join('ai.act', 'a')
            ->where('a.type = :supplyType')
            ->andWhere('p = :product')
            ->setParameter('product', $product->getId())
            ->setParameter('supplyType', Act::SUPPLY_TYPE)

            ->getQuery()
            ->getResult()[0][1]
        ;

        return $confirmedPurchaseQty - $supplyQty;
    }
}
