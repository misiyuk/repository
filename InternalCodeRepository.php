<?php

namespace App\Repository;

use App\Entity\Form\Purchase\PurchaseProductForm;
use App\Entity\InternalCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method InternalCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method InternalCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method InternalCode[]    findAll()
 * @method InternalCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InternalCodeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, InternalCode::class);
    }

    /**
     * @param PurchaseProductForm[] $productForms
     * @return InternalCode[]
     */
    public function findByOtherProduct(array $productForms): array
    {
        $internalCodes = [];
        foreach ($productForms as $productForm) {
            $internalCode = $this->createQueryBuilder('ic')
                ->andWhere('ic.product != :product')
                ->andWhere('ic.value = :value')
                ->setParameter('product', $productForm->getProductEntity()->getId())
                ->setParameter('value', $productForm->getInternalCode())
                ->getQuery()
                ->getResult()
            ;
            if ($internalCode) {
                $internalCodes[] = reset($internalCode);
            }
        }

        return $internalCodes;
    }

    /**
     * @param string $value
     * @param int $product
     * @return InternalCode[]
     */
    public function findByConfirmedPurchase(string $value, ?int $product): ?array
    {
        return $this->createQueryBuilder('ic')
            ->join('ic.actInvoice', 'ai')
            ->join('ai.act', 'a')
            ->join('a.purchase', 'p')

            ->andWhere('p.confirmed = true')
            ->andWhere('ai.product = :product')
            ->andWhere('ic.value = :value')
            ->setParameter('value', $value)
            ->setParameter('product', $product)

            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return InternalCode[] Returns an array of InternalCode objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?InternalCode
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
