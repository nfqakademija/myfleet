<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Vehicle;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $i = 1;
        while ($this->hasReference('vehicle-' . $i)) {
            /** @var Vehicle $vehicle */
            $vehicle = $this->getReference('vehicle-' . $i);

            $eventCreatedAt = new DateTime();
            $eventCreatedAt->modify('today -' . mt_rand(1, 10) . ' days');

            $addRecord = (bool)mt_rand(0, 1);

            if ($addRecord) {
                $event = new Event();
                $event->setVehicle($vehicle);
                $event->setCreatedAt(clone $eventCreatedAt);
                $event->setDescription('Padangos pakeistos į žiemines');

                $manager->persist($event);
                $manager->flush();
            }

            $i++;
        }
    }

    public function getDependencies()
    {
        return [
            VehicleFixtures::class,
        ];
    }
}
