<?php
/**
 * Created by PhpStorm.
 * User: Nonosse
 * Date: 24/03/2019
 * Time: 18:21
 */

namespace eni\AdminBundle\Controller;


use AppBundle\Entity\Site;
use AppBundle\Form\SiteType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SiteController
 *
 * @Route("/admin/manageSite", name="manageSite_")
 * @package eni\AdminBundle\Controller
 */
class SiteController extends Controller
{
    /**
     *
     * @Route("/", name="createSite")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function manageCityAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repoSites = $em->getRepository(Site::class);

        // Liste de tous les sites.
        $sites = $repoSites->findAll();


        /*
         * Ajout d'un nouveau site.
         */
        $site = new Site();
        // Création du formulaire pour enregistrer un nouveau site en BDD.
        $form = $this->createForm(SiteType::class, $site);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($site);

            try
            {
                $em->flush();

                // Message confirmant le succès de l'enregsitrement.
                $this->addFlash("success", "Nouveau site de rattachement enregistré avec succès");
            }
            catch (\Exception $e)
            {
                // Message indiquant une erreur lors de l'enregistrement.
                $this->addFlash("danger", "Une erreur est survenue lors de l'enregistrement.");
            }

            return $this->redirectToRoute("manageSite_createSite");
        }

        return $this->render("manageSite.html.twig", [
            "villes" => $sites,
            "formSite" => $form->createView()
        ]);
    }
}