<?php

namespace App\Repository;

use App\Entity\FakeVehicleDataEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method FakeVehicleDataEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method FakeVehicleDataEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method FakeVehicleDataEntry[]    findAll()
 * @method FakeVehicleDataEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FakeVehicleDataEntryRepository extends ServiceEntityRepository
{
    /**
     * FakeVehicleDataEntryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FakeVehicleDataEntry::class);
    }

    public function findByVinTillThisMoment($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.vin = :vin')
            ->setParameter('vin', $value)
            ->andWhere('f.eventTime <= :eventTime')
            ->setParameter('eventTime', date('Y-m-d H:i:s'))
            ->orderBy('f.eventTime', 'DESC')
            ->setMaxResults(100)
            ->getQuery()
            ->getResult()
        ;
    }
}
