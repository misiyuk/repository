<?php

namespace App\Repository;

use App\Entity\Client;
use App\Entity\ClientOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ClientOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method ClientOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method ClientOrder[]    findAll()
 * @method ClientOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientOrderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ClientOrder::class);
    }

    /**
     * @throws
     */
    public function findByCreating(Client $client): ?ClientOrder
    {
        $orders = $this->findBy([
            'client' => $client,
            'status' => ClientOrder::STATUSES['creating'],
        ]);
        if (\count($orders) > 1) {
            throw new \Exception('Exists '.\count($orders).' creating orders');
        }

        return $orders ? reset($orders) : null;
    }

    public function findByPayment(Client $client): ?ClientOrder
    {
        return $this->findOneBy([
            'client' => $client,
            'status' => ClientOrder::STATUSES['payment'],
        ]);
    }

    /**
     * @return ClientOrder[]|null
     */
    public function findByProfile(Client $client): ?array
    {
        return $this->findBy(['client' => $client], ['id' => 'ASC']);
    }

    /**
     * @throws
     */
    public function countByProfile(Client $client): int
    {
        return $this->createQueryBuilder('o')
            ->select('count(o.id)')
            ->andWhere('o.client = :client')
            ->setParameter('client', $client)
            ->getQuery()
            ->getSingleScalarResult() ?? 0
        ;
    }

    // /**
    //  * @return Order[] Returns an array of Order objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
