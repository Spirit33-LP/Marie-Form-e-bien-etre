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
     *
     * je créé une méthode index et je lui passe en paramètre la classe ActivitesRepository et une variable $activitesRepository
     * pour demander à Symfony d'instancier la classe ActivitesRepository dans la variable $activitesRepository
     * c'est comme faire $activitesRepository = new ActivitesRepository
     */

    public function index(ActivitesRepository $activitesRepository)
    {

        // Je viens chercher toutes les activites de ma table activites (donc je fais une requête SQL SELECT)
        // en utilisant la méthode findAll() de la classe activitesRepository (que j'ai créer lors du make:entity)

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

        // Recupérer le repository de la table activites
        // J'utilise activitesRepository et la méthode find() pour trouver l'activité en fonction de la wildcard, donc ici "$id"
        // $activitesRepository->find($id)
        $activite = $activitesRepository->find($id);

        // Avec la méthode render je renvoie vers une view
        // Je retourne un fichier Twig, en lui passant l'activite récupérer
        return $this->render('pages/activite_id.html.twig', [
            'activite' => $activite
        ]);
    }

    /**
     * @Route("/admin/activite/ajouter", name="admin_activite_ajouter")
     */

    public function ActiviteInserer(Request $request, EntityManagerInterface $entityManager)
    {
        // Je créé une nouvelle activité à associer à mon formulaire pour que mon formulaire l'enregistre en BDD
        $activite = new Activites();

        // Je viens récupérer mon gabarit de formulaire et je l'associe à une nouvelle activité
        $activiteForm = $this->createForm(ActiviteType::class, $activite);

        // Je vérifie si le formulaire a été envoyé (donc si la requête est un POST (elle transmet les informations du formulaire de manière masquée mais non cryptée))
        if ($request->isMethod('POST'))
        {
            // Je demande à la variable contenant le formulaire de récupérer les données de la requête envoyé
            // (donc les données envoyés en POST)
            $activiteForm->handleRequest($request);

            // Je vérifie que les données de formulaire sont valides par rapport à ce que j'attends
            if ($activiteForm->isValid())
            {
                // J'enregistre mon activité en BDD (avec le percist qui stock les informations et le flush qui envoie dans la BDD)
                $entityManager->persist($activite);
                $entityManager->flush();

                // Avec la méthode redirectToRoute je renvoie vers une route
                // Une fois envoyé en BDD, ça me renvoie sur la page d'acceuil
                return $this->redirectToRoute('home');

            }
        }

        // Je créé un formulaire utilisable dans mon twig avec le formulaire
        $activiteFormView = $activiteForm->createView();

        // Rendre un fichier Twig, en lui passant la variable qui contient la vue du formulaire
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

        // j'utilise l'entity manager avec la méthode remove pour enregistrer la suppression de l'activite dans l'unité de travail
        $entityManager->remove($activite);

        // je valide la suppression en BDD avec la méthode flush
        $entityManager->flush();

        return $this->render('admin/activites/ActiviteSupprimer.html.twig', [
            'activite' => $activite
        ]);
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

        // (1) récuperer l'activité' à modifier
        // (2) modifier l'activité
        // (3) sauvegarder la modification

        $activite = $activitesRepository->find($id);

        // J'utilise la méthode createForm pour créer le gabarit / le constructeur de
        // formulaire pour l'activité : ActiviteType et je lui associe mon entité activité vide
        $activiteForm = $this->createForm(ActiviteType::class, $activite);

        // demande de traitement de la requête
        $activiteForm->handleRequest($request);

        // Si les données de mon formulaire sont valides (que les types rentrés dans les inputs sont bons, que tous les
        // champs obligatoires sont remplis)
        if ($activiteForm->isSubmitted() && $activiteForm->isValid())
        {
            $activite = $activiteForm->getData();

            // J'enregistre en BDD ma variable $activite qui n'est plus vide, car elle a été remplie avec les données du formulaire
            $entityManager->persist($activite);

            $entityManager->flush();

            $this->addFlash('succès', "L'activité a bien été modifier");

            return $this->redirectToRoute('home');
        }
        // à partir de mon gabarit, je crée la vue de mon formulaire
        $activiteFormView = $activiteForm->createView();

        // je retourne un fichier twig, et je lui envoie ma variable qui contient mon formulaire
        return $this->render('admin/activites/ActiviteModifier.html.twig', [
            'activiteFormView' => $activiteFormView
        ]);
    }

}