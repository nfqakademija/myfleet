<?php

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
     *
     * @return InstantNotification|null
     */
    public function findUnsentUserNotification(UserInterface $user)
    {
        return $this->findOneBy(
            [
                'user' => $user,
                'isSent' => false
            ],
            ['eventTime' => 'ASC']
        );
    }

    /**
     * @param UserInterface $user
     * @param int $maxAgeInMinutes
     * @param int $maxResults
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function findFreshUnsentNotificaions(UserInterface $user, int $maxAgeInMinutes = 3, int $maxResults = 1)
    {
        $newerThan = new DateTime('-' . $maxAgeInMinutes . ' minutes');

        return $this->createQueryBuilder('in')
            ->andWhere('in.user = :user')
            ->setParameter('user', $user)
            ->andWhere('in.eventTime > :newerThan')
            ->setParameter('newerThan', $newerThan)
            ->setMaxResults($maxResults)
            ->orderBy('in.eventTime', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
}
