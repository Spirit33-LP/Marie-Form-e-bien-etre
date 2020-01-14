<?php

namespace App\Controller;

use App\Repository\GalerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/admin/galerie/supprimer/{id}", name="admin_galerie_supprimer_id")
     */

    public function supprimerPhoto(GalerieRepository $galerieRepository, EntityManagerInterface $entityManager, $id)
    {
        $photo = $galerieRepository->find($id);

        $entityManager->remove($photo);

        $entityManager->flush();

        return $this->render('admin/galerie/PhotoSupprimer.html.twig', [
            'photo' => $photo
        ]);
    }

    /**
     * @Route ("/admin/galerie/actualiser/{id}", name="admin_galerie_actualiser_id")
     */

    public function actualiserPhoto(GalerieRepository $galerieRepository, EntityManagerInterface $entityManager, Request $request, $id)
    {
        $photo = $galerieRepository->find($id);

        $photoForm = $this->createForm(GalerieType::class, $photo);

        $photoForm->handleRequest($request);

        if ($photoForm->isSubmitted() && $photoForm->isValid())
        {
            $photo = $photoForm->getData();

            $entityManager->persist($photo);

            $entityManager->flush();

            $this->addFlash('succès', "La photo a été actualiser");

            return$this->redirectToRoute('galerie');
        }

        $photoFormView = $photoForm->createView();

        return $this->render('admin/galerie/GalerieFormulaire.html.twig', [
            'photoFormView' => $photoFormView
        ]);
    }

    /**
     * @Route ("/admin/galerie/inserer", name="admin_galerie_actualiser")
     */

    public function insererPhoto(Request $request, EntityManagerInterface $entityManager)
    {
        $photo = new photo();

        $photoForm = $this->createForm(GalerieType::class, $photo);

        if ($request->isMethod('POST'))
        {
            $photoForm->handleRequest($request);

            if($photoForm->isValid())
            {
                $entityManager->persist($photo);
                $entityManager->flush();
            }
        }

        $photoFormView = $photoForm->createView();

        return $this->render('admin/galerie/PhotoInserer.html.twig', [
            'photoFormView' => $photoFormView
        ]);
    }
}