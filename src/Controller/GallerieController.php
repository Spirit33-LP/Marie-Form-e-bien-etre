<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GallerieController extends AbstractController
{
    /**
     * @Route("/gallerie", name="gallerie")
     */
    public function gallerie()
    {
        return $this->render('pages/gallerie.html.twig', [
            'controller_name' => 'GallerieController',
        ]);
    }
}