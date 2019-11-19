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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FakeVehicleDataEntry::class);
    }

    // /**
    //  * @return FakeVehicleDataEntry[] Returns an array of FakeVehicleDataEntry objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FakeVehicleDataEntry
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
