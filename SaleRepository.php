<?php

namespace App\Repository;

use App\Entity\CategoryArray;
use App\Entity\CategoryInterface;
use App\Entity\Product;
use App\Entity\Sale;
use App\Services\CategoryHelper;
use App\Services\DateHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sale|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sale|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sale[]    findAll()
 * @method Sale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @property CategoryHelper $categoryHelper
 * @property CategoryRepository $categoryRepository
 * @property DateHelper $dh
 */
class SaleRepository extends ServiceEntityRepository
{
    private $categoryHelper;
    private $categoryRepository;
    private $dh;
    static $dailySales;

    public function __construct(
        RegistryInterface $registry,
        CategoryHelper $categoryHelper,
        CategoryRepository $categoryRepository,
        DateHelper $dateHelper
    )
    {
        $this->categoryHelper = $categoryHelper;
        $this->dh = $dateHelper->setTimezone(new \DateTimeZone('UTC'));
        $this->categoryRepository = $categoryRepository;
        parent::__construct($registry, Sale::class);
    }

    /**
     * @param null $storeId
     * @return mixed[]
     * @throws
     */
    public function getTodayStat($storeId = null)
    {
        $stmt = $this->getEntityManager()->getConnection()
            ->prepare(
                'SELECT COUNT(*) AS "amtSales", 
                              SUM("sg"."sum") as "amtSum", 
                              AVG("sg"."countItems") as "avgQty", 
                              AVG("sg"."sum") as "avgSaleSum" 
                            FROM (
                              SELECT 
                                COUNT(*) as "countItems", 
                                AVG(sale.amt_sum) as "sum" 
                              FROM sale 
                                LEFT JOIN act on sale.act_id = act.id
                                LEFT JOIN retail_shift rs on sale.retail_shift_uuid = rs.id
                                LEFT JOIN cashbox cb on rs.cashbox_uuid = cb.id
                                LEFT JOIN act_invoice ai on ai.act_id = act.id
                              WHERE sale.created_at > CURRENT_DATE'.($storeId ? " AND cb.store_uuid = '$storeId' " : '').'
                              GROUP BY sale.act_id
                            ) sg;'
            );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * @param string|null $store
     * @return float
     * @throws \Exception
     */
    public function findSumInThisMouth(?string $store = null): float
    {
        $saleSum = $this->saleSumInThisMouth($store);
        $saleReturnSum = $this->saleReturnSumInThisMouth($store);

        return $saleSum - $saleReturnSum;
    }

    public function saleSumInThisMouth(?string $store)
    {
        $qb = $this->createQueryBuilder('s');
        $qb
            ->select('SUM(s.amtSum)')
            ->join('s.retailShift', 'rs')
            ->join('rs.cashbox', 'cb')
        ;
        $qb->where('s.createdAt >= :from');
        $qb->andWhere('s.createdAt < :to');
        if ($store) {
            $qb->andWhere('cb.store = :store');
            $qb->setParameter('store', $store);
        }
        $qb->setParameter('from', $this->dh->firstDayOfMonth);
        $qb->setParameter('to', $this->dh->currentDay);

        return $qb->getQuery()->getResult()[0][1];
    }

    public function saleReturnSumInThisMouth(?string $store)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select('SUM(s.amtSum)')
            ->from('App:SaleReturn', 's')
            ->join('s.retailShift', 'rs')
            ->join('rs.cashbox', 'cx')
            ->join('cx.store', 'store')
        ;
        $qb->where('s.createdAt >= :from');
        $qb->andWhere('s.createdAt < :to');
        if ($store) {
            $qb->andWhere('store = :store');
            $qb->setParameter('store', $store);
        }
        $qb->setParameter('from', $this->dh->firstDayOfMonth);
        $qb->setParameter('to', $this->dh->currentDay);

        return $qb->getQuery()->getResult()[0][1];
    }

    /**
     * @param string|null $storeId
     * @return mixed[]
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getTodaySaleReturns(?string $storeId = null)
    {
        $stmt = $this->getEntityManager()->getConnection()
            ->prepare(
            'SELECT SUM(amt_sum) as "amtSaleReturns" 
                FROM sale_return 
                    LEFT JOIN retail_shift rs on sale_return.retail_shift_uuid = rs.id
                    LEFT JOIN cashbox cb on rs.cashbox_uuid = cb.id
                WHERE sale_return.created_at > CURRENT_DATE
                '.($storeId ? " AND cb.store_uuid = '$storeId' " : '').';'
            )
        ;
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * @param \DateTime|null $from
     * @param \DateTime|null $to
     * @return Sale[]
     * @throws \Exception
     */
    public function findByDateTimeInterval(?\DateTime $from, ?\DateTime $to)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select(['s'])->from('App:Sale', 's');
        if ($from) {
            $qb->andWhere('s.createdAt >= :from');
            $qb->setParameter('from', $from->setTimezone(new \DateTimeZone('UTC')));
        }
        if ($to) {
            $qb->andWhere('s.createdAt <= :to');
            $qb->setParameter('to', $to->setTimezone(new \DateTimeZone('UTC')));
        }
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    /**
     * @param Sale $sale
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Sale $sale)
    {
        $this->_em->persist($sale);
        $this->_em->flush();
    }

    /**
     * @param array $categories
     * @param int $daysCount
     * @param int $top
     * @return mixed
     */
    public function getTopProductSales(array $categories, $daysCount = 14, $top = 50)
    {
        $productsTop = $this->getProductsTop($daysCount, $top, $categories);
        $result = [];
        foreach ($productsTop as $product) {
            $result["{$product['name']}({$product['id']})"] = $this->getProductRevenueReport($product['id'], $daysCount);
        }

        return $result;
    }

    public function getProductRevenueReport(int $productId, int $daysCount): array
    {
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select("DATE_FORMAT(s.createdAt, 'YYYY-mm-dd') Day, SUM(ai.price * ai.qty) SumPrice")
            ->from('App:Sale', 's')
            ->join('s.act', 'a')
            ->join('a.actInvoices', 'ai')
            ->join('ai.product', 'p')
        ;
        $qb->andWhere('s.createdAt >= :from');
        $qb->andWhere('s.createdAt <= :to');
        $qb->andWhere('p.id = :product');

        $qb->groupBy('Day');

        $qb->setParameter('from', $this->dh->dayAgo($daysCount + 1));
        $qb->setParameter('to', $this->dh->currentDay);
        $qb->setParameter('product', $productId);
        $queryResult = [];
        foreach ($qb->getQuery()->getArrayResult() as $row) {
            $queryResult[$row['Day']] = $row['SumPrice'];
        }
        $result = [];
        for ($day = 1; $day <= $daysCount; $day++) {
            $dayFormat = $this->dh->dayAgo($day)->format('Y-m-d');
            $result[$dayFormat] = $queryResult[$dayFormat] ?? 0;
        }

        return $result;
    }

    public function getProductsTop(int $daysCount, int $top, array $categories): array
    {
        $nestedCategories = $this->categoryHelper->getNestedMultiple(array_map(function(int $categoryId) {
            return new CategoryArray($categoryId, $this->_em);
        }, $categories));

        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select(['p.id', 'p.name', 'SUM(ai.price * ai.qty) SaleSum'])
            ->from('App:Sale', 's')
            ->join('s.act', 'a')
            ->join('a.actInvoices', 'ai')
            ->join('ai.product', 'p')
        ;
        $qb->andWhere('s.createdAt >= :from');
        $qb->andWhere('s.createdAt <= :to');
        $qb->andWhere('p.category IN(:categories)');

        $qb->groupBy('p.id');
        $qb->orderBy('SaleSum', 'DESC');
        $qb->setMaxResults($top);

        $qb->setParameter('from', $this->dh->dayAgo($daysCount+1));
        $qb->setParameter('to', $this->dh->currentDay);
        $qb->setParameter('categories', array_map(function(CategoryInterface $category) {
            return $category->getId();
        }, $nestedCategories));

        $result = $qb->getQuery()->getArrayResult();

        return $result;
    }

    /**
     * @return Sale[]
     */
    public function findByLastWeek()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select(['s'])
            ->from('App:Sale', 's')
        ;
        $qb->andWhere('s.createdAt >= :from');
        $qb->orderBy('s.createdAt');
        $qb->setParameter('from', $this->dh->dayAgo(7));

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $offset
     * @param Product $product
     * @return int|null
     * @throws
     */
    public function countOfWeek(int $offset, Product $product): ?int
    {
        if ($offset < 1) {
            throw new \Exception('offset < 1 in countOfWeek method');
        }
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->from('App:Sale', 's')
            ->select(['SUM(ai.qty)'])
            ->join('s.act', 'a')
            ->join('a.actInvoices', 'ai')
            ->join('ai.product', 'p')
        ;
        $to = $offset-1;
        $qb
            ->where('s.createdAt >= :from')
            ->andWhere('s.createdAt <= :to')
            ->andWhere('p = :product')
            ->setParameter('from', (new \DateTime("-$offset week"))->setTimezone(new \DateTimeZone('UTC')))
            ->setParameter('to', (new \DateTime("-$to week"))->setTimezone(new \DateTimeZone('UTC')))
            ->setParameter('product', $product)
        ;

        return $qb->getQuery()->getResult()[0][1] ?? null;
    }

    /**
     * @param Product $product
     * @return int|null
     * @throws
     */
    public function countOfLastMonth(Product $product): ?int
    {
        $qb = $this->_em->createQueryBuilder();
        $qb
            ->select(['SUM(ai.qty)'])
            ->from('App:Sale', 's')
            ->join('s.act', 'a')
            ->join('a.actInvoices', 'ai')
            ->join('ai.product', 'p')
        ;
        $qb
            ->where('s.createdAt >= :from')
            ->andWhere('p = :product')
            ->setParameter('from', (new \DateTime('-1 month'))->setTimezone(new \DateTimeZone('UTC')))
            ->setParameter('product', $product)
        ;

        return $qb->getQuery()->getResult()[0][1] ?? null;
    }

    /**
     * @param Product $product
     * @param \DateTime $date
     * @return int|null
     */
    public function countOfDay(Product $product, \DateTime $date)
    {
        $qb = $this->createQueryBuilder('s')
            ->select('SUM(ai.qty)')
            ->join('s.act', 'a')
            ->join('a.actInvoices', 'ai')
            ->join('ai.product', 'p')
            ->andWhere('p = :product')
            ->andWhere('a.dateAct = :date')
            ->setParameter('product', $product->getId())
            ->setParameter('date', $date->setTimezone(new \DateTimeZone('UTC'))->format('Y-m-d'))
        ;

        return $qb->getQuery()->getResult()[0][1] ?? null;
    }

    /**
     * @param Product $product
     * @param int $dayCount
     * @return array
     * @throws
     */
    public function dailySales(Product $product, int $dayCount): array
    {
        if (self::$dailySales[$product->getId()][$dayCount] ?? false) {
            return self::$dailySales[$product->getId()][$dayCount];
        }
        $daysSales = $this->createQueryBuilder('s')
            ->select('SUM(ai.qty) Qty, DATE_FORMAT(s.createdAt, \'YYYY-mm-dd\') Day')
            ->join('s.act', 'a')
            ->join('a.actInvoices', 'ai')
            ->join('ai.product', 'p')
            ->andWhere('p = :product')
            ->andWhere('s.createdAt >= :from')
            ->andWhere('s.createdAt <= :to')
            ->setParameter('product', $product->getId())
            ->setParameter('from', $this->dh->dayAgo($dayCount, true))
            ->setParameter('to', $this->dh->currentDay)
            ->groupBy('Day')

            ->getQuery()
            ->getResult()
        ;
        foreach ($daysSales as $daySales) {
            $daysSalesKeys[$daySales['Day']] = $daySales['Qty'];
        }
        for ($i = $dayCount; $i > 0; $i--) {
            $key = $this->dh->dayAgo($i)->setTimezone((new\DateTime())->getTimezone())->format('Y-m-d');
            $result[] = $daysSalesKeys[$key] ?? 0;
            $dayCount--;
        }

        return self::$dailySales[$product->getId()][$dayCount] = $result ?? [];
    }

    /**
     * @param CategoryInterface[] $categories
     * @param \DateTime|null $minDate
     * @param \DateTime|null $maxDate
     * @return int
     * @throws
     */
    public function salesForCategory(array $categories, ?\DateTime $minDate, ?\DateTime $maxDate): int
    {
        $qb = $this->createQueryBuilder('s')
            ->select('SUM(ai.qty)')
            ->join('s.act', 'a')
            ->join('a.actInvoices', 'ai')
            ->join('ai.product', 'p')
        ;
        if ($categories) {
            $qb->where('p.category IN(:categories)');
        }
        if ($minDate) {
            $qb->andWhere('s.createdAt >= :minDate');
        }
        if ($maxDate) {
            $qb->andWhere('s.createdAt <= :maxDate');
        }
        if ($categories) {
            $qb->setParameter('categories', array_map(function (CategoryInterface $category) {
                return $category->getId();
            }, $categories));
        }
        if ($minDate) {
            $qb->setParameter('minDate', $minDate->setTimezone(new \DateTimeZone('utc')));
        }
        if ($maxDate) {
            $qb->setParameter('maxDate', $maxDate->setTimezone(new \DateTimeZone('utc')));
        }

        return $qb->getQuery()->getSingleScalarResult() ?? 0;
    }
}
