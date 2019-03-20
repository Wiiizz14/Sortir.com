<?php

namespace eni\AdminBundle\Controller;

use AppBundle\Entity\Ville;
use AppBundle\Form\VilleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class VilleController
 *
 * @Route("/admin/manageCity", name="manageCity_")
 * @package eni\AdminBundle\Controller
 */
class VilleController extends Controller
{
    /**
     * @Route("/", name="liste")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listCityAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repoVilles = $em->getRepository(Ville::class);
        $villes = $repoVilles->findAll();

        return $this->render("@eniAdmin/ville/manageCity.html.twig", [
            "villes" => $villes
        ]);
    }

    /**
     * @Route("/createCity", name="createCity")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createCityAction(Request $request)
    {
        $city = new Ville();

        // Crétation du formulaire pour enregistrer une nouvelle Ville en BDD.
        $form = $this->createForm(VilleType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($city);
            $em->flush();

            // Message confirmant le succès de l'enregsitrement.
            $this->addFlash("success", "Nouvelle Ville enregistrée avec succès");
            // Message indiquant une erreur lors de l'enregistrement.
            $this->addFlash("danger", "Une erreur est survenue lors de l'enregistrement.");

            return $this->redirectToRoute("manageCity_liste");
        }

        return $this->redirectToRoute("manageCity_liste", [
            "form" => $form->createView()
        ]);
    }
}
