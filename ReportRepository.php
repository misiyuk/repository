<?php

namespace App\Repository;

use App\Entity\Report;
use App\Services\Report\QueryBuilderService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Report|null find($id, $lockMode = null, $lockVersion = null)
 * @method Report|null findOneBy(array $criteria, array $orderBy = null)
 * @method Report[]    findAll()
 * @method Report[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportRepository extends ServiceEntityRepository
{
    // /**

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Report::class);
    }

    /**
     * @param Report $report
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function customSql(Report $report): array
    {
        $query = (new QueryBuilderService($this->_em))->generateQuery($report);
        $connection = $this->_em->getConnection();
        $statement = $connection->prepare($query);
        $statement->execute();

        return $statement->fetchAll();
    }
}
