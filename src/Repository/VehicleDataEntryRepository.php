<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Vehicle;
use App\Entity\VehicleDataEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method VehicleDataEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method VehicleDataEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method VehicleDataEntry[]    findAll()
 * @method VehicleDataEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleDataEntryRepository extends ServiceEntityRepository
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VehicleDataEntry::class);
    }

    /**
     * @param Vehicle $vehicle
     * @param int $limit
     *
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
     *
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
     *
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
     * @param int $vehicleId
     * @param int $timestamp
     * @param int $maxResults
     *
     * @return mixed
     */
    public function findByVehicleTillThisMoment(int $vehicleId, int $timestamp = 0, int $maxResults = 100)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.vehicle = :vehicleId')
            ->setParameter('vehicleId', $vehicleId)
            ->andWhere('v.eventTime > :timestamp')
            ->setParameter('timestamp', $timestamp)
            ->setMaxResults($maxResults)
            ->orderBy('v.eventTime', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
