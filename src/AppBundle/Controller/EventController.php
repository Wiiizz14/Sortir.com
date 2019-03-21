<?php

/*Revoir : render et redirectToRoute -> manque les twig*/

namespace AppBundle\Controller;

use AppBundle\Entity\Sortie;
use AppBundle\Form\SortiesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createEventAction(Request $request, EntityManagerInterface $em)
    {

        $sortie = new Sortie();
        $form = $this->createForm(SortiesType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash("success", "Event crée avec succès !");

            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute("event_detailEvent", [
            ]);
        }

        return $this->render("createEvent.html.twig", [
            "formCreateEvent" => $form->createView()
        ]);
    }

    /**
     * @Route("/", name="listeEvent")
     * @param EntityManagerInterface $em
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listEventAction(EntityManagerInterface $em)
    {

        $repoSortie = $em->getRepository(Sortie::class);
        $sortie = $repoSortie->findAll();

        return $this->render("listEvent.html.twig", [
            "sorties" => $sortie
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