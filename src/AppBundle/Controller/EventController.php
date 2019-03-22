<?php

/*Revoir : render et redirectToRoute -> manque les twig*/

namespace AppBundle\Controller;

use AppBundle\Entity\Lieu;
use AppBundle\Entity\Site;
use AppBundle\Entity\Sortie;
use AppBundle\Entity\Ville;
use AppBundle\Form\SortiesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class EventController
 * @Route("/event", name="event_")
 * @package AppBundle\Controller
 */
class EventController extends Controller
{

    /**
     * @Route("/createEvent", name="createEvent")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createEventAction(Request $request, EntityManagerInterface $em, UserInterface $user)
    {

        $sortie = new Sortie();
        $form = $this->createForm(SortiesType::class, $sortie);

        /* A RE-INSERER SI MEP DE LA MODALE
        $form->remove("Enregistrer");
        $form->remove("Publier");
        $form->remove("Annuler");
        */

        $form->handleRequest($request);

        //Pour choix de la ville
        $repoVille = $em->getRepository(Ville::class);
        $villes = $repoVille->findAll();

        //Pour choix du lieu
        $repoLieu = $em->getRepository(Lieu::class);
        $lieux = $repoLieu->findAll();

        if ($form->isSubmitted() && $form->isValid()) {

            //Setters pour l'organisateur et l'état de la sortie
            $sortie->setOrganisateur($user);
            $sortie->setIsEtatSortie(true);
            dump($form);
            dump($sortie);
            $test = $request->get("ville");
            dump($test);
            die();
            //GO
            $em->persist($sortie);
            $em->flush();

            $this->addFlash("success", "Event crée avec succès !");
            return $this->redirectToRoute("event_detailEvent", ["id"=>$sortie->getid()
            ]);
        }
        return $this->render("createEvent.html.twig", [
            "formCreateEvent" => $form->createView(),
            "villes"=>$villes,
            "lieux"=>$lieux
        ]);
    }

    /**
     * @Route("/", name="listeEvent")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listEventAction(Request $request, EntityManagerInterface $em, UserInterface $user)
    {
        // tableau des sites pour le select
        $sites = $em->createQueryBuilder()
            ->select("site")
            ->from(Site::class, "site")
            ->getQuery()->getResult();

        // formulaire de requete des checkboxes
        $formBuilder = $this->createFormBuilder()
            ->add('organisateur', CheckboxType::class, [
                "required" => false
            ])
            ->add('isInscrit', CheckboxType::class, [
                "required" => false
            ])
            ->add('isNotInscrit', CheckboxType::class, [
        "required" => false
            ])
            ->add('archive', CheckboxType::class, [
        "required" => false
            ])
            ->add("Valider", SubmitType::class);

        $form = $formBuilder->getForm();
        $repoSortie = $em->getRepository(Sortie::class);

        $findByOrganisateur = array();
        $findByInscription = array();
        $findByNonInscription = array();
        $findAllOthers = array();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            if ($form->get("organisateur"))
            {
                $findByOrganisateur = $repoSortie->getSortiesByAuthor($user);
            }
            if ($form->get("isInscrit"))
            {
                $findByInscription = $repoSortie->getSortiesByRegistering($user);
            }
            if ($form->get("isNotInscrit"))
            {
                $findByNonInscription = $repoSortie->getSortiesByNotRegistered($user);
            }
            if ($form->get("archive"))
            {
                $findAllOthers = "3";
            }
//            dump($findByInscription);
//            dump($findByOrganisateur);
//            dump($findByNonInscription);
//            dump($findAllOthers);
//            die();
//            $sorties = $findAllOthers + $findByNonInscription;
//                + $findByOrganisateur + $findByInscription;

//            dump($sorties);
//            die();

        }
        else
        {
            $sorties = $repoSortie->findAll();
        }



        return $this->render("listEvent.html.twig", [
            "sorties" => $sorties,
            "form" => $form->createView(),
            "sites" => $sites
        ]);
    }

    /**
     * @Route("/detailEvent/{sortie}", name="detailEvent", requirements={"id":"\d+"})
     * @param Sortie $sortie
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailEventAction(Sortie $sortie, EntityManagerInterface $em)
    {

        $repoId = $em->getRepository(Sortie::class);
        $detailEvent = $repoId->find($sortie);

        return $this->render("detailEvent.html.twig", [
            "Detail" => $detailEvent
        ]);
    }

    /**
     * @Route("/updateEvent/{sortie}", name="updateEvent", requirements={"sortie":"\d+"})
     * @param Request $request
     * @param Sortie $sortie
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateEventAction(Request $request, Sortie $sortie, EntityManagerInterface $em)
    {

        $form = $this->createForm(SortiesType::class, $sortie);



        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash("success", "Event modifié avec succes !");

            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute("event_detailEvent", ["id" => $sortie->getId()]);
        }
        return $this->render("updateEvent.html.twig", [
            "formUpdateEvent" => $form->createView()
        ]);
    }

    /**
     * @Route("/deleteEvent/{sortie}", name="deleteEvent", requirements={"sortie":"\d+"})
     * @param Request $request
     * @param Sortie $sortie
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteEventAction(Request $request, Sortie $sortie, EntityManagerInterface $em)
    {

        $formBuilder = $this->createFormBuilder();
        $formBuilder->add("OK", SubmitType::class);

        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $this->addFlash("success", "Event supprimé avec succès !");

            $em->remove($sortie);
            $em->flush();

            return $this->redirectToRoute("event_listeEvent");
        }

        return $this->render("deleteEvent.html.twig", [
            "sortie" => $sortie,
            "formDeleteEvent" => $form->createView()
        ]);
    }
}