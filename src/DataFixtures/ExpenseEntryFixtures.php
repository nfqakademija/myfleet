<?php

namespace App\DataFixtures;

use App\Entity\ExpenseEntry;
use App\Entity\Vehicle;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ExpenseEntryFixtures extends Fixture
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
                $expenseEntry = new ExpenseEntry();
                $expenseEntry->setVehicle($vehicle);
                $expenseEntry->setDescription('Padangų permontavimas ir balansavimas');
                $expenseEntry->setAmount(500);
                $expenseEntry->setCreatedAt($eventCreatedAt);

                $manager->persist($expenseEntry);
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
