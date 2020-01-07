<?php

namespace App\Controller;

use App\Repository\GalerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class GalerieController extends AbstractController
{
    /**
     * @Route("/galerie", name="galerie")
     *
     * je créé une méthode galerie et je lui passe en paramètre la classe GalerieRepository
     * et une variable $galerieRepository pour demander à Symfony d'instancier la classe GalerieRepository dans la
     * variable $galerieRepository
     * c'est comme faire $galerieRepository = new GalerieRepository
     */
    public function galerie(GalerieRepository $galerieRepository)
    {
        // A FAIRE :
        // 1 : Recupérer le repository de Galerie
        // 2 : J'utilise la méthode finAll() pour récup toutes les photos de la BDD
        // 3 : Je retourne un fichier Twig, en lui envoyant la variable qui contient toutes les photos

        // Je viens chercher toutes les photos de ma table galerie (donc je fais une requête SQL SELECT)
        // en utilisant la méthode findAll() de la classe galerieRepository (qui a été créée automatiquement par Symfony
        // lors du make:entity)
        $galerie = $galerieRepository->findAll();

        return $this->render('pages/galerie.html.twig', [
            'galerie' => $galerie,
        ]);
    }
}