<?php

namespace App\Repository;

use App\Entity\RegistryDataEntry;
use App\Entity\Vehicle;
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

    /**
     * @param Vehicle $vehicle
     * @return RegistryDataEntry|null
     */
    public function getLastEntry(Vehicle $vehicle)
    {
        return $this->findOneBy(
            ['vehicle' => $vehicle],
            ['eventTime' => 'DESC']
        );
    }

    /**
     * @param Vehicle $vehicle
     * @return RegistryDataEntry|null
     */
    public function getPreviousRecord(Vehicle $vehicle): ?RegistryDataEntry
    {
        $result =  $this->findBy(
            ['vehicle' => $vehicle],
            ['eventTime' => 'DESC'],
            1,
            1
        );

        return ($result[0] ?? null);
    }
}
