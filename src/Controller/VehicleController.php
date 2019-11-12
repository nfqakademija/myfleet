<?php

namespace App\Controller;

use App\Entity\Vehicle;
use App\Form\Type\VehicleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class VehicleController extends AbstractController
{
    /**
     * @Route("/vehicle", name="vehicle_list")
     */
    public function index()
    {
        $vehicles = $this->getDoctrine()
            ->getRepository(Vehicle::class)
            ->findAll();

        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $jsonVehicles = $serializer->serialize($vehicles, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return $this->render('vehicle/index.html.twig', [
            'controller_name' => 'VehicleController',
            'vehicles' => $jsonVehicles,
        ]);
    }

    /**
     * @Route("/vehicle/{id}", name="vehicle_view", requirements={"id":"\d+"})
     */
    public function view(Vehicle $vehicle)
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $jsonVehicle = $serializer->serialize($vehicle, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);

        return $this->render('vehicle/view.html.twig', [
            'vehicle' => $jsonVehicle
        ]);
    }

    /**
     * @Route("/vehicle/create", name="vehicle_create")
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $vehicle = new Vehicle();
        //$vehicle->setType('semitrailer');
        //$vehicle->setMake('Schmitz');
        //$vehicle->setModel('SKO 24 FP60');
        //$vehicle->setVinCode('WSM00000005227004');
        //$vehicle->setRegistrationPlateNumber('MM348');
        //$vehicle->setFirstRegistration(new \DateTime('2019-03-13'));
        //$vehicle->setAdditionalInformation('Šaldytuvas');

        $form = $this->createForm(VehicleType::class, $vehicle);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vehicle = $form->getData();

            //$entityManager = $this->getDoctrine()->getManager();
            //$entityManager->persist($vehicle);
            //$entityManager->flush();

            return $this->redirectToRoute('vehicle_list');
        }

        return $this->render('vehicle/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/vehicle/{id}/update", name="vehicle_update", requirements={"id":"\d+"})
     */
    public function update(Vehicle $vehicle)
    {
        return $this->render('vehicle/update.html.twig', [
            'vehicle' => $vehicle
        ]);
    }
}
