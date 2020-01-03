<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CourseController extends AbstractController
{
    /**
     * @Route("/activite/course", name="course")
     */
    public function index()
    {
        return $this->render('activites/course.html.twig', [
            'controller_name' => 'CourseController',
        ]);
    }
}
