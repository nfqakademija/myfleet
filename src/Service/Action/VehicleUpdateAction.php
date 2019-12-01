<?php

namespace App\Service\Action;

use App\Form\Type\VehicleType;
use App\Repository\VehicleRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VehicleUpdateAction
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
     * VehicleUpdateAction constructor.
     * @param ContainerInterface $container
     * @param VehicleRepository $vehicleRepository
     */
    public function __construct(
        ContainerInterface $container,
        VehicleRepository $vehicleRepository
    ) {
        $this->container = $container;
        $this->vehicleRepository = $vehicleRepository;
    }

    /**
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function execute(Request $request)
    {
        $vehicleId = explode('/', $request->getPathInfo())[2];
        $vehicle = $this->vehicleRepository->findOneBy(['id' => $vehicleId]);

        $vehicleForm = $this->container->get('form.factory')->create(VehicleType::class, $vehicle);
        $vehicleForm->handleRequest($request);

        if ($vehicleForm->isSubmitted() && $vehicleForm->isValid()) {
            $vehicle = $vehicleForm->getData();

            $entityManager = $this->container->get('doctrine')->getManager();
            $entityManager->persist($vehicle);
            $entityManager->flush();

            $this->container->get('session')->getFlashBag()->add('success', 'vehicle_update_success');

            $redirectToUrl = $this->container->get('router')->generate('vehicle_view', [
                'id' => $vehicle->getId(),
                'type' => $request->get('type'),
                'plate_number' => $request->get('plate_number'),
            ]);
            return new RedirectResponse($redirectToUrl);
        }
        //$vehicleForm->setData($vehicle);

        $content = $this->container->get('twig')->render('vehicle/update.html.twig', [
            'vehicle' => $vehicle,
            'vehicleForm' => $vehicleForm->createView(),
        ]);

        $response = new Response();
        $response->setContent($content);

        return $response;
    }
}
