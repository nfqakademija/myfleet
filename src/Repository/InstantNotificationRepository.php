<?php

namespace App\Repository;

use App\Entity\InstantNotification;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
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
}
