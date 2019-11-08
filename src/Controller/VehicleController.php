<?php

namespace App\Controller;

use App\Entity\Vehicle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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

        return $this->render('vehicle/index.html.twig', [
            'controller_name' => 'VehicleController',
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * @Route("/vehicle/{id}", name="vehicle_view", requirements={"id":"\d+"})
     */
    public function view(Vehicle $vehicle)
    {
        return $this->render('vehicle/view.html.twig', [
            'vehicle' => $vehicle
        ]);
    }
}
