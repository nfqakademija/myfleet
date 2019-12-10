<?php

namespace App\Service\Action;

use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;

class VehicleTaskCompleteAction
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @param EntityManagerInterface $entityManager
     * @param TaskRepository $taskRepository
     * @param FlashBagInterface $flashBag
     * @param RouterInterface $router
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TaskRepository $taskRepository,
        FlashBagInterface $flashBag,
        RouterInterface $router
    ) {
        $this->entityManager = $entityManager;
        $this->taskRepository = $taskRepository;
        $this->flashBag = $flashBag;
        $this->router = $router;
    }

    public function execute(Request $request)
    {
        $task = $this->taskRepository->find($request->attributes->get('id'));
        $vehicle = (!is_null($task) ? $task->getVehicle() : null);

        if (is_null($task) || is_null($vehicle)) {
            $this->flashBag->add('danger', 'Nepavyko įvykdyti užduoties');

            $redirectToUrl = $this->router->generate('vehicle_list', [
                'type' => $request->get('type'),
                'plate_number' => $request->get('plate_number'),
            ]);
            return new RedirectResponse($redirectToUrl);
        }

        $task->setIsCompleted(true);
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        $this->flashBag->add('success', 'Užduotis įvykdyta');

        $redirectToUrl = $this->router->generate('vehicle_view', [
            'id' => $request->attributes->get('id'),
            'type' => $request->get('type'),
            'plate_number' => $request->get('plate_number'),
        ]);
        return new RedirectResponse($redirectToUrl);
    }
}
