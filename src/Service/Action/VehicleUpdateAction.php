<?php

namespace App\Service\Action;

use App\Form\Type\VehicleType;
use App\Repository\VehicleRepository;
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

class VehicleUpdateAction
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
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $entityManager
     * @param FlashBagInterface $flashBag
     * @param RouterInterface $router
     * @param Environment $twig
     * @param VehicleRepository $vehicleRepository
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        FlashBagInterface $flashBag,
        RouterInterface $router,
        Environment $twig,
        VehicleRepository $vehicleRepository
    ) {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
        $this->router = $router;
        $this->twig = $twig;
        $this->vehicleRepository = $vehicleRepository;
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
        $vehicle = $this->vehicleRepository->findOneBy(['id' => $request->attributes->get('id')]);

        $vehicleForm = $this->formFactory->create(VehicleType::class, $vehicle);
        $vehicleForm->handleRequest($request);

        if ($vehicleForm->isSubmitted() && $vehicleForm->isValid()) {
            $vehicle = $vehicleForm->getData();

            $this->entityManager->persist($vehicle);
            $this->entityManager->flush();

            $this->flashBag->add('success', 'vehicle_update_success');

            $redirectToUrl = $this->router->generate('vehicle_view', [
                'id' => $vehicle->getId(),
                'type' => $request->get('type'),
                'plate_number' => $request->get('plate_number'),
            ]);
            return new RedirectResponse($redirectToUrl);
        }

        $content = $this->twig->render('vehicle/update.html.twig', [
            'vehicle' => $vehicle,
            'vehicleForm' => $vehicleForm->createView(),
        ]);

        $response = new Response();
        $response->setContent($content);

        return $response;
    }
}
