<?php

namespace eni\AdminBundle\Controller;

use AppBundle\Entity\Ville;
use AppBundle\Form\VilleType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
     * @Route("/liste", name="liste")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    /**public function listCityAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repoVilles = $em->getRepository(Ville::class);
        $villes = $repoVilles->findAll();

        return $this->render("@eniAdmin/ville/manageCity.html.twig", [
            "villes" => $villes
        ]);
    }*/

    /**
     * Affiche l'ensemble des villes et permet d'en ajouter une nouvelle.
     *
     * @Route("/", name="createCity")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function manageCityAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repoVilles = $em->getRepository(Ville::class);

        // Liste de toutes les villes.
        $villes = $repoVilles->findAll();


        /*
         * Ajout d'une nouvelle ville.
         */
        $city = new Ville();
        // Création du formulaire pour enregistrer une nouvelle Ville en BDD.
        $form = $this->createForm(VilleType::class, $city);
        $form->remove('valider');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($city);

            try
            {
                $em->flush();

                // Message confirmant le succès de l'enregsitrement.
                $this->addFlash("success", "Nouvelle ville enregistrée avec succès");
            }
            catch (\Exception $e)
            {
                // Message indiquant une erreur lors de l'enregistrement.
                $this->addFlash("danger", "Une erreur est survenue lors de l'enregistrement.");
            }

            return $this->redirectToRoute("manageCity_createCity");
        }

        return $this->render("@eniAdmin/Ville/manageCity.html.twig", [
            "villes" => $villes,
            "formCity" => $form->createView()
        ]);
    }

    /**
     * Permet de modifier une ville.
     *
     * @Route("/updateCity/{city}", name="updateCity")
     * @param Request $request
     * @param Ville $city
     * @Method("UPDATE")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateCityAction(Request $request, Ville $city)
    {
        // Création du formulaire
        $formUpdateCity = $this->createForm(VilleType::class, $city);
        $formUpdateCity->remove('ajouter');

        $formUpdateCity->handleRequest($request);

        if ($formUpdateCity->isSubmitted() && $formUpdateCity->isValid())
        {
            $this->getDoctrine()->getManager()->flush();

            // Message confirmant la modification de la ville.
            $this->addFlash("success", "La modification de la ville a bien été effectuée.");
            return $this->redirectToRoute("manageCity_createCity");
        }

        return $this->render("@eniAdmin/ville/updateCity.html.twig", [
            "formUpdateCity" => $formUpdateCity->createView(),
            "ville" => $city
        ]);
    }

    /**
     * Permet de supprimer une ville.
     *
     * @Route("/deleteCity/{city}", name="deleteCity")
     * @param Request $request
     * @param Ville $city
     * @Method("DELETE")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteCityAction(Request $request, Ville $city)
    {
        // Création du formulaire
        $formBuilder = $this->createFormBuilder();
        $formBuilder->add('supprimer', SubmitType::class);

        $formDeleteCity = $formBuilder->getForm();
        $formDeleteCity->handleRequest($request);

        if ($formDeleteCity->isSubmitted() && $formDeleteCity->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($city);

            try
            {
                $em->flush();

                // Message confirmant le succès de la suppression.
                $this->addFlash("success", "La suppression de la ville a bien été effectuée.");
            }
            catch (\Exception $e)
            {
                // Message indiquant une erreur lors de la suppression.
                $this->addFlash("danger", "Une erreur s'est produite. La suppression de la ville n'a pas aboutie.");
            }
            return $this->redirectToRoute("manageCity_createCity");
        }

        return $this->render("@eniAdmin/ville/deleteCity.html.twig", [
            "formDeleteCity" => $formDeleteCity->createView(),
            "ville" => $city
        ]);
    }

}
