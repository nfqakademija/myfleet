<?php

declare(strict_types=1);

namespace App\Service\Action;

use App\Entity\Event;
use App\Entity\ExpenseEntry;
use App\Entity\Task;
use App\Entity\Vehicle;
use App\Form\Type\EventType;
use App\Form\Type\ExpenseEntryType;
use App\Form\Type\TaskType;
use App\Repository\EventRepository;
use App\Repository\ExpenseEntryRepository;
use App\Repository\RegistryDataEntryRepository;
use App\Repository\TaskRepository;
use App\Repository\VehicleDataEntryRepository;
use App\Repository\VehicleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use InvalidArgumentException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
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
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @var ExpenseEntryRepository
     */
    private $expenseEntryRepository;

    /**
     * @var VehicleDataEntryRepository
     */
    private $vehicleDataEntryRepository;

    /**
     * @var RegistryDataEntryRepository
     */
    private $registryDataEntryRepository;

    /**
     * @var Security
     */
    private $security;

    public function __construct(
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        FlashBagInterface $flashBag,
        RouterInterface $router,
        Environment $twig,
        VehicleRepository $vehicleRepository,
        TaskRepository $taskRepository,
        EventRepository $eventRepository,
        ExpenseEntryRepository $expenseEntryRepository,
        VehicleDataEntryRepository $vehicleDataEntryRepository,
        RegistryDataEntryRepository $registryDataEntryRepository,
        Security $security
    ) {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
        $this->router = $router;
        $this->twig = $twig;
        $this->vehicleRepository = $vehicleRepository;
        $this->taskRepository = $taskRepository;
        $this->eventRepository = $eventRepository;
        $this->expenseEntryRepository = $expenseEntryRepository;
        $this->vehicleDataEntryRepository = $vehicleDataEntryRepository;
        $this->registryDataEntryRepository = $registryDataEntryRepository;
        $this->security = $security;
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse|Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws Exception
     */
    public function execute(Request $request)
    {
        $data = $this->getDataEntries($request);
        $vehicle = $data[VehicleRepository::class];

        $additionalVehicleData = $this->getAdditionalVehicleData($vehicle);

        $vehicleDataEntries = $data[VehicleDataEntryRepository::class];
        $registryDataEntry = $data[RegistryDataEntryRepository::class];

        $coordinates = $this->extractCoordinates($vehicleDataEntries);
        $coordinates = array_reverse($coordinates);

        $formTypes = [EventType::class, TaskType::class, ExpenseEntryType::class];
        $forms = [];
        $user = $this->security->getUser();

        foreach ($formTypes as $formType) {
            $forms[$formType] = $this->createFormType($request, $formType);
            if ($forms[$formType]->isSubmitted() && $forms[$formType]->isValid() && !is_null($user)) {
                $this->updateEntity($forms[$formType], $vehicle, $user);
                $this->addSuccessFlashBag($formType);

                return $this->redirect($request);
            }
        }

        $content = $this->twig->render('vehicle/view.html.twig', [
            'vehicle' => $vehicle,
            'additionalVehicleData' => $additionalVehicleData,
            'vehicleDataEntries' => $vehicleDataEntries,
            'timestamp' => $data['timestamp'],
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

    /**
     * @param Request $request
     *
     * @return array
     */
    private function getDataEntries(Request $request): array
    {
        $vehicleId = $request->attributes->get('id');
        $vehicle = $this->vehicleRepository->find($vehicleId);

        if ($vehicle === null) {
            return [];
        }

        $data = [];
        $data[VehicleRepository::class] = $vehicle;
        $data[VehicleDataEntryRepository::class] = $this->vehicleDataEntryRepository->getLastEntries(
            $vehicle,
            100
        );
        $lastVehicleDataEntry = $this->vehicleDataEntryRepository->getLastEntry($vehicle);
        $data['timestamp'] = (
        $lastVehicleDataEntry !== null
            ? $lastVehicleDataEntry->getEventTime()->getTimestamp()
            : 0
        );
        $data[RegistryDataEntryRepository::class] = $this->registryDataEntryRepository->getLastEntry($vehicle);

        return $data;
    }

    /**
     * @param array $vehicleDataEntries
     *
     * @return array
     */
    private function extractCoordinates(array $vehicleDataEntries): array
    {
        $coordinates = [];

        foreach ($vehicleDataEntries as $entry) {
            $coordinates[] = [$entry->getLatitude(), $entry->getLongitude()];
        }

        return $coordinates;
    }

    /**
     * @param Request $request
     * @param string $formType
     *
     * @return FormInterface
     */
    private function createFormType(Request $request, string $formType)
    {
        $form = $this->formFactory->create($formType);
        $form->handleRequest($request);

        return $form;
    }

    /**
     * @param FormInterface $form
     * @param Vehicle $vehicle
     * @param UserInterface $user
     *
     * @throws Exception
     */
    private function updateEntity(FormInterface $form, Vehicle $vehicle, UserInterface $user): void
    {
        /** @var Task|Event|ExpenseEntry $entity */
        $entity = $form->getData();
        $entity->setVehicle($vehicle);
        $entity->setUser($user);
        $entity->setDescription((string)$entity->getDescription());
        if ($entity instanceof Task) {
            $entity->setStartAt(new DateTime());
        }

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @param string $formType
     */
    private function addSuccessFlashBag(string $formType): void
    {
        $this->flashBag->add('success', $this->getSuccessMessage($formType));
    }

    /**
     * @param string $formType
     *
     * @return string
     */
    private function getSuccessMessage(string $formType): string
    {
        switch ($formType) {
            case TaskType::class:
                return 'task_add_success';
            case EventType::class:
                return 'event_add_success';
            case ExpenseEntryType::class:
                return 'expense_add_success';
            default:
                throw new InvalidArgumentException('Wrong argument passed');
        }
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    private function redirect(Request $request): Response
    {
        $redirectToUrl = $this->router->generate('vehicle_view', [
            'id' => $request->attributes->get('id'),
            'type' => $request->get('type'),
            'plate_number' => $request->get('plate_number'),
        ]);

        return new RedirectResponse($redirectToUrl);
    }

    /**
     * @param Vehicle $vehicle
     *
     * @return array
     */
    private function getAdditionalVehicleData(Vehicle $vehicle): array
    {
        $out = [];

        $out['tasks'] = $this->taskRepository->findBy(
            ['vehicle' => $vehicle],
            ['id' => 'DESC'],
            20
        );

        $out['events'] = $this->eventRepository->findBy(
            ['vehicle' => $vehicle],
            ['id' => 'DESC'],
            20
        );

        $out['expenseEntries'] = $this->expenseEntryRepository->findBy(
            ['vehicle' => $vehicle],
            ['id' => 'DESC'],
            20
        );

        return $out;
    }
}
