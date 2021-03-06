<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\FakeVehicleDataEntry;
use App\Entity\Vehicle;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;
use Symfony\Component\HttpKernel\KernelInterface;

class FakeVehicleDataEntryFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @param KernelInterface $kernel
     */
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
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
        $startTime = new Datetime('-2 hours');
        $endTime = new Datetime('+2 days');

        $csvData = $this->loadData();
        if ($csvData === false) {
            return;
        }
        $vehiclesData = $this->transformData($csvData);

        foreach ($vehiclesData as $vehicleOrder => $vehicleData) {
            $i = $vehicleOrder - 1;

            if (!$this->hasReference('vehicle-' . $i)) {
                continue;
            }
            /** @var Vehicle $vehicle */
            $vehicle = $this->getReference('vehicle-' . $i);
            $vin = $vehicle->getVin();

            if (is_null($vin)) {
                continue;
            }
            if (is_null($vehicle->getFirstRegistration())) {
                continue;
            }

            $firstItem = 0;
            $currentItem = 0;
            $currentCycle = 0;
            $lastItem = (count($vehicleData) - 1);
            $incrementBy = 1;
            $mileage = (int)$vehicle->getFirstRegistration()
                ->diff((new DateTime('today')))
                ->format('%m');
            $mileage *= mt_rand(2000, 10000);

            $currentTime = clone $startTime;

            while ($currentItem <= $lastItem) {
                $mileage += $vehicleData[$currentItem][2];
                $currentTime->modify('+' . round($vehicleData[$currentItem][3]) . ' seconds');

                $fakeVehicleDataEntry = new FakeVehicleDataEntry();
                $fakeVehicleDataEntry->setVin($vin);
                $fakeVehicleDataEntry->setLatitude($vehicleData[$currentItem][0]);
                $fakeVehicleDataEntry->setLongitude($vehicleData[$currentItem][1]);
                $fakeVehicleDataEntry->setMileage((int)$mileage);
                $fakeVehicleDataEntry->setEventTime(clone $currentTime);

                $manager->persist($fakeVehicleDataEntry);

                $currentCycle++;
                if ($currentCycle % 25 === 0) {
                    $manager->flush();
                    $manager->clear();
                }

                if ($currentItem == $lastItem) {
                    $incrementBy *= -1;
                } elseif (0 > $incrementBy && $currentItem == $firstItem) {
                    $incrementBy *= -1;
                }
                $currentItem += $incrementBy;

                if ($currentTime > $endTime) {
                    break;
                }
            }
            $manager->flush();
            $manager->clear();
        }
    }

    /**
     * @return array|false
     *
     * @throws Exception
     */
    private function loadData()
    {
        $csvData = file($this->kernel->getProjectDir() . '/src/DataFixtures/coordinates.csv');
        if ($csvData === false) {
            throw new Exception('Cannot read file');
        }

        return $csvData;
    }

    /**
     * @param array $csvData
     *
     * @return array
     */
    private function transformData(array $csvData): array
    {
        $vehiclesData = [];
        $prevVehicleId = null;
        $prevLatitude = 0.0;
        $prevLongitude = 0.0;
        foreach ($csvData as $line => $string) {
            $string = trim($string);
            [$vehicleId, $latitude, $longitude] = explode(',', $string);
            $latitude = (float)$latitude;
            $longitude = (float)$longitude;

            if (isset($vehiclesData[$vehicleId]) && $vehicleId === $prevVehicleId) {
                // c^2 = a^2 + b^2
                $a = ($prevLatitude - $latitude);
                $b = ($prevLongitude - $longitude);
                $c = pow($a, 2) + pow($b, 2);
                $c = sqrt($c);
                $km = $c / 0.02;
                $seconds = (1000 * $km) / (1000 / 60);

                $vehiclesData[$vehicleId][] = [$latitude, $longitude, $km, $seconds];
            } else {
                $vehiclesData[$vehicleId][] = [$latitude, $longitude, 0, 0];
            }

            $prevVehicleId = $vehicleId;
            $prevLatitude = $latitude;
            $prevLongitude = $longitude;
        }

        return $vehiclesData;
    }
}
