<?php

namespace App\Repository;

use App\Entity\RegistryDataEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method RegistryDataEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method RegistryDataEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method RegistryDataEntry[]    findAll()
 * @method RegistryDataEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RegistryDataEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegistryDataEntry::class);
    }

    // /**
    //  * @return RegistryDataEntry[] Returns an array of RegistryDataEntry objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RegistryDataEntry
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
