<?php

namespace App\Service\Action;

use App\Entity\Event;
use App\Entity\ExpenseEntry;
use App\Entity\Task;
use App\Entity\Vehicle;
use App\Form\Type\EventType;
use App\Form\Type\ExpenseEntryType;
use App\Form\Type\TaskType;
use App\Repository\VehicleRepository;
use App\Repository\VehicleDataEntryRepository;
use App\Repository\RegistryDataEntryRepository;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormTypeInterface;
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
        EntityManagerInterface $entityManager,
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
        $data = $this->getDataEntries($request);
        $vehicle = $data[VehicleRepository::class];
        $vehicleDataEntries = $data[VehicleDataEntryRepository::class];
        $registryDataEntry = $data[RegistryDataEntryRepository::class];

        $coordinates = $this->extractCoordinates($vehicleDataEntries);

        $formTypes = [EventType::class, TaskType::class, ExpenseEntryType::class];
        $forms = [];
        foreach ($formTypes as $formType) {
            $forms[$formType] = $this->createFormType($request, $formType);
            if ($forms[$formType]->isSubmitted() && $forms[$formType]->isValid()) {
                $this->updateEntity($forms[$formType], $vehicle);
                $this->addSuccessFlashBag($formType);
                $this->redirect($request);
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

    /**
     * @param string $formType
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
     * @return array
     */
    private function getDataEntries(Request $request): array
    {
        $vehicleId = $request->attributes->get('id');
        $data = [];
        $data[VehicleRepository::class] = $this->vehicleRepository->findOneBy(['id' => $vehicleId]);
        $data[VehicleDataEntryRepository::class] = $this->vehicleDataEntryRepository->findBy(
            ['vehicle' => $vehicleId],
            ['eventTime' => 'DESC'],
            100
        );
        $data[RegistryDataEntryRepository::class] = $this->registryDataEntryRepository->findOneBy(
            ['vehicle' => $vehicleId],
            ['eventTime' => 'DESC']
        );

        return $data;
    }

    /**
     * @param array $vehicleDataEntries
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
     */
    private function updateEntity($form, Vehicle $vehicle): void
    {
        /**
         * @var Task|Event|ExpenseEntry
         */
        $entity = $form->getData();
        $entity->setVehicle($vehicle);

        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    /**
     * @param Request $request
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
     * @param string $formType
     */
    private function addSuccessFlashBag(string $formType): void
    {
        $this->flashBag->add('success', $this->getSuccessMessage($formType));
    }
}
