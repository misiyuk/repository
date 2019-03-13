<?php

namespace App\Repository;

use App\Entity\Ean13Code;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Ean13Code|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ean13Code|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ean13Code[]    findAll()
 * @method Ean13Code[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Ean13CodeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ean13Code::class);
    }

    /**
     * @throws \Exception
     */
    public function getCode()
    {
        /** @var Ean13Code $code */
        $code = $this->find(1);
        if (!$code) {
            $code = Ean13Code::create(9000000000001);
        }

        return $code;
    }

    /**
     * @param Ean13Code $code
     * @throws \Exception
     */
    public function save(Ean13Code $code)
    {
        try {
            $this->_em->persist($code);
            $this->_em->flush();
        } catch (\Exception $e) {
            throw new \Exception('Can\'t save code');
        }
    }
}
