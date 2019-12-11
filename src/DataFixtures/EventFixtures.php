<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\User;
use App\Entity\Vehicle;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            VehicleFixtures::class,
        ];
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager)
    {
        /** @var User $user */
        $user = $this->getReference('user-manager');

        $i = 1;
        while ($this->hasReference('vehicle-' . $i)) {
            /** @var Vehicle $vehicle */
            $vehicle = $this->getReference('vehicle-' . $i);

            $eventCreatedAt = new DateTime();
            $eventCreatedAt->modify('today -' . mt_rand(1, 10) . ' days');

            $createRecord = (bool)mt_rand(0, 1);

            if ($createRecord) {
                $event = new Event();
                $event->setVehicle($vehicle);
                $event->setUser($user);
                $event->setCreatedAt(clone $eventCreatedAt);
                $event->setDescription('Padangos pakeistos į žiemines');

                $manager->persist($event);
                $manager->flush();
            }

            $i++;
        }
    }
}
