<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EvenementielController extends AbstractController
{
    /**
     * @Route("/activite/evenementiel", name="evenementiel")
     */
    public function index()
    {
        return $this->render('activites/evenementiel.html.twig', [
            'controller_name' => 'EvenementielController',
        ]);
    }
}
