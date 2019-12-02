<?php

namespace App\Repository;

use App\Entity\FakeRegistryDataEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FakeRegistryDataEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method FakeRegistryDataEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method FakeRegistryDataEntry[]    findAll()
 * @method FakeRegistryDataEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FakeRegistryDataEntryRepository extends ServiceEntityRepository
{
    /**
     * FakeRegistryDataEntryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FakeRegistryDataEntry::class);
    }

    /**
     * @param string $value
     * @return mixed
     */
    public function findByVinTillThisMoment(string $value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.vin = :vin')
            ->setParameter('vin', $value)
            ->andWhere('f.publishedAt <= :publishedAt')
            ->setParameter('publishedAt', date('Y-m-d H:i:s'))
            ->orderBy('f.publishedAt', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
            ;
    }
}
