<?php

namespace App\Controller;

use App\Dto\FiltersData;
use App\Entity\Vehicle;
use App\Form\Type\VehicleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class VehicleController extends AbstractController
{
    /**
     * @Route("/list", name="vehicle_list")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $filtersData = new FiltersData();
        $filtersData->setVehicleType($request->get('vehicle_type'));
        $filtersData->setRegistrationPlateNumberPart($request->get('registration_plateNumber_part'));

        $vehicles = $this->getDoctrine()
            ->getRepository(Vehicle::class)
            ->filterVehicles($filtersData);

        return $this->render('vehicle/index.html.twig', [
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * @Route("/vehicle/{id}", name="vehicle_view", requirements={"id":"\d+"})
     * @param Vehicle $vehicle
     * @return Response
     */
    public function view(Vehicle $vehicle)
    {
        return $this->render('vehicle/view.html.twig', [
            'vehicle' => $vehicle
        ]);
    }

    /**
     * @Route("/vehicle/create", name="vehicle_create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $form = $this->createForm(VehicleType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vehicle = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vehicle);
            $entityManager->flush();

            $this->addFlash('success', 'Transporto priemonė sekmingai pridėta!');

            return $this->redirectToRoute('vehicle_list');
        }

        return $this->render('vehicle/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/vehicle/{id}/update", name="vehicle_update", requirements={"id":"\d+"})
     * @param Request $request
     * @param Vehicle $vehicle
     * @return RedirectResponse|Response
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $form = $this->createForm(VehicleType::class, $vehicle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vehicle = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vehicle);
            $entityManager->flush();

            $this->addFlash('success', 'Transporto priemonė sekmingai atnaujinta!');

            return $this->redirectToRoute('vehicle_view', ['id' => $vehicle->getId()]);
        }

        return $this->render('vehicle/update.html.twig', [
            'vehicle' => $vehicle,
            'form' => $form->createView(),
        ]);
    }
}
