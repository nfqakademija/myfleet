<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\InstantNotification;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method InstantNotification|null find($id, $lockMode = null, $lockVersion = null)
 * @method InstantNotification|null findOneBy(array $criteria, array $orderBy = null)
 * @method InstantNotification[]    findAll()
 * @method InstantNotification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstantNotificationRepository extends ServiceEntityRepository
{
    /**
     * InstantNotificationRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InstantNotification::class);
    }

    /**
     * @param UserInterface $user
     * @param int $maxAgeInSeconds
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function findLastUnsentUserNotification(UserInterface $user, int $maxAgeInSeconds = 180)
    {
        $newerThan = new DateTime('-' . $maxAgeInSeconds . ' minutes');

        return $this->createQueryBuilder('i')
            ->andWhere('i.user = :user')
            ->andWhere('i.eventTime > :newerThan')
            ->andWhere('i.isSent = false')
            ->setParameter('user', $user)
            ->setParameter('newerThan', $newerThan)
            ->orderBy('i.eventTime', 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
