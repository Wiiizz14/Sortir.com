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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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
     * Permet de lister l'ensemble des sites et de pouvoir en ajouter si nécessaire.
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
        $form->remove("valider");

        // Insertion de la requête utilisateur dans le formulaire.
        $form->handleRequest($request);

        // Si le formulaire est submit et valide
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

        return $this->render("@eniAdmin/Site/manageSite.html.twig", [
            "sites" => $sites,
            "formSite" => $form->createView()
        ]);
    }

    /**
     * Permet de modifier un site.
     *
     * @Route("/updateSite/{site}", name="updateSite")
     * @param Request $request
     * @param Site $site
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function updateSiteAction(Request $request, Site $site)
    {
        // Création du formulaire
        $formUpdateSite = $this->createForm(SiteType::class, $site);
        $formUpdateSite->remove("ajouter");
        // Insertion de la requête utilisateur dans le formulaire.
        $formUpdateSite->handleRequest($request);

        // Si le formulaire est submit et valide
        if ($formUpdateSite->isSubmitted() && $formUpdateSite->isValid())
        {
            $this->getDoctrine()->getManager()->flush();

            // Message confirmant le succès de la modification.
            $this->addFlash("success", "La modification du site a bien été effectuée.");

            return $this->redirectToRoute("manageSite_createSite");
        }

        return $this->render("@eniAdmin/Site/updateSite.html.twig", [
            "formUpdateSite" => $formUpdateSite->createView(),
            "site" => $site
        ]);
    }

    /**
     * Permet de supprimer un site.
     *
     * @Route("/delete/{site}", name="deleteSite")
     * @param Request $request
     * @param Site $site
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteSiteAction(Request $request, Site $site)
    {
        //Création du formulaire
        $formBuilder = $this->createFormBuilder();
        $formBuilder->add('supprimer', SubmitType::class);

        $formDeleteSite = $formBuilder->getForm();
        // Insertion de la requête utilisateur dans le formulaire.
        $formDeleteSite->handleRequest($request);

        // Si le formulaire est submit et valide
        if ($formDeleteSite->isSubmitted() && $formDeleteSite->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($site);
            $em->flush();

            try
            {
                $em->flush();

                // Message confirmant le succès de la suppression.
                $this->addFlash("success", "La suppression du site a bien été effectuée.");
                return $this->redirectToRoute('manageSite_createSite');
            }
            catch (\Exception $e)
            {
                // Message indiquant une erreur lors de la suppression.
                $this->addFlash("danger", "Une erreur s'est produite. La suppression du n'a pas aboutie.");
            }
        }
        return $this->render("@eniAdmin/Site/deleteSite.html.twig", [
            "formDeleteSite" => $formDeleteSite->createView(),
            "site" => $site
        ]);
    }
}