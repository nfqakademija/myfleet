<?php

namespace App\Repository;

use App\Entity\ExpenseEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ExpenseEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExpenseEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExpenseEntry[]    findAll()
 * @method ExpenseEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpenseEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExpenseEntry::class);
    }

    // /**
    //  * @return ExpenseEntry[] Returns an array of ExpenseEntry objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExpenseEntry
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
