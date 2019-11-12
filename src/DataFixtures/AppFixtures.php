<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\Event;
use App\Entity\ExpenseEntry;
use App\Entity\Vehicle;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $data = [
            [
                'make' => 'Ford',
                'model' => 'Mondeo',
                'firstRegistration' => '2010-01-01',
                'registrationPlateNumber' => 'AAA111',
                'vinCode' => '12345678901234567',
                'type' => 'car',
                'additionalInformation' => '',
            ],
            [
                'make' => 'Skoda',
                'model' => 'Octavia',
                'firstRegistration' => '2011-02-02',
                'registrationPlateNumber' => 'AAA112',
                'vinCode' => '12345678901234568',
                'type' => 'car',
                'additionalInformation' => '',
            ],
            [
                'make' => 'BMW',
                'model' => 'X6M',
                'firstRegistration' => '2013-03-03',
                'registrationPlateNumber' => 'AAA113',
                'vinCode' => '12345678901234569',
                'type' => 'car',
                'additionalInformation' => '',
            ],
            [
                'make' => 'Audi',
                'model' => 'A7',
                'firstRegistration' => '2014-04-04',
                'registrationPlateNumber' => 'AAA114',
                'vinCode' => '12345678901234570',
                'type' => 'car',
                'additionalInformation' => '',
            ],
            [
                'make' => 'Opel',
                'model' => 'Astra',
                'firstRegistration' => '2015-05-05',
                'registrationPlateNumber' => 'AAA115',
                'vinCode' => '12345678901234571',
                'type' => 'car',
                'additionalInformation' => '',
            ],
            [
                'make' => 'Volvo',
                'model' => 'Globetroter',
                'firstRegistration' => '2016-06-06',
                'registrationPlateNumber' => 'AAA116',
                'vinCode' => '12345678901234572',
                'type' => 'truck',
                'additionalInformation' => '',
            ],
            [
                'make' => 'DAF',
                'model' => '205 XF',
                'firstRegistration' => '2017-07-07',
                'registrationPlateNumber' => 'AAA117',
                'vinCode' => '12345678901234573',
                'type' => 'truck',
                'additionalInformation' => '',
            ],
            [
                'make' => 'Iveco',
                'model' => 'Stralis',
                'firstRegistration' => '2018-08-08',
                'registrationPlateNumber' => 'AAA118',
                'vinCode' => '12345678901234574',
                'type' => 'truck',
                'additionalInformation' => '',
            ],
            [
                'make' => 'Man',
                'model' => 'Atego',
                'firstRegistration' => '2019-09-09',
                'registrationPlateNumber' => 'AAA119',
                'vinCode' => '12345678901234575',
                'type' => 'truck',
                'additionalInformation' => '',
            ],
            [
                'make' => 'Renault',
                'model' => 'Premium',
                'firstRegistration' => '2018-10-10',
                'registrationPlateNumber' => 'AAA120',
                'vinCode' => '12345678901234576',
                'type' => 'truck',
                'additionalInformation' => '',
                ],
            [
                'make' => 'Schmitz',
                'model' => 'SKO',
                'firstRegistration' => '2017-11-11',
                'registrationPlateNumber' => 'BB222',
                'vinCode' => '12345678901234577',
                'type' => 'semitrailer',
                'additionalInformation' => '',
            ],
            [
                'make' => 'Koegel',
                'model' => 'SNCO',
                'firstRegistration' => '2016-12-12',
                'registrationPlateNumber' => 'BB223',
                'vinCode' => '12345678901234578',
                'type' => 'semitrailer',
                'additionalInformation' => '',
            ],
            [
                'make' => 'Krone',
                'model' => 'SDP',
                'firstRegistration' => '2015-01-13',
                'registrationPlateNumber' => 'BB224',
                'vinCode' => '12345678901234579',
                'type' => 'semitrailer',
                'additionalInformation' => '',
            ],
            [
                'make' => 'Humbaur',
                'model' => 'BigOne',
                'firstRegistration' => '2014-02-14',
                'registrationPlateNumber' => 'BB225',
                'vinCode' => '12345678901234580',
                'type' => 'semitrailer',
                'additionalInformation' => '',
            ],
            [
                'make' => 'Wielton',
                'model' => 'NTSG',
                'firstRegistration' => '2013-03-15',
                'registrationPlateNumber' => 'BB226',
                'vinCode' => '12345678901234581',
                'type' => 'semitrailer',
                'additionalInformation' => '',
            ],
            [
                'make' => 'Ford',
                'model' => 'Transit',
                'firstRegistration' => '2012-04-16',
                'registrationPlateNumber' => 'AAA121',
                'vinCode' => '12345678901234582',
                'type' => 'van',
                'additionalInformation' => '',
            ],
            [
                'make' => 'Opel',
                'model' => 'Vivaro',
                'firstRegistration' => '2011-05-17',
                'registrationPlateNumber' => 'AAA122',
                'vinCode' => '12345678901234583',
                'type' => 'van',
                'additionalInformation' => '',
            ],
            [
                'make' => 'Mercedez-Benz',
                'model' => 'Sprinter',
                'firstRegistration' => '2010-06-18',
                'registrationPlateNumber' => 'AAA123',
                'vinCode' => '12345678901234584',
                'type' => 'van',
                'additionalInformation' => '',
            ],
            [
                'make' => 'Citroen',
                'model' => 'Jumper',
                'firstRegistration' => '2009-07-19',
                'registrationPlateNumber' => 'AAA124',
                'vinCode' => '12345678901234585',
                'type' => 'van',
                'additionalInformation' => '',
            ],
            [
                'make' => 'Peugeot',
                'model' => 'Boxer',
                'firstRegistration' => '2008-08-20',
                'registrationPlateNumber' => 'AAA125',
                'vinCode' => '12345678901234586',
                'type' => 'van',
                'additionalInformation' => '',
            ],
        ];
        foreach ($data as $row) {
            $vehicle = new Vehicle();
            $vehicle->setMake($row['make']);
            $vehicle->setModel($row['model']);
            $vehicle->setFirstRegistration(new DateTime($row['firstRegistration']));
            $vehicle->setRegistrationPlateNumber($row['registrationPlateNumber']);
            $vehicle->setVinCode($row['vinCode']);
            $vehicle->setType($row['type']);
            $vehicle->setAdditionalInformation($row['additionalInformation']);

            $manager->persist($vehicle);
            $manager->flush();

            $hasChangedTyres = (bool)random_int(0, 1);

            if ($hasChangedTyres) {
                // insert Task with status completed and startAt < today
                // insert Event
                // insert ExpenseEntry
                $taskStartAt = new DateTime();
                $taskStartAt->modify('today -' . mt_rand(1, 10) . ' days');
                $eventCreatedAt = new DateTime($taskStartAt->format('Y-m-d'));
                $eventCreatedAt->modify('+'.mt_rand(1, 2) . ' days');

                $task = new Task();
                $task->setVehicle($vehicle);
                $task->setStartAt($taskStartAt);
                $task->setDescription('Pakeisti padangas į žiemines');
                $task->setIsCompleted(true);

                $manager->persist($task);
                $manager->flush();

                $event = new Event();
                $event->setVehicle($vehicle);
                $event->setCreatedAt($eventCreatedAt);
                $event->setDescription('Padangos pakeistos į žiemines');

                $manager->persist($event);
                $manager->flush();

                $expenseEntry = new ExpenseEntry();
                $expenseEntry->setVehicle($vehicle);
                $expenseEntry->setDescription('Padangų permontavimas ir balansavimas');
                $expenseEntry->setAmount(50000);
                $expenseEntry->setCreatedAt($eventCreatedAt);

                $manager->persist($expenseEntry);
                $manager->flush();
            } else {
                // insert Task with status no completed startAt > today
                $taskStartAt = new DateTime();
                $taskStartAt->modify('today +' . mt_rand(1, 10) . ' days');

                $task = new Task();
                $task->setVehicle($vehicle);
                $task->setStartAt($taskStartAt);
                $task->setDescription('Pakeisti padangas į žiemines');
                $task->setIsCompleted(false);

                $manager->persist($task);
                $manager->flush();
            }
        }
    }
}
