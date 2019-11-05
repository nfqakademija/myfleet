<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VehicleController extends AbstractController
{
    /**
     * @Route("/vehicle", name="vehicle")
     */
    public function index()
    {
        return $this->render('vehicle/index.html.twig', [
            'controller_name' => 'VehicleController',
        ]);
    }

}
