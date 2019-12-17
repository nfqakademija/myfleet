<?php

declare(strict_types=1);

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
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExpenseEntry::class);
    }
}
