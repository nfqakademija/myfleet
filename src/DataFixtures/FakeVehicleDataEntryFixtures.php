<?php

namespace App\DataFixtures;

use App\Entity\FakeVehicleDataEntry;
use App\Entity\Vehicle;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class FakeVehicleDataEntryFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $startTime = new Datetime('-12 hours');
        $endTime = new Datetime('+2 days');
        $repository = $this->getDoctrine()->getRepository(Vehicle::class);

        for ($i = 1; $i <= 3; $i++) {
            // Prepare data for:
            $vin = AppFixtures::VINS[($i - 1)];
            $vehicle = $repository->findOneBy(['vinCode' => $vin]);
            $startData = [
                'vin' => $vin,
                // Rinktinės 5, Vilnius
                'latitude' => 54.693308,
                'longitude' => 25.289299,
                // Average KM: per year 25200 => per month 2100 => per day 70
                'mileage' => (int)($vehicle->getFirstRegistration()->diff($startTime)->format('%a') * 70),
                'eventTime' => $startTime,
            ];
            $currentTime = clone $startTime;
            while ($currentTime <= $endTime) {
                if ($currentTime == $startTime) {
                    $lastEntry = $startData;
                    $currentTime->modify('+30 seconds');
                } else {
                    $currentTime->modify('+30 seconds');

                    $radius = rand(0, 2); // in miles

                    $lng_min = $lastEntry['longitude'] - $radius / abs(cos(deg2rad($lastEntry['latitude'])) * 111);
                    $lng_max = $lastEntry['longitude'] + $radius / abs(cos(deg2rad($lastEntry['latitude'])) * 111);
                    $lat_min = $lastEntry['latitude'] - ($radius / 111);
                    $lat_max = $lastEntry['latitude'] + ($radius / 111);

                    $longitude = (($lng_max - $lng_min) / 10 * mt_rand(1, 9)) + $lng_min;
                    $latitude = (($lat_max - $lat_min) / 10 * mt_rand(1, 9)) + $lat_min;

                    /* *
                    $angle = deg2rad(mt_rand(0, 359));
                    $pointRadius = mt_rand(0, 2);
                    $point = [
                        'latitude' => sin($angle) * $pointRadius,
                        'longitude' => cos($angle) * $pointRadius
                    ];
                    * */
                    $mileage = sqrt(
                        pow($latitude - $lastEntry['latitude'], 2)
                        + pow($longitude - $lastEntry['longitude'], 2)
                    );

                    $lastEntry = [
                        'vin' => $vin,
                        'latitude' => $latitude,//$point['latitude'],
                        'longitude' => $longitude,//$point['longitude'],
                        'mileage' => (int)$mileage,
                        'eventTime' => $currentTime,
                    ];
                }
                $entries[$vin][] = $lastEntry;
            }

            if (isset($entries[$vin])) {
                foreach ($entries[$vin] as $row) {
                    $fakeVehicleDataEntry = new FakeVehicleDataEntry();
                    $fakeVehicleDataEntry->setVin($row['vin']);
                    $fakeVehicleDataEntry->setLatitude($row['latitude']);
                    $fakeVehicleDataEntry->setLongitude($row['longitude']);
                    $fakeVehicleDataEntry->setMileage($row['mileage']);
                    $fakeVehicleDataEntry->setEventTime($row['eventTime']);

                    $manager->persist($fakeVehicleDataEntry);
                }
            }
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            AppFixtures::class,
        ];
    }
}
