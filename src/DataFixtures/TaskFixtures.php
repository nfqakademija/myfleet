<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\Vehicle;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TaskFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $i = 1;
        while ($this->hasReference('vehicle-' . $i)) {
            /** @var Vehicle $vehicle */
            $vehicle = $this->getReference('vehicle-' . $i);

            $hasChangedTyres = (bool)mt_rand(0, 1);

            $taskStartAt = new DateTime();
            $taskStartAt->modify('today -' . mt_rand(1, 10) . ' days');

            $task = new Task();
            $task->setVehicle($vehicle);
            $task->setStartAt($taskStartAt);
            $task->setDescription('Pakeisti padangas į žiemines');
            $task->setIsCompleted($hasChangedTyres);

            $manager->persist($task);
            $manager->flush();

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
