<?php

namespace App\Controller;

use App\Form\ActiviteType;
use App\Repository\ActivitesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ActivitesController extends AbstractController
{
    /**
     * @Route("/", name="activites")
     */
    public function index(ActivitesRepository $activitesRepository)
    {
        $activites = $activitesRepository->findAll();

        return $this->render('pages/home.html.twig',[
            'activites' => $activites
        ]);
    }

    /**
     * @Route("/activite/pilates", name="pilates")
     */
    public function pilates()
    {
        return $this->render('activites/pilates.html.twig', [
            'controller_name' => 'ActivitesController',
            'current_menu' => 'pilates'
        ]);
    }

    /**
     * @Route("/activite/stretching", name="stretching")
     */
    public function stretching()
    {
        return $this->render('activites/stretching.html.twig', [
            'controller_name' => 'ActivitesController',
            'current_menu' => 'stretching'
        ]);
    }

    /**
     * @Route("/activite/marche_nordique", name="marche_nordique")
     */
    public function marche_nordique()
    {
        return $this->render('activites/marche_nordique.html.twig', [
            'controller_name' => 'ActivitesController',
            'current_menu' => 'marche_nordique'
        ]);
    }

    /**
     * @Route("/activite/course", name="course")
     */
    public function course()
    {
        return $this->render('activites/course.html.twig', [
            'controller_name' => 'ActivitesController',
            'current_menu' => 'course'
        ]);
    }

    /**
     * @Route("/activite/fitness", name="fitness")
     */
    public function fitness()
    {
        return $this->render('activites/fitness.html.twig', [
            'controller_name' => 'ActivitesController',
            'current_menu' => 'fitness'
        ]);
    }

    /**
     * @Route("/activite/evenementiel", name="evenementiel")
     */
    public function evenementiel()
    {
        return $this->render('activites/evenementiel.html.twig', [
            'controller_name' => 'ActivitesController',
            'current_menu' => 'evenementiel'
        ]);
    }

    /**
     * @param ActivitesRepository $activitesRepository
     * @param EntityManagerInterface $entityManager
     * @Route("/admin/activites/supprimer/{id}", name="admin_activites_supprimer_id")
     */

    public function supprimerActivite(ActivitesRepository $activitesRepository, EntityManagerInterface $entityManager, $id)
    {
        $activite = $activitesRepository->find($id);

        // j'utilise l'entity manager avec la méthode remove pour enregistrer la suppression de l'activite
        // dans l'unité de travail
        $entityManager->remove($activite);

        // je valide la suppression en BDD avec la méthode flush
        $entityManager->flush();

        return $this->redirectToRoute('activites');
    }

    /**
     * @param ActivitesRepository $activitesRespository
     * @param EntityManagerInterface $entityManager
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/admin/activites/actualiser/{id}", name="admin_activites_actualiser_id")
     */

    public function actualiserActivite(ActivitesRepository $activitesRepository, EntityManagerInterface $entityManager, Request $request, $id)
    {
        $activite = $activitesRepository->find($id);

        $activiteForm = $this->createForm(ActiviteType::class, $activite);

        $activiteForm->handleRequest($request);

        if ($activiteForm->isSubmitted() && $activiteForm->isValid())
        {
            $activite = $activiteForm->getData();

            $entityManager->persist($activite);

            $entityManager->flush();

            $this->addFlash('succès', "L'activité a bien été actualiser");

            return $this->redirectToRoute('home');
        }

        $activiteFormView = $activiteForm->createView();

        return $this->render('admin/activites/ActiviteFormulaire.html.twig', [
            'activiteFormView' => $activiteFormView
        ]);
    }

    /**
     * @Route("/admin/activites/inserer", name="admin_activites_actualiser")
     */

    public function insererActivite(Request $request, EntityManagerInterface $entityManager, $activiteForm)
    {
        // Je créé une nouvelle activité à associer à mon formulaire pour que mon formulaire l'enregistre en BDD
        $activite = new Activite();

        // Je viens récupérer mon gabarit de formulaire et je l'associe à une nouvelle activité
        $activiteForm = $this->createForm(ActiviteType::class, $activite);

        // (1) : On vérifie si le formulaire a été envoyé (donc si la requête est un POST)
        if ($request->isMethod('POST'))
        {
            // (2) : On demande à la variable contenant le formulaire de récupérer les données de la requête envoyé
            // (donc les données envoyés en POST)
            $activiteForm->handleRequest($request);

            // (3) : On vérifie que les données de formulaire sont valides par rapport à ce qu'on attend
            if ($activiteForm->isValid())
            {
                // (4) : On enregistre notre produit en BDD (avec le percist et le flush)
                $entityManager->persist($activite);
                $entityManager->flush();
            }
        }

        // Je créé un formulaire utilisable dans mon twig avec le formulaire
        $activiteFormView = $activiteForm->createView();

        // 3 : rendre un fichier Twig, en lui passant la variable qui contient la vue du formulaire
        return $this->render('admin/activites/ActiviteInserer.html.twig', [
            'activiteFormView' => $activiteFormView
        ]);
    }

    /**
     * @Route("/activite/{id}", name="activite_id")
     */

    public function afficheActivite(ActivitesRepository $activitesRepository, $id)
    {
        $activite = $activitesRepository->find($id);

        return $this->render('activites/activite_id.html.twig', ['activite' => $activite]);
    }
}