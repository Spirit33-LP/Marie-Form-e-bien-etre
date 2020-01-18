<?php

namespace App\Controller;

use App\Entity\Activites;
use App\Form\ActiviteType;
use App\Repository\ActivitesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * @Route("/activite/{id}", name="activite_id")
     */

    public function ActiviteAffiche(ActivitesRepository $activitesRepository, $id)
    {
        $activite = $activitesRepository->find($id);

        return $this->render('activites/activite_id.html.twig', ['activite' => $activite]);
    }

    /*
     * @Route("/activite/pilates", name="pilates")
     */
    /* public function pilates()
    {
        return $this->render('activites/pilates.html.twig', [
            'controller_name' => 'ActivitesController',
            'current_menu' => 'pilates'
        ]);
    } */

    /**
     * @Route("/admin/activite/ajouter", name="admin_activite_ajouter")
     */

    public function ActiviteInserer(Request $request, EntityManagerInterface $entityManager)
    {
        // Je créé une nouvelle activité à associer à mon formulaire pour que mon formulaire l'enregistre en BDD
        $activite = new Activites();

        // Je viens récupérer mon gabarit de formulaire et je l'associe à une nouvelle activité
        $activiteForm = $this->createForm(ActiviteType::class, $activite);

        // (1) : Je vérifie si le formulaire a été envoyé (donc si la requête est un POST)
        if ($request->isMethod('POST'))
        {
            // (2) : Je demande à la variable contenant le formulaire de récupérer les données de la requête envoyé
            // (donc les données envoyés en POST)
            $activiteForm->handleRequest($request);

            // (3) : Je vérifie que les données de formulaire sont valides par rapport à ce que j'attends
            if ($activiteForm->isValid())
            {
                // (4) : J'enregistre mon activité en BDD (avec le percist qui stock les informations et le flush qui envoie dans la BDD)
                $entityManager->persist($activite);
                $entityManager->flush();

                // Une fois envoyé en BDD, je retourne sur la page d'acceuil sans manipulation
                return $this->redirectToRoute('home');

            }
        }

        // Je créé un formulaire utilisable dans mon twig avec le formulaire
        $activiteFormView = $activiteForm->createView();

        // 3 : rendre un fichier Twig, en lui passant la variable qui contient la vue du formulaire
        return $this->render('admin/activites/ActiviteAjouter.html.twig', [
            'activiteFormView' => $activiteFormView
        ]);
    }

    /**
     * @param ActivitesRepository $activitesRepository
     * @param EntityManagerInterface $entityManager
     * @Route("/admin/activite/supprimer/{id}", name="admin_activite_supprimer_id")
     */

    public function ActiviteSupprimer(ActivitesRepository $activitesRepository, EntityManagerInterface $entityManager, $id)
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
     * @Route("/admin/activite/modifier/{id}", name="admin_activite_modifier_id")
     */

    public function ActiviteModifier(ActivitesRepository $activitesRepository, EntityManagerInterface $entityManager, Request $request, $id)
    {
        $activite = $activitesRepository->find($id);

        $activiteForm = $this->createForm(ActiviteType::class, $activite);

        $activiteForm->handleRequest($request);

        if ($activiteForm->isSubmitted() && $activiteForm->isValid())
        {
            $activite = $activiteForm->getData();

            $entityManager->persist($activite);

            $entityManager->flush();

            $this->addFlash('succès', "L'activité a bien été modifier");

            return $this->redirectToRoute('home');
        }

        $activiteFormView = $activiteForm->createView();

        return $this->render('admin/activites/ActiviteModifier.html.twig', [
            'activiteFormView' => $activiteFormView
        ]);
    }


}