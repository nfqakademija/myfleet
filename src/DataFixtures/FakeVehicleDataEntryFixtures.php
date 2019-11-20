<?php

namespace App\DataFixtures;

use App\Entity\FakeVehicleDataEntry;
use App\Entity\Vehicle;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FakeVehicleDataEntryFixtures extends Fixture implements DependentFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function load(ObjectManager $manager)
    {
        $startTime = new Datetime('-12 hours');
        $endTime = new Datetime('+2 days');
        $earthRadius = 6371000;

        $em = $this->container->get('doctrine')->getManager();
        $repository = $em->getRepository(Vehicle::class);

        for ($i = 1; $i <= 3; $i++) {
            $vin = AppFixtures::VINS[($i - 1)];
            $vehicle = $repository->findOneBy(['vinCode' => $vin]);
            $mileage = (int)($vehicle->getFirstRegistration()->diff($startTime)->format('%a') * 70);
            $lastEntry = [
                'vin' => $vin,
                // Rinktinės 5, Vilnius
                'latitude' => 54.693308,
                'longitude' => 25.289299,
                // Average KM: per year 25200 => per month 2100 => per day 70
                'mileage' => $mileage,
                'eventTime' => $startTime->format('Y-m-d H:i:s'),
            ];
            $entries[$vin][] = $lastEntry;

            $currentTime = clone $startTime;
            while ($currentTime <= $endTime) {
                $radius = mt_rand() / mt_getrandmax();

                $lng_min = $lastEntry['longitude'] - $radius / abs(cos(deg2rad($lastEntry['latitude'])) * 111);
                $lng_max = $lastEntry['longitude'] + $radius / abs(cos(deg2rad($lastEntry['latitude'])) * 111);
                $lat_min = $lastEntry['latitude'] - ($radius / 111);
                $lat_max = $lastEntry['latitude'] + ($radius / 111);

                $longitude = (($lng_max - $lng_min) / 10 * mt_rand(4, 9)) + $lng_min;
                $latitude = (($lat_max - $lat_min) / 10 * mt_rand(4, 9)) + $lat_min;

                $latitudeFrom = deg2rad($lastEntry['latitude']);
                $latitudeTo = deg2rad($latitude);
                $longitudeFrom = deg2rad($lastEntry['longitude']);
                $longitudeTo = deg2rad($longitude);

                $latitudeDelta = $latitudeTo - $latitudeFrom;
                $longitudeDelta = $longitudeTo - $longitudeFrom;

                $angle = 2 * asin(
                    sqrt(pow(sin($latitudeDelta / 2), 2) +
                        cos($latitudeFrom) * cos($latitudeTo) * pow(sin($longitudeDelta / 2), 2)));
                $mileage += ($angle * $earthRadius);
                $mileage = number_format($mileage, 0, '.', '');

                $lastEntry = [
                    'vin' => $vin,
                    'latitude' => $latitude,//$point['latitude'],
                    'longitude' => $longitude,//$point['longitude'],
                    'mileage' => $mileage,
                    'eventTime' => $currentTime->modify('30 seconds')->format('Y-m-d H:i:s'),
                ];

                $entries[$vin][] = $lastEntry;
            }

            if (isset($entries[$vin])) {
                foreach ($entries[$vin] as $row) {
                    $fakeVehicleDataEntry = new FakeVehicleDataEntry();
                    $fakeVehicleDataEntry->setVin($row['vin']);
                    $fakeVehicleDataEntry->setLatitude($row['latitude']);
                    $fakeVehicleDataEntry->setLongitude($row['longitude']);
                    $fakeVehicleDataEntry->setMileage($row['mileage']);
                    $fakeVehicleDataEntry->setEventTime(new DateTime($row['eventTime']));

                    $manager->persist($fakeVehicleDataEntry);
                }
                $manager->flush();
            }
        }
    }

    public function getDependencies()
    {
        return [
            AppFixtures::class,
        ];
    }
}
