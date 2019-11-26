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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FakeRegistryDataEntry::class);
    }

    // /**
    //  * @return FakeRegistryDataEntry[] Returns an array of FakeRegistryDataEntry objects
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
    public function findOneBySomeField($value): ?FakeRegistryDataEntry
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
