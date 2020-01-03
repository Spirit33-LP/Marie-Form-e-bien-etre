<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StretchingController extends AbstractController
{
    /**
     * @Route("/activite/stretching", name="stretching")
     */
    public function index()
    {
        return $this->render('activites/stretching.html.twig', [
            'controller_name' => 'StretchingController',
        ]);
    }
}
