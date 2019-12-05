<?php

namespace App\Controller;

use App\Entity\FakeRegistryDataEntry;
use App\Entity\InstantNotification;
use App\Repository\FakeRegistryDataEntryRepository;
use App\Repository\FakeVehicleDataEntryRepository;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/demo/api/vehicle_data/{vin}", name="api_vehicle_data")
     * @param Request $request
     * @param FakeVehicleDataEntryRepository $repository
     * @param string $vin
     * @return Response
     */
    public function vehicleData(
        Request $request,
        FakeVehicleDataEntryRepository $repository,
        string $vin
    ) {
        $data = $repository->findByVinTillThisMoment($vin);
        return $this->json($data);
    }

    /**
     * @Route("/demo/api/registry_data/{vin}", name="api_registry_data")
     * @param Request $request
     * @param FakeRegistryDataEntryRepository $repository
     * @param string $vin
     * @return false|string
     */
    public function registryData(
        Request $request,
        FakeRegistryDataEntryRepository $repository,
        string $vin
    ) {
        $data = $repository->findByVinTillThisMoment($vin);

        return $this->json($data);
    }

    /**
     * @Route("/api/getInstantNotification", name="api_instant_notificaiton")
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function getInstantNotification(Request $request)
    {
        set_time_limit(30);
        $startTs = $currentTs = (new DateTime())->getTimestamp();
        $diffTs = 0;

        $entityManager = $this->getDoctrine()->getManager();
        $notification = $this->getDoctrine()
            ->getRepository(InstantNotification::class);

        while ($diffTs < 3) {
            $currentTs = (new DateTime())->getTimestamp();
            $diffTs = $currentTs - $startTs;

            $lastAjaxCall = $request->get('timestamp');

            $lastNotification = $notification->findOneBy(
                ['isSent' => false],
                ['eventTime' => 'ASC']
            );

            if (
                null !== $lastNotification
                && (null === $lastAjaxCall || $lastNotification->getEventTime()->getTimestamp() > $lastAjaxCall)
            ) {
                echo $this->json($lastNotification);

                $lastNotification->setIsSent(true);

                $entityManager->persist($lastNotification);
                $entityManager->flush();

                break;
            } else {
                sleep(1);
                continue;
            }
        }
        return (new Response());
    }
}
