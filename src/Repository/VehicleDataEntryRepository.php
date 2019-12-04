<?php

namespace App\Repository;

use App\Entity\Vehicle;
use App\Entity\VehicleDataEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method VehicleDataEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method VehicleDataEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method VehicleDataEntry[]    findAll()
 * @method VehicleDataEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleDataEntryRepository extends ServiceEntityRepository
{
    /**
     * VehicleDataEntryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VehicleDataEntry::class);
    }

    /**
     * @param Vehicle $vehicle
     * @return mixed
     */
    public function getLastEntries(Vehicle $vehicle)
    {
        return $this->findBy(
            ['vehicle' => $vehicle],
            ['eventTime' => 'DESC'],
            100
        );
    }

    /**
     * @param Vehicle $vehicle
     * @return VehicleDataEntry|null
     */
    public function getPreviousRecord(Vehicle $vehicle): ?VehicleDataEntry
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
