<?php

namespace App\Service\Action;

use App\Entity\Event;
use App\Entity\ExpenseEntry;
use App\Entity\Task;
use App\Form\Type\EventType;
use App\Form\Type\ExpenseEntryType;
use App\Form\Type\TaskType;
use App\Repository\VehicleRepository;
use App\Repository\VehicleDataEntryRepository;
use App\Repository\RegistryDataEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class VehicleViewAction
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var Environment
     */
    private $twig;

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
        FormFactoryInterface $formFactory,
        RegistryInterface $entityManager,
        FlashBagInterface $flashBag,
        RouterInterface $router,
        Environment $twig,
        VehicleRepository $vehicleRepository,
        VehicleDataEntryRepository $vehicleDataEntryRepository,
        RegistryDataEntryRepository $registryDataEntryRepository
    ) {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
        $this->router = $router;
        $this->twig = $twig;
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
        $vehicleId = $request->attributes->get('id');

        $vehicle = $this->vehicleRepository->findOneBy(['id' => $vehicleId]);
        $vehicleDataEntries = $this->vehicleDataEntryRepository->getLastEntries($vehicle);
        $registryDataEntry = $this->registryDataEntryRepository->findOneBy(
            ['vehicle' => $vehicleId],
            ['eventTime' => 'DESC']
        );

        $formTypes = [EventType::class, TaskType::class, ExpenseEntryType::class];

        $forms = [];
        foreach ($formTypes as $formType) {
            $forms[$formType] = $this->formFactory->create($formType);
            $forms[$formType]->handleRequest($request);

            if ($forms[$formType]->isSubmitted() && $forms[$formType]->isValid()) {
                /**
                 * @var Task|Event|ExpenseEntry
                 */
                $entity = $forms[$formType]->getData();
                $entity->setVehicle($vehicle);

                $entityManager = $this->entityManager->getManager();
                $entityManager->persist($entity);
                $entityManager->flush();

                $this->flashBag->add('success', 'valio');

                $redirectToUrl = $this->router->generate('vehicle_view', [
                    'id' => $vehicle->getId(),
                    'type' => $request->get('type'),
                    'plate_number' => $request->get('plate_number'),
                ]);
                return new RedirectResponse($redirectToUrl);
            }
        }

//        $data = [
//            ['eventForm', EventType::class, 'event', 'event_add_success'],
//            ['taskForm', TaskType::class, 'task', 'task_add_success'],
//            ['expenseEntryForm', ExpenseEntryType::class, 'expenseEntry', 'expense_add_success'],
//        ];
//

        $coordinates = [];
        if (isset($vehicleDataEntries)) {
            foreach ($vehicleDataEntries as $entry) {
                $coordinates[] = [$entry->getLatitude(), $entry->getLongitude()];
            }
        }

        $content = $this->twig->render('vehicle/view.html.twig', [
            'vehicle' => $vehicle,
            'vehicleDataEntries' => $vehicleDataEntries,
            'coordinates' => $coordinates,
            'registryDataEntry' => $registryDataEntry,
            'eventForm' => $forms[EventType::class]->createView(),
            'taskForm' => $forms[TaskType::class]->createView(),
            'expenseEntryForm' => $forms[ExpenseEntryType::class]->createView(),
        ]);

        $response = new Response();
        $response->setContent($content);

        return $response;
    }
}
