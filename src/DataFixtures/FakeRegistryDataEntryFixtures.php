<?php

namespace App\DataFixtures;

use App\Entity\FakeRegistryDataEntry;
use App\Entity\Vehicle;
use Cassandra\Date;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FakeRegistryDataEntryFixtures extends Fixture implements DependentFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    public function load(ObjectManager $manager)
    {
        $startTime = new Datetime('-2 days');
        $endTime = new Datetime('+5 days');

        $em = $this->container->get('doctrine')->getManager();
        $repository = $em->getRepository(Vehicle::class);

        foreach (AppFixtures::VINS as $vin) {
            $vehicle = $repository->findOneBy(['vin' => $vin]);

            $technicalInspectionValidTill = $vehicle->getFirstRegistration();
            $currentYear = (new DateTime())->format('Y');
            while ($technicalInspectionValidTill->format('Y') <= $currentYear) {
                $technicalInspectionValidTill->modify('+2 years');
            }

            $currentTime = clone $startTime;
            while ($currentTime <= $endTime) {
                $status = ($currentTime > $technicalInspectionValidTill ? 'registred_but_suspended' : 'registred');
                $isInsured = (bool)(10 >= mt_rand(1, 12));
                $isPoliceSearching = (bool)(10 < mt_rand(1, 12));
                $isAllowedDriving = ('registred' === $status && $isInsured && !$isPoliceSearching);

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
    }

    public function getDependencies()
    {
        return [
            AppFixtures::class,
        ];
    }
}
