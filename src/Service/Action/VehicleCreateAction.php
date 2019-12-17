<?php

declare(strict_types=1);

namespace App\Service\Action;

use App\Form\Type\VehicleType;
use Doctrine\ORM\EntityManagerInterface;
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

class VehicleCreateAction
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
     * @param FormFactoryInterface $formFactory
     * @param EntityManagerInterface $entityManager
     * @param FlashBagInterface $flashBag
     * @param RouterInterface $router
     * @param Environment $twig
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        EntityManagerInterface $entityManager,
        FlashBagInterface $flashBag,
        RouterInterface $router,
        Environment $twig
    ) {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->flashBag = $flashBag;
        $this->router = $router;
        $this->twig = $twig;
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function execute(Request $request): Response
    {
        $vehicleForm = $this->formFactory->create(VehicleType::class);
        $vehicleForm->handleRequest($request);

        if ($vehicleForm->isSubmitted() && $vehicleForm->isValid()) {
            $vehicle = $vehicleForm->getData();

            $this->entityManager->persist($vehicle);
            $this->entityManager->flush();

            $this->flashBag->add('success', 'vehicle_add_success');

            $redirectToUrl = $this->router->generate('vehicle_view', [
                'id' => $vehicle->getId(),
                'type' => $request->get('type'),
                'plate_number' => $request->get('plate_number'),
            ]);

            return new RedirectResponse($redirectToUrl);
        }

        $content = $this->twig->render('vehicle/create.html.twig', [
            'vehicleForm' => $vehicleForm->createView(),
        ]);

        $response = new Response();
        $response->setContent($content);

        return $response;
    }
}
