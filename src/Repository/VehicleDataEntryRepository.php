<?php

namespace App\Repository;

use App\Entity\Vehicle;
use App\Entity\VehicleDataEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use phpDocumentor\Reflection\Types\Collection;

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
     * @param int $limit
     * @return VehicleDataEntry[]|null
     */
    public function getLastEntries(Vehicle $vehicle, $limit = 100)
    {
        return $this->findBy(
            ['vehicle' => $vehicle],
            ['eventTime' => 'DESC'],
            $limit
        );
    }

    /**
     * @param Vehicle $vehicle
     * @return VehicleDataEntry|null
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

    /**
     * @param Vehicle $vehicle
     * @param int $startId
     * @param int $maxResults
     * @return QueryBuilder
     */
    public function findByVehicleTillThisMoment(Vehicle $vehicle, int $startId = 0, int $maxResults = 200)
    {
        $query = $this->createQueryBuilder('v')
            ->andWhere('v.vehicle = :vehicle')
            ->setParameter('vehicle', $vehicle);

        if (is_int($startId) && 0 < $startId) {
            $query->andWhere('v.id > :startId')
                ->setParameter('startId', $startId);
        }

        $query->setMaxResults($maxResults)
            ->orderBy('v.id', 'DESC')
            ->getQuery()
            ->getResult()
        ;

        return $query;
    }
}
