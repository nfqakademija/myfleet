<?php

namespace App\Repository;

use App\Dto\FiltersData;
use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleRepository extends ServiceEntityRepository
{
    /**
     * VehicleRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    /**
     * @param FiltersData $filtersData
     * @return Collection
     */
    public function filterVehicles(FiltersData $filtersData)
    {
        $query = $this->createQueryWithFiltersApplied($filtersData);

        $query
            ->setMaxResults($filtersData->getPageSize())
            ->setFirstResult($filtersData->getPageSize() * ($filtersData->getPage()-1));

        return $query->getQuery()->getResult();
    }

    /**
     * @param FiltersData $filtersData
     * @return QueryBuilder
     */
    protected function createQueryWithFiltersApplied(FiltersData $filtersData): QueryBuilder
    {
        $query = $this->createQueryBuilder('v');

        if ($filtersData->getVehicleType()) {
            $query->andWhere('v.type = :type')
                ->setParameter('type', $filtersData->getVehicleType());
        }

        if ($filtersData->getPlateNumberPart()) {
            $query->andWhere('v.plateNumber LIKE :plateNumber')
                ->setParameter('plateNumber', '%' . $filtersData->getPlateNumberPart() . '%');
        }

        return $query;
    }

    /**
     * @param FiltersData $filtersData
     * @return int
     */
    public function countMatchingVehicles(FiltersData $filtersData): int
    {
        try {
            $query = $this->createQueryWithFiltersApplied($filtersData);

            return (int)count($query->getQuery()->getResult());
        } catch (\Throwable $e) {
            return 0;
        }
    }
}
