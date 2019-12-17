<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Vehicle;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Exception;

class VehicleFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var array
     */
    private $vins = [
        'YV2AS02A76B424444',
        'YS2R4X20002022235',
        'WSMS7480000706156',
        'VM3LVFS3FB1R22791',
        'WSM00000003165607',
        'VF624GPA000059305',
        'WMA06XZZ8CP034890',
        'YV2AG30A2DB636686',
        'WDB9340331L739361',
        'SUDPC200000036976',
        'YS2R6X20002094075',
        'WDB9634061L940188',
        'XLRASH4300G147637',
        'XLRASH4300G215255',
        'WMAH06ZZX7W095829',
        'XLRTE47MS0E827112',
        'WMA06XZZ7BW148240',
        'WKESD000000531892',
        'WSM00000003159336',
        'XLRTEH4300G063982',
    ];

    /**
     * @var array
     */
    private $vehicleList = [
        'Ford Mondeo',
        'Skoda Octavia',
        'BMW X6M',
        'Audi A7',
        'Opel Astra',
        'Volvo Globetroter',
        'DAF 205XF',
        'Iveco Stralis',
        'Man Atego',
        'Renault Premium',
        'Schmitz SKO',
        'Koegel SNCO',
        'Krone SDP',
        'Humbaur BigOne',
        'Wielton NTSG',
        'Ford Transit',
        'Opel Vivaro',
        'Mercedes-Benz Sprinter',
        'Citroen Jumper',
        'Peugeot Boxer',
    ];

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class,
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

        $firstRegistration = new DateTime('2010-01-01');
        foreach ($this->vins as $index => $vin) {
            [$make, $model] = explode(' ', $this->vehicleList[$index]);

            $firstRegistration->modify('+' . mt_rand(30, 120) . ' days');

            $vehicle = new Vehicle();
            $vehicle->setMake($make);
            $vehicle->setModel($model);
            $vehicle->setFirstRegistration(clone $firstRegistration);
            $vehicle->setPlateNumber($this->generateRandomPlateNumber($index));
            $vehicle->setVin($vin);
            $vehicle->setType($this->getType($index));
            $vehicle->setAdditionalInformation('');
            $vehicle->addUser($user);

            $manager->persist($vehicle);
            $manager->flush();

            $this->addReference('vehicle-' . $index, $vehicle);
        }
    }

    /**
     * @param int $index
     *
     * @return string
     */
    private function generateRandomPlateNumber(int $index): string
    {
        $charsLength = ($this->getType($index) === 'semitrailer' ? 2 : 3);
        $chars = 'ERTYUPASDFGHJKLZCVBNM';
        $plateNumber = substr(str_shuffle($chars), 0, $charsLength);
        $plateNumber .= mt_rand(100, 999);

        return $plateNumber;
    }

    /**
     * @param int $index
     *
     * @return string
     */
    private function getType(int $index): string
    {
        if ($index <= 5) {
            return 'car';
        } elseif ($index <= 10) {
            return 'truck';
        } elseif ($index <= 15) {
            return 'semitrailer';
        } elseif ($index <= 20) {
            return 'van';
        }

        return 'car';
    }
}
