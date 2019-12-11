<?php

namespace App\DataFixtures;

use App\Entity\ExpenseEntry;
use App\Entity\User;
use App\Entity\Vehicle;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ExpenseEntryFixtures extends Fixture implements DependentFixtureInterface
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
     * @throws Exception
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
                $expenseEntry = new ExpenseEntry();
                $expenseEntry->setVehicle($vehicle);
                $expenseEntry->setUser($user);
                $expenseEntry->setDescription('Padangų permontavimas ir balansavimas');
                $expenseEntry->setAmount(500);
                $expenseEntry->setCreatedAt($eventCreatedAt);

                $manager->persist($expenseEntry);
                $manager->flush();
            }

            $i++;
        }
    }
}
