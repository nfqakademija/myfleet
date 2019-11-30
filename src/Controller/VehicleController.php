<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Form\Type\EventType;
use App\Form\Type\ExpenseEntryType;
use App\Form\Type\TaskType;
use App\Form\Type\VehicleType;
use App\Repository\RegistryDataEntryRepository;
use App\Repository\VehicleDataEntryRepository;
use App\Repository\VehicleRepository;
use App\Service\Action\VehicleListAction;
use App\Service\BuildFilterDtoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class VehicleController extends AbstractController
{
    /**
     * @Route("/vehicle/list", name="vehicle_list")
     *
     * @param Request $request
     * @param VehicleListAction $vehicleListAction
     *
     * @return Response
     */
    public function list(Request $request, VehicleListAction $vehicleListAction): Response
    {
        return $vehicleListAction->execute($request);
    }

    /**
     * @Route("/vehicle/{id}", name="vehicle_view", requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param Vehicle $vehicle
     * @param VehicleDataEntryRepository $vehicleDataEntryRepository
     * @param RegistryDataEntryRepository $dataEntryRepository
     *
     * @return Response
     */
    public function view(
        Request $request,
        Vehicle $vehicle,
        VehicleDataEntryRepository $vehicleDataEntryRepository,
        RegistryDataEntryRepository $dataEntryRepository
    ) {
        $vehicleDataEntries = $vehicleDataEntryRepository->getLastEntries($vehicle);
        $registryDataEntry = $dataEntryRepository->findOneBy(['vehicle' => $vehicle], ['eventTime' => 'DESC']);

        $data = [
            ['eventForm', EventType::class, 'event', 'event_add_success'],
            ['taskForm', TaskType::class, 'task', 'task_add_success'],
            ['expenseEntryForm', ExpenseEntryType::class, 'expenseEntry', 'expense_add_success'],
        ];

        foreach ($data as $row) {
            list($form, $class, $entity, $flashMessage) = $row;

            $$form = $this->createForm($class);
            $$form->handleRequest($request);
            if ($$form->isSubmitted() && $$form->isValid()) {
                $$entity = $$form->getData();
                $$entity->setVehicle($vehicle);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($$entity);
                $entityManager->flush();

                $this->addFlash('success', $flashMessage);

                return $this->redirectToRoute('vehicle_view', ['id' => $vehicle->getId()]);
            }
        }

        return $this->render('vehicle/view.html.twig', [
            'vehicle' => $vehicle,
            'vehicleDataEntries' => $vehicleDataEntries,
            'registryDataEntry' => $registryDataEntry,
            'eventForm' => $eventForm->createView(),
            'taskForm' => $taskForm->createView(),
            'expenseEntryForm' => $expenseEntryForm->createView(),
        ]);
    }

    /**
     * @Route("/vehicle/create", name="vehicle_create")
     *
     * @param Request $request
     *
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

            $this->addFlash('success', 'vehicle_add_success');

            return $this->redirectToRoute('vehicle_list');
        }

        return $this->render('vehicle/create.html.twig', [
            'vehicleForm' => $vehicleForm->createView(),
        ]);
    }

    /**
     * @Route("/vehicle/{id}/update", name="vehicle_update", requirements={"id":"\d+"})
     *
     * @param Request $request
     * @param Vehicle $vehicle
     *
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

            $this->addFlash('success', 'vehicle_update_success');

            return $this->redirectToRoute('vehicle_view', [
                'id' => $vehicle->getId(),
                'type' => $request->get('type'),
                'plate_number' => $request->get('plate_number'),
            ]);
        }

        return $this->render('vehicle/update.html.twig', [
            'vehicle' => $vehicle,
            'vehicleForm' => $vehicleForm->createView(),
        ]);
    }
}
