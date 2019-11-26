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
    /**
     * RegistryDataEntryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RegistryDataEntry::class);
    }
}
