<?php

namespace App\Repository;

use App\Dto\FiltersData;
use App\Entity\Vehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Vehicle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Vehicle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Vehicle[]    findAll()
 * @method Vehicle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VehicleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Vehicle::class);
    }

    public function filterVehicles(FiltersData $filtersData)
    {
        $query = $this->createQueryBuilder('v');

        if ($filtersData->getVehicleType()) {
            $query->andWhere('v.type = :type')
                ->setParameter('type', $filtersData->getVehicleType());
        }

        if ($filtersData->getRegistrationPlateNumberPart()) {
            $query->andWhere('v.registrationPlateNumber LIKE :registrationPlateNumber')
                ->setParameter('registrationPlateNumber', '%' . $filtersData->getRegistrationPlateNumberPart() . '%');
        }

        return $query->getQuery()->getResult();
    }

    public function countMatchingVehicles(FiltersData $filtersData)
    {
        return 20;
    }
}
