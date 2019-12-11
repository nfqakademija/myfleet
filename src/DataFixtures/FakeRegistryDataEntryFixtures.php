<?php

namespace App\DataFixtures;

use App\Entity\FakeRegistryDataEntry;
use App\Entity\Vehicle;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FakeRegistryDataEntryFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            VehicleFixtures::class,
        ];
    }

    public function load(ObjectManager $manager)
    {
        $startTime = new Datetime('-2 days');
        $endTime = new Datetime('+5 days');

        $i = 0;
        while ($this->hasReference('vehicle-' . $i)) {
            /** @var Vehicle $vehicle */
            $vehicle = $this->getReference('vehicle-' . $i);
            $vin = $vehicle->getVin();

            if (is_null($vin)) {
                continue;
            }

            /** @var DateTime $technicalInspectionValidTill */
            $technicalInspectionValidTill = $vehicle->getFirstRegistration();
            $currentYear = (new DateTime())->format('Y');
            while ($technicalInspectionValidTill->format('Y') <= $currentYear) {
                $technicalInspectionValidTill->modify('+2 years');

                $currentTime = clone $startTime;
                while ($currentTime <= $endTime) {
                    $status = (
                    $currentTime > $technicalInspectionValidTill
                        ? Vehicle::STATUS_SUSPENDED
                        : Vehicle::STATUS_REGISTERED
                    );
                    $isInsured = (bool)(10 >= mt_rand(1, 12));
                    $isPoliceSearching = (bool)(10 < mt_rand(1, 12));
                    $isAllowedDriving = (Vehicle::STATUS_REGISTERED === $status && $isInsured && !$isPoliceSearching);

                    $fakeRegistryDataEntry = new FakeRegistryDataEntry();
                    $fakeRegistryDataEntry->setVin($vin);
                    $fakeRegistryDataEntry->setStatus($status);
                    $fakeRegistryDataEntry->setTechnicalInspectionValidTill($technicalInspectionValidTill);
                    $fakeRegistryDataEntry->setIsInsured($isInsured);
                    $fakeRegistryDataEntry->setIsPoliceSearching($isPoliceSearching);
                    $fakeRegistryDataEntry->setIsAllowedDriving($isAllowedDriving);
                    $fakeRegistryDataEntry->setPublishedAt(clone $currentTime);

                    $manager->persist($fakeRegistryDataEntry);

                    $currentTime->modify('+1 day');
                }
                $manager->flush();
            }
            $i++;
        }
    }
}
