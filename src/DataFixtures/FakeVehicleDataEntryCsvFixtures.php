<?php

namespace App\DataFixtures;

use App\Entity\Vehicle;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class FakeVehicleDataEntryCsvFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }


    public function load(ObjectManager $manager)
    {
        $startTime = new Datetime('-2 hours');
        $endTime = new Datetime('+2 days');

        $csvData = file($this->kernel->getProjectDir() . '/src/DataFixtures/coordinates.csv');
        if (false === $csvData) {
            throw new \Exception('Cannot read file');
        }

        foreach ($csvData as $line => $string) {
            $string = trim($string);
            list($vehicleId, $latitude, $longitude) = explode(',', $string);
            $vehiclesData[$vehicleId][] = [$latitude, $longitude];
        }

        for ($i = 1; $i <= 3; $i++) {
            if (!$this->hasReference('vehicle-' . $i)) {
                continue;
            }
            /** @var Vehicle $vehicle */
            $vehicle = $this->getReference('vehicle-' . $i);
            $vin = $vehicle->getVin();

            if (is_null($vin)) {
                continue;
            }
            if (!isset($vehiclesData[$i])) {
                continue;
            }

            $vehicleData = $vehiclesData[$i];

            $prevLatitude = $prevLongitude = null;

            foreach ($vehicleData as $point) {
                if (null === $prevLatitude || null === $prevLongitude) {
                    $prevLatitude = $point[0];
                    $prevLongitude = $point[1];
                    continue;
                }

                $currentLatitude = $point[0];
                $currentLongitude = $point[1];

                // c^2 = a^2 + b^2
                $a = ($prevLatitude - $currentLatitude);
                $b = ($prevLongitude - $currentLongitude);
                $c = pow($a, 2) + pow($b, 2);
                $c = sqrt($c);
                $km = (float) number_format($c / 0.02, 3, '.', '');
                $seconds = number_format((1000 * $km) / (1000 / 60), 2, '.', '');

                echo $i . ' vehicle has moved ' . $km . ' km in ' . $seconds . ' s' . PHP_EOL;

                $prevLatitude = $point[0];
                $prevLongitude = $point[1];
            }


//            $fakeVehicleDataEntry = new FakeVehicleDataEntry();
//            $fakeVehicleDataEntry->setVin();
//            $fakeVehicleDataEntry->setLatitude();
//            $fakeVehicleDataEntry->setLongitude();
//            $fakeVehicleDataEntry->setMileage();
//            $fakeVehicleDataEntry->setEventTime();
//
//            $manager->persist($fakeVehicleDataEntry);
//            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            VehicleFixtures::class,
        ];
    }
}
