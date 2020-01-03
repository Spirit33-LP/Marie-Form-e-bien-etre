<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MarcheNordiqueController extends AbstractController
{
    /**
     * @Route("/activite/marche-nordique", name="marche_nordique")
     */
    public function index()
    {
        return $this->render('activites/marche_nordique.html.twig', [
            'controller_name' => 'MarcheNordiqueController',
        ]);
    }
}
