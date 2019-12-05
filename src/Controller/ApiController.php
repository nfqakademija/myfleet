<?php

namespace App\Controller;

use App\Repository\FakeRegistryDataEntryRepository;
use App\Repository\FakeVehicleDataEntryRepository;
use App\Repository\InstantNotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

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
     * @Route("/api/instant_notification", name="api_instant_notificaiton")
     *
     * @param InstantNotificationRepository $instantNotificationRepository
     * @param Security $security
     *
     * @return Response
     */
    public function getInstantNotification(
        InstantNotificationRepository $instantNotificationRepository,
        Security $security
    ) {
        $entityManager = $this->getDoctrine()->getManager();
        $currentUser = $security->getUser();

        if (is_null($currentUser)) {
            return new JsonResponse();
        }

        for ($i = 1; $i <= 20; $i++) {
            $lastNotification = $instantNotificationRepository->findUnsentUserNotification($currentUser);

            if (null !== $lastNotification && !is_null($lastNotification->getEventTime())) {
                $lastNotification->setIsSent(true);

                $entityManager->persist($lastNotification);
                $entityManager->flush();

                return new JsonResponse($lastNotification);
            } else {
                sleep(1);
                continue;
            }
        }
        return new JsonResponse();
    }
}
