<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Etat;
use AppBundle\Entity\Lieu;
use AppBundle\Entity\Participant;
use AppBundle\Entity\Site;
use AppBundle\Entity\Sortie;
use AppBundle\Entity\Ville;
use AppBundle\Form\SortiesType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use mysql_xdevapi\Exception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class EventController
 * @Route("/", name="event_")
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

        //Pour choix de la ville -> DYNAMISE le LIEU + CODE POSTAL
        $repoVille = $em->getRepository(Ville::class);
        $villes = $repoVille->findAll();

        //Pour choix du lieu -> A DYNAMISER en JS
        $repoLieu = $em->getRepository(Lieu::class);
        $lieux = $repoLieu->findAll();


        if ($form->isSubmitted() && $form->isValid()) {

            //Setters : l'organisateur + etat_id + site_id
            $sortie->setOrganisateur($user);
            $sortie->setIsEtatSortie(true);
            $sortie->setSite($user->getSite());

            //Aiguillage
            if($form->get("Enregistrer")->isClicked()){
                //Set etat_id = Créee(1)
                $sortie->setEtat($em->getRepository(Etat::class)->find(1));
                //GO BDD
                $em->persist($sortie);
                $em->flush();
                //Flash
                $this->addFlash("success", "Event enregistré avec succes !");
                return $this->redirectToRoute("event_detailEvent", ["id"=>$sortie->getid()]);

            } elseif ($form->get("Publier")->isClicked()) {
                //Set etat_id = Ouverte(2)
                $sortie->setEtat($em->getRepository(Etat::class)->find(2));

                //GO BDD
                $em->persist($sortie);
                $em->flush();

                //Flash
                $this->addFlash("success", "Event publié avec succes !");
                return $this->redirectToRoute("event_detailEvent", ["id"=>$sortie->getid()]);

            } elseif($form->get("Annuler")->isClicked()) {
                return $this->redirectToRoute("event_listeEvent");
            }
        }
        return $this->render("createEvent.html.twig", [
            "formCreateEvent" => $form->createView(),
            "villes"=>$villes,
            "lieux"=>$lieux
        ]);
    }

    /**
     * @Route("/listEvent", name="listeEvent")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listEventAction(Request $request, EntityManagerInterface $em)
    {
        // tableau des sites pour le select
        $sites = $em->getRepository(Site::class)->findAll();

        // formulaire de requete des checkboxes
        $formBuilder = $this->createFormBuilder()
        ->add('sites',  EntityType::class, [
                "class" => 'AppBundle:Site',
                "query_builder" => function(EntityRepository $er) {
                    return $er->createQueryBuilder("u")
                        ->orderBy('u.nom', "ASC");
                },
                "choice_label" => "nom"
            ])
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
        ]);

        $form = $formBuilder->getForm();

        return $this->render("listEvent.html.twig", [
            "form" => $form->createView(),
            "sites" => $sites
        ]);
    }

    /**
     * @Route("/api/searchEvent", name="api_getList")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserInterface $user
     * @param SerializerInterface $serializer
     * @return Response
     * @throws \Exception
     */
    public function getAction(Request $request, EntityManagerInterface $em, UserInterface $user,
                              SerializerInterface $serializer)
    {
        $idSite = $request->get("idSite", false) ;
        $isOrganisateur = filter_var($request->get("isOrganisateur", false), FILTER_VALIDATE_BOOLEAN);
        $isInscrit = filter_var($request->get("isInscrit", false), FILTER_VALIDATE_BOOLEAN);
        $isNotInscrit = filter_var($request->get("isNotInscrit", false), FILTER_VALIDATE_BOOLEAN);
        $isArchive = filter_var($request->get("isArchive", false), FILTER_VALIDATE_BOOLEAN);

        $repoSortie = $em->getRepository(Sortie::class);

        if ($isOrganisateur || $isInscrit || $isNotInscrit || $isArchive)
        {
            $sorties = $repoSortie->getSortiesByParameters($user, $idSite, $isOrganisateur,
                $isInscrit, $isNotInscrit, $isArchive);
        } else
        {
            $sorties = $repoSortie->getSortiesOnlyBySite($idSite);
        }

        $retour = $serializer->normalize($sorties,
            null,
            ["groups" => ["sortieGroupe"]]);

        return new JsonResponse($retour);
    }

    /**
     * @Route("/detailEvent/{id}", name="detailEvent", requirements={"id":"\d+"})
     * @param $id
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailEventAction($id, EntityManagerInterface $em)
    {

        $repoId = $em->getRepository(Sortie::class);
        $detailEvent = $repoId->find($id);

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

            $em->persist($sortie);
            $em->flush();

            $this->addFlash("success", "Event modifié avec succes !");
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

            $em->remove($sortie);
            $em->flush();

            $this->addFlash("success", "Event supprimé avec succès !");
            return $this->redirectToRoute("event_listeEvent");
        }

        return $this->render("deleteEvent.html.twig", [
            "sortie" => $sortie,
            "formDeleteEvent" => $form->createView()
        ]);
    }

    /**
     * @Route("/api/registerEvent/{id}", name="registerEvent", requirements={"id":"\d+"}))
     * @param EntityManagerInterface $em
     * @param UserInterface $user
     * @param $id
     * @return void
     * @throws \Exception
     */
    public function registerEventAction(EntityManagerInterface $em, UserInterface $user, $id){

        $repoSortie= $em->getRepository(Sortie::class);
        $sortie = $repoSortie->find($id);

        //Récup des participants
        $participant = new Participant();
        $repoParticipant = $em->getRepository(Participant::class);
        $tabParticipant = $sortie->getParticipants();


        // Récup des champs : nb inscrits max, nb inscrits, date limite
        $nbInscriptionMax = $sortie->getNbInscriptionsMax();
        $nbInscrits = count($sortie->getParticipants());
        $dateDuJour = new \DateTime();
        $dateLimite = $sortie->getDateCloture();

        //Entrer le participant dans la sortie
        $sortie->getParticipants()->add($user);

        //Entrer la sortie dans le participant
        $user->getSorties()->add($sortie);

        //Go Bdd
        $em->persist($user);
        $em->persist($sortie);
        $em->flush();

        //TODO ajouter les contraintes





    }

}