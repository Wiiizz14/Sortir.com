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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Date;

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
     * @throws \Exception
     */
    public function createEventAction(Request $request, EntityManagerInterface $em, UserInterface $user)
    {
        $sortie = new Sortie();
        // TODO $sortie->gqetDateDebut(new \DateTime('now + 1 day'));
        // TODO $sortie->setDateCloture(new \DateTime('now'));

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
            if ($form->get("Enregistrer")->isClicked()) {
                //Set etat_id = Créee(1)
                $sortie->setEtat($em->getRepository(Etat::class)->find(1));
                //GO BDD
                $em->persist($sortie);
                $em->flush();
                //Flash
                $this->addFlash("success", "Event enregistré avec succes !");
                return $this->redirectToRoute("event_detailEvent", ["id" => $sortie->getid()]);

            } elseif ($form->get("Publier")->isClicked()) {
                //Set etat_id = Ouverte(2)
                $sortie->setEtat($em->getRepository(Etat::class)->find(2));

                //GO BDD
                $em->persist($sortie);
                $em->flush();

                //Flash
                $this->addFlash("success", "Event publié avec succes !");
                return $this->redirectToRoute("event_detailEvent", ["id" => $sortie->getid()]);

            } elseif ($form->get("Annuler")->isClicked()) {
                return $this->redirectToRoute("event_listeEvent");
            }
        }
        return $this->render("createEvent.html.twig", [
            "formCreateEvent" => $form->createView(),
            "villes" => $villes,
            "lieux" => $lieux
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
            ->add('sites', EntityType::class, [
                "class" => 'AppBundle:Site',
                "query_builder" => function (EntityRepository $er) {
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
        $idSite = $request->get("idSite", false);
        $isOrganisateur = filter_var($request->get("isOrganisateur", false), FILTER_VALIDATE_BOOLEAN);
        $isInscrit = filter_var($request->get("isInscrit", false), FILTER_VALIDATE_BOOLEAN);
        $isNotInscrit = filter_var($request->get("isNotInscrit", false), FILTER_VALIDATE_BOOLEAN);
        $isArchive = filter_var($request->get("isArchive", false), FILTER_VALIDATE_BOOLEAN);

        $repoSortie = $em->getRepository(Sortie::class);

        if ($isOrganisateur || $isInscrit || $isNotInscrit || $isArchive) {
            $sorties = $repoSortie->getSortiesByParameters($user, $idSite, $isOrganisateur,
                $isInscrit, $isNotInscrit, $isArchive);
        } else {
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
        $repoParticipant = $detailEvent->getParticipants();

        return $this->render("detailEvent.html.twig", [
            "Detail" => $detailEvent,
            "TabParticipant"=>$repoParticipant
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

        //Pour choix de la ville -> DYNAMISE le LIEU + CODE POSTAL
        $repoVille = $em->getRepository(Ville::class);
        $villes = $repoVille->findAll();

        //Pour choix du lieu -> A DYNAMISER en JS
        $repoLieu = $em->getRepository(Lieu::class);
        $lieux = $repoLieu->findAll();

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($sortie);
            $em->flush();

            $this->addFlash("success", "Event modifié avec succes !");
            return $this->redirectToRoute("event_detailEvent", ["id" => $sortie->getId()]);
        }
        return $this->render("updateEvent.html.twig", [
            "formUpdateEvent" => $form->createView(),
            "villes" => $villes,
            "lieux" => $lieux
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
     * @Route("/api/registerEvent/", name="registerEvent")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Exception
     */
    public function registerEventAction(Request $request, EntityManagerInterface $em, UserInterface $user)
    {

        $id = $request->get("idSortie");
        $repoSortie = $em->getRepository(Sortie::class);
        $sortie = $repoSortie->find($id);

        //Récup des participants déja inscrits
        $tabParticipant = $sortie->getParticipants();

        //Récup des champs : nb inscrits max, nb inscrits, date du jour, date limite
        $nbInscriptionMax = $sortie->getNbInscriptionsMax();
        $nbInscrits = count($sortie->getParticipants());
        $dateDuJour = new \DateTime();
        $dateLimite = $sortie->getDateCloture();

        //Test date
        if ($dateLimite > $dateDuJour) {

            //Test si user déja inscrit
            if (!$tabParticipant->contains($user)) {

                //Test capacité
                if ($nbInscrits < $nbInscriptionMax) {

                    //Entrer le participant dans la sortie
                    $sortie->getParticipants()->add($user);

                    //Entrer la sortie dans le participant
                    $user->getSorties()->add($sortie);

                    //Go Bdd
                    $em->persist($user);
                    $em->persist($sortie);
                    $em->flush();

                    $this->addFlash("success", "Vous êtes bien inscrit !");

                } else {
                    $this->addFlash("warning", "Il n'y a plus de places disponibles !");
                }
            } else {
                $this->addFlash("warning", "Vous êtes déja inscrit !");
            }
        } else {
            $this->addFlash("warning", "la date limite d'inscription est dépassée !");
        }
        return $this->redirectToRoute("event_listeEvent");
    }

    /**
     * @Route("/api/unRegisterEvent/", name="unRegisterEvent")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserInterface $user
     * @return Response
     * @throws \Exception
     */
    public function unRegisterEventAction(Request $request,EntityManagerInterface $em, UserInterface $user)
    {
        $id = $request->get("idSortie");
        $repoSortie = $em->getRepository(Sortie::class);
        /** @var Sortie $sortie */
        $sortie = $repoSortie->findOneById($id);


        if ($sortie && $sortie->getOrganisateur() != $user && $sortie->getDateCloture() > new \DateTime("now"))
        {
            if ($sortie->getParticipants()->contains($user)) {

                $sortie->getParticipants()->removeElement($user);
                $em->persist($sortie);

                // utilisatio de SQL en brut, c'est hardcore, mais ça marche !
                $raw_slq = "DELETE FROM participant_sortie WHERE participant_id = :userId AND sortie_id = :sortieId;";
                $statement = $em->getConnection()->prepare($raw_slq);

                $statement->bindValue('userId', $user->getId());
                $statement->bindValue('sortieId', $sortie->getId());

                $statement->execute();

                try
                {
                    $em->flush();
                    return new Response("true");
                } catch (\Exception $e) {
                    return new Response("error");
                }
            } else {
                return new Response("Vous n'êtes pas inscrit.");
            }
        }
        return new Response("La demande n'a pas pu être satisfaite.");
    }

    /**
     * @Route("/api/cancelEvent/", name="cancelEvent")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param UserInterface $user
     * @return Response
     * @throws \Exception
     */
    public function cancelEventAction(Request $request,EntityManagerInterface $em, UserInterface $user)
    {
        $id = $request->get("idSortie");
        $repoSortie = $em->getRepository(Sortie::class);
        /** @var Sortie $sortie */
        $sortie = $repoSortie->findOneById($id);


        if ($sortie && $sortie->getOrganisateur() == $user && $sortie->getDateCloture() > new \DateTime("now"))
        {
            // 1 et 2 sont "Créée" et "Annulée", attention aux changements de bdd
            if ($sortie->getEtat()->getId() != 1 || $sortie->getEtat()->getId() != 1)
                dump($sortie);
                $repoEtat = $em->getRepository(Etat::class);
                $etat = $repoEtat->findOneById(6);
                $sortie->setEtat($etat);
                dump($sortie);
                $em->persist($sortie);
                try
                {
                    $em->flush();
                    return new Response("true");
                } catch (\Exception $e) {
                    return new Response("error");
                }


        }
        return new Response("La demande n'a pas pu être satisfaite.");
    }

    /**
     * @Route("/api/getLieux", name="getLieux")
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function getLieuxAction(SerializerInterface $serializer, Request $request,EntityManagerInterface $em)
    {
        $villeId = $request->get("villeId");

        $repoVille = $em->getRepository(Ville::class);
        $ville = $repoVille->findOneById($villeId);
        dump($ville);

        if ($ville) {

            $lieux = $ville->getLieux();
            dump($lieux);

            $retour = $serializer->normalize($lieux,
                null,
                ["groups" => ["lieuxGroupe"]]);

            return new JsonResponse($retour);
        }
    }

    /**
     * @Route("/api/getLieu", name="getOneLieu")
     * @param SerializerInterface $serializer
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return JsonResponse
     */
    public function getOneLieuAction(SerializerInterface $serializer, Request $request,EntityManagerInterface $em)
    {
        $lieuId = $request->get("lieuId");

        $repoLieu = $em->getRepository(Lieu::class);
        $lieu = $repoLieu->findOneById($lieuId);

        if ($lieu) {
            $retour = $serializer->normalize($lieu,
                null,
                ["groups" => ["lieuxGroupe"]]);

            return new JsonResponse($retour);
        }
    }

}