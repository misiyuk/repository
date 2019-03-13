<?php

namespace App\Repository;

use App\Entity\Act;
use App\Entity\ActInvoice;
use App\Entity\Batch;
use App\Entity\Purchase;
use App\Entity\Relocation;
use App\Entity\Sale;
use App\Entity\SaleReturn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Act|null find($id, $lockMode = null, $lockVersion = null)
 * @method Act|null findOneBy(array $criteria, array $orderBy = null)
 * @method Act[]    findAll()
 * @method Act[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Act::class);
    }

    /**
     * @return Act[]
     */
    public function findBySale()
    {
        $saleRepository = $this->_em->getRepository(Sale::class);
        $sales = $saleRepository->findAll();

        return $this->createQueryBuilder('a')
            ->where('a.id IN(:sales)')
            ->setParameter('sales', array_map(function (Sale $sale) {
                return $sale->getAct()->getId();
            }, $sales))
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Act[]
     */
    public function findBySupply()
    {
        $batchRepository = $this->_em->getRepository(Batch::class);
        $supllies = $batchRepository->findBySupply();

        return $this->createQueryBuilder('a')
            ->where('a.id IN(:supplies)')
            ->setParameter('supplies', array_map(function (Batch $batch) {
                return $batch->getAct()->getId();
            }, $supllies))
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Act[]
     */
    public function findBySaleReturns()
    {
        $saleReturnRepository = $this->_em->getRepository(SaleReturn::class);
        $saleReturns = $saleReturnRepository->findAll();

        return $this->createQueryBuilder('a')
            ->where('a.id IN(:saleReturns)')
            ->setParameter('saleReturns', array_map(function (SaleReturn $saleReturn) {
                return $saleReturn->getActId();
            }, $saleReturns))
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Act[]
     */
    public function findByRelocationFrom()
    {
        $relocationRepository = $this->_em->getRepository(Relocation::class);
        $relocations = [];
        array_map(function (Relocation $relocation) use (&$relocations) {
            $relocations[] = $relocation->getFromAct()->getId();
        }, $relocationRepository->findAll());

        return $this->createQueryBuilder('a')
            ->where('a.id IN(:relocations)')
            ->setParameter('relocations', $relocations)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Act[]
     */
    public function findByRelocationTo()
    {
        $relocationRepository = $this->_em->getRepository(Relocation::class);
        $relocations = [];
        array_map(function (Relocation $relocation) use (&$relocations) {
            $relocations[] = $relocation->getToAct()->getId();
        }, $relocationRepository->findAll());

        return $this->createQueryBuilder('a')
            ->where('a.id IN(:relocations)')
            ->setParameter('relocations', $relocations)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Act[]
     */
    public function findByPurchase()
    {
        $purchaseRepository = $this->_em->getRepository(Purchase::class);
        $purchases = $purchaseRepository->findAll();

        return $this->createQueryBuilder('a')
            ->where('a.id IN(:purchases)')
            ->setParameter('purchases', array_map(function (Purchase $purchase) {
                return $purchase->getAct()->getId();
            }, $purchases))
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Act[]
     */
    public function findByEnter()
    {
        $saleRepository = $this->_em->getRepository(Sale::class);
        $sales = $saleRepository->findAll();
        $batchRepository = $this->_em->getRepository(Batch::class);
        $supllies = $batchRepository->findBySupply();
        $saleReturnRepository = $this->_em->getRepository(SaleReturn::class);
        $saleReturns = $saleReturnRepository->findAll();
        $relocationRepository = $this->_em->getRepository(Relocation::class);
        $relocations = [];
        array_map(function (Relocation $relocation) use (&$relocations) {
            $relocations[] = $relocation->getFromAct()->getId();
            $relocations[] = $relocation->getToAct()->getId();
        }, $relocationRepository->findAll());
        $purchaseRepository = $this->_em->getRepository(Purchase::class);
        $purchases = $purchaseRepository->findAll();

        return $this->createQueryBuilder('a')
            ->join('a.batches', 'b')
            ->where('a.id NOT IN(:sales)')
            ->andWhere('a.id NOT IN(:supplies)')
            ->andWhere('a.id NOT IN(:saleReturns)')
            ->andWhere('a.id NOT IN(:relocations)')
            ->andWhere('a.id NOT IN(:purchases)')

            ->setParameter('sales', array_map(function (Sale $sale) {
                return $sale->getAct()->getId();
            }, $sales))
            ->setParameter('supplies', array_map(function (Batch $batch) {
                return $batch->getAct()->getId();
            }, $supllies))
            ->setParameter('saleReturns', array_map(function (SaleReturn $saleReturn) {
                return $saleReturn->getActId();
            }, $saleReturns))
            ->setParameter('relocations', $relocations)
            ->setParameter('purchases', array_map(function (Purchase $purchase) {
                return $purchase->getAct()->getId();
            }, $purchases))

            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param $actId
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function cancelExecute($actId)
    {
        $entityManager = $this->_em;
        $act = $this->find($actId);
        $invoices = $act->getActInvoices();

        /** @var ActInvoice $invoice */
        foreach ($invoices as $invoice)
        {
            $product = $invoice->getProduct();
            $oldCostSum = $product->getQty()*$product->getCostPrice();
            $invoiceCostSum = $invoice->getQty()*$invoice->getCostPrice();
            $product->setQty($product->getQty() - $invoice->getQty());
            $product->setCostPrice(($oldCostSum - $invoiceCostSum) / ($product->getQty()));
            $entityManager->persist($product);
        }

        $entityManager->flush();
    }

    /**
     * @param Act $act
     * @throws \Doctrine\ORM\ORMException
     */
    public function save(Act $act)
    {
        $this->_em->persist($act);
        $this->_em->flush();
    }
}
