<?php

namespace App\Service\Action;

use App\Form\Type\EventType;
use App\Form\Type\ExpenseEntryType;
use App\Form\Type\TaskType;
use App\Repository\VehicleRepository;
use App\Repository\VehicleDataEntryRepository;
use App\Repository\RegistryDataEntryRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class VehicleViewAction
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var VehicleRepository
     */
    private $vehicleRepository;

    /**
     * @var VehicleDataEntryRepository
     */
    private $vehicleDataEntryRepository;

    /**
     * @var RegistryDataEntryRepository
     */
    private $registryDataEntryRepository;

    public function __construct(
        ContainerInterface $container,
        VehicleRepository $vehicleRepository,
        VehicleDataEntryRepository $vehicleDataEntryRepository,
        RegistryDataEntryRepository $registryDataEntryRepository
    ) {
        $this->container = $container;
        $this->vehicleRepository = $vehicleRepository;
        $this->vehicleDataEntryRepository = $vehicleDataEntryRepository;
        $this->registryDataEntryRepository = $registryDataEntryRepository;
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function execute(Request $request)
    {
        $vehicleId = explode('/', $request->getPathInfo())[2];

        $vehicle = $this->vehicleRepository->findOneBy(['id' => $vehicleId]);
        $vehicleDataEntries = $this->vehicleDataEntryRepository->getLastEntries($vehicle);
        $registryDataEntry = $this->registryDataEntryRepository->findOneBy(
            ['vehicle' => $vehicleId],
            ['eventTime' => 'DESC']
        );

        $data = [
            ['eventForm', EventType::class, 'event', 'event_add_success'],
            ['taskForm', TaskType::class, 'task', 'task_add_success'],
            ['expenseEntryForm', ExpenseEntryType::class, 'expenseEntry', 'expense_add_success'],
        ];

        foreach ($data as $row) {
            list($form, $class, $entity, $flashMessage) = $row;

            //$$form = $this->createForm($class);
            $$form = $this->container->get('form.factory')->create($class);
            $$form->handleRequest($request);
            if ($$form->isSubmitted() && $$form->isValid()) {
                $$entity = $$form->getData();
                $$entity->setVehicle($vehicle);

                $entityManager = $this->container->get('doctrine')->getManager();
                $entityManager->persist($$entity);
                $entityManager->flush();

                $this->container->get('session')->getFlashBag()->add('success', $flashMessage);

                $redirectToUrl = $this->container->get('router')->generate('vehicle_view', [
                    'id' => $vehicle->getId(),
                    'type' => $request->get('type'),
                    'plate_number' => $request->get('plate_number'),
                ]);
                return new RedirectResponse($redirectToUrl);
            }
        }

        $coordinates = [];
        if (isset($vehicleDataEntries)) {
            foreach ($vehicleDataEntries as $entry) {
                $coordinates[] = [$entry->getLatitude(), $entry->getLongitude()];
            }
        }

        $content = $this->container->get('twig')->render('vehicle/view.html.twig', [
            'vehicle' => $vehicle,
            'vehicleDataEntries' => $vehicleDataEntries,
            'coordinates' => $coordinates,
            'registryDataEntry' => $registryDataEntry,
            'eventForm' => $eventForm->createView(),
            'taskForm' => $taskForm->createView(),
            'expenseEntryForm' => $expenseEntryForm->createView(),
        ]);

        $response = new Response();
        $response->setContent($content);

        return $response;
    }
}
