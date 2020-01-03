<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PilatesController extends AbstractController
{
    /**
     * @Route("/activite/pilates", name="pilates")
     */
    public function index()
    {
        return $this->render('activites/pilates.html.twig', [
            'controller_name' => 'PilatesController',
            'current_menu' => 'pilates'
        ]);
    }
}
