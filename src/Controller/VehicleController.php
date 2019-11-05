<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VehicleController extends AbstractController
{
    /**
     * @Route("/vehicle", name="vehicle_list")
     */
    public function index()
    {
        return $this->render('vehicle/index.html.twig', [
            'controller_name' => 'VehicleController',
        ]);
    }

    /**
     * @Route("/vehicle/{id}", name="vehicle_view"
     */
    public function view($id)
    {
        return $this->render('vehicle/view', [
            'controller_name' => 'VehicleController',
        ]);
    }
}
