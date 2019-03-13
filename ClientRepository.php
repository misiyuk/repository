<?php

namespace App\Repository;

use App\Entity\ActInvoice;
use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Client::class);
    }
    public function findAllSearch($search)
    {
        $words = explode(" ", $search);
        foreach($words as &$word)
        {
            $word = $word.':*';
        }

        return $this->getEntityManager()
            ->createQuery(
                "SELECT p FROM App:Client p WHERE TS_MATCH_OP(TO_TSVECTOR(p.firstName), TO_TSQUERY('".implode(',', $words)."')) = true 
                    OR TS_MATCH_OP(TO_TSVECTOR(p.lastName), TO_TSQUERY('".implode(',', $words)."')) = true
                    OR TS_MATCH_OP(TO_TSVECTOR(p.fatherName), TO_TSQUERY('".implode(',', $words)."')) = true
                    OR TS_MATCH_OP(TO_TSVECTOR(p.mobilePhone), TO_TSQUERY('".implode(',', $words)."')) = true"
            )
            ->getResult();
    }

    /**
     * @param $clientId
     * @return Client|null
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateSalesStat($clientId)
    {
        $client = $this
            ->find($clientId);
        $amtSales = 0;
        $sumSales = 0;

        foreach ($client->getSales() as $sale)
        {
            $amtSales++;
            $sumSale = 0;
            /** @var ActInvoice $item */
            foreach($sale->getAct()->getActInvoices() as $item)
            {
                $sumSale += $item->getQty()*$item->getPrice()*((100 - $item->getDiscount()) / 100);
            }
            $sumSales += $sumSale;
        }

        $client->setAmtSales($amtSales);
        $client->setAvgSale($sumSales / $amtSales);

        $this->getEntityManager()->persist($client);
        $this->getEntityManager()->flush();
        return $client;
    }
}
