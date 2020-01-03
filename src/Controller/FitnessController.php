<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FitnessController extends AbstractController
{
    /**
     * @Route("/activite/fitness", name="fitness")
     */
    public function index()
    {
        return $this->render('activites/fitness.html.twig', [
            'controller_name' => 'FitnessController',
        ]);
    }
}
