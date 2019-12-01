<?php

namespace App\Service\Action;

use App\Form\Type\VehicleType;
use App\Repository\VehicleRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VehicleCreateAction
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var VehicleRepository
     */
    private $vehicleRepository;

    public function __construct(
        ContainerInterface $container,
        VehicleRepository $vehicleRepository
    ) {
        $this->container = $container;
        $this->vehicleRepository = $vehicleRepository;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function execute(Request $request): Response
    {
        $vehicleForm = $this->container->get('form.factory')->create(VehicleType::class);
        $vehicleForm->handleRequest($request);

        if ($vehicleForm->isSubmitted() && $vehicleForm->isValid()) {
            $vehicle = $vehicleForm->getData();

            $entityManager = $this->container->get('doctrine')->getManager();
            $entityManager->persist($vehicle);
            $entityManager->flush();

            $redirectToUrl = $this->container->get('router')->generate('vehicle_view', [
                'id' => $vehicle->getId(),
                'type' => $request->get('type'),
                'plate_number' => $request->get('plate_number'),
            ]);
            return new RedirectResponse($redirectToUrl);
        }

        $content = $this->container->get('twig')->render('vehicle/create.html.twig', [
            'vehicleForm' => $vehicleForm->createView(),
        ]);

        $response = new Response();
        $response->setContent($content);

        return $response;
    }
}
