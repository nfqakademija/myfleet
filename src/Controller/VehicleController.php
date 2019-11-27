<?php

namespace App\Controller;

use App\Dto\FiltersData;
use App\Entity\Vehicle;
use App\Form\Type\EventType;
use App\Form\Type\ExpenseEntryType;
use App\Form\Type\TaskType;
use App\Form\Type\VehicleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class VehicleController extends AbstractController
{
    /**
     * @Route("/vehicle/list", name="vehicle_list")
     * @param Request $request
     * @param SessionInterface $session
     * @return Response
     */
    public function list(Request $request, SessionInterface $session)
    {
        $filtersData = new FiltersData();
        $filtersData->setVehicleType($request->get('type'));
        $filtersData->setPlateNumberPart($request->get('plate_number'));
        $filtersData->setPage($request->get('page') ? $request->get('page') : 1);

        $vehicles = $this->getDoctrine()
            ->getRepository(Vehicle::class)
            ->filterVehicles($filtersData);

        $totalVehicles = $this->getDoctrine()
            ->getRepository(Vehicle::class)
            ->countMatchingVehicles($filtersData);

        $pagesCount = ceil($totalVehicles / $filtersData->getPageSize());
        $session->set('current_filters', $request->query->all());

        return $this->render('vehicle/list.html.twig', [
            'vehicles' => $vehicles,
            'pagesCount' => $pagesCount,
            'currentPage' => $request->get('page') ? $request->get('page') : 1,
            'currentFilters' => $request->query->all(),
        ]);
    }

    /**
     * @Route("/vehicle/{id}", name="vehicle_view", requirements={"id":"\d+"})
     * @param Request $request
     * @param Vehicle $vehicle
     * @param SessionInterface $session
     * @return Response
     */
    public function view(Request $request, Vehicle $vehicle, SessionInterface $session)
    {
        $eventForm = $this->createForm(EventType::class);
        $eventForm->handleRequest($request);

        if ($eventForm->isSubmitted() && $eventForm->isValid()) {
            $event = $eventForm->getData();
            $event->setVehicle($vehicle);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();

            $this->addFlash('success', 'Įvykis sekmingai pridėtas');

            return $this->redirectToRoute('vehicle_view', ['id' => $vehicle->getId()]);
        }

        $taskForm = $this->createForm(TaskType::class);
        $taskForm->handleRequest($request);

        if ($taskForm->isSubmitted() && $taskForm->isValid()) {
            $task = $taskForm->getData();
            $task->setVehicle($vehicle);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'Užduotis sekmingai pridėta');

            return $this->redirectToRoute('vehicle_view', ['id' => $vehicle->getId()]);
        }

        $expenseEntryForm = $this->createForm(ExpenseEntryType::class);
        $expenseEntryForm->handleRequest($request);

        if ($expenseEntryForm->isSubmitted() && $expenseEntryForm->isValid()) {
            $expenseEntry = $expenseEntryForm->getData();
            $expenseEntry->setVehicle($vehicle);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($expenseEntry);
            $entityManager->flush();

            $this->addFlash('success', 'Išlaidos sekmingai pridėtos');

            return $this->redirectToRoute('vehicle_view', ['id' => $vehicle->getId()]);
        }

        return $this->render('vehicle/view.html.twig', [
            'vehicle' => $vehicle,
            'eventForm' => $eventForm->createView(),
            'taskForm' => $taskForm->createView(),
            'expenseEntryForm' => $expenseEntryForm->createView(),
            'currentFilters' => $session->get('current_filters') ?? [],
        ]);
    }

    /**
     * @Route("/vehicle/create", name="vehicle_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $vehicleForm = $this->createForm(VehicleType::class);
        $vehicleForm->handleRequest($request);

        if ($vehicleForm->isSubmitted() && $vehicleForm->isValid()) {
            $vehicle = $vehicleForm->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vehicle);
            $entityManager->flush();

            $this->addFlash('success', 'Transporto priemonė sekmingai pridėta!');

            return $this->redirectToRoute('vehicle_list');
        }

        return $this->render('vehicle/create.html.twig', [
            'vehicleForm' => $vehicleForm->createView(),
        ]);
    }

    /**
     * @Route("/vehicle/{id}/update", name="vehicle_update", requirements={"id":"\d+"})
     * @param Request $request
     * @param Vehicle $vehicle
     * @return RedirectResponse|Response
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $vehicleForm = $this->createForm(VehicleType::class, $vehicle);
        $vehicleForm->handleRequest($request);

        if ($vehicleForm->isSubmitted() && $vehicleForm->isValid()) {
            $vehicle = $vehicleForm->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vehicle);
            $entityManager->flush();

            $this->addFlash('success', 'Transporto priemonė sekmingai atnaujinta!');

            return $this->redirectToRoute('vehicle_view', ['id' => $vehicle->getId()]);
        }

        return $this->render('vehicle/update.html.twig', [
            'vehicle' => $vehicle,
            'vehicleForm' => $vehicleForm->createView(),
        ]);
    }
}
