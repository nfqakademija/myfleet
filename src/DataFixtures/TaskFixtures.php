<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use App\Entity\Vehicle;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;

class TaskFixtures extends Fixture implements DependentFixtureInterface
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
     *
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        /** @var User $user */
        $user = $this->getReference('user-manager');

        $i = 0;
        while ($this->hasReference('vehicle-' . $i)) {
            /** @var Vehicle $vehicle */
            $vehicle = $this->getReference('vehicle-' . $i);

            $hasChangedTyres = (bool)mt_rand(0, 1);

            $taskStartAt = new DateTime();
            $taskStartAt->modify('today -' . mt_rand(1, 10) . ' days');

            $task = new Task();
            $task->setVehicle($vehicle);
            $task->setUser($user);
            $task->setStartAt($taskStartAt);
            $task->setDescription('Pakeisti padangas į žiemines');
            $task->setIsCompleted($hasChangedTyres);

            $manager->persist($task);
            $manager->flush();

            $i++;
        }
    }
}
