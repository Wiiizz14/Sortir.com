<?php

namespace eni\AdminBundle\Controller;

use AppBundle\Entity\Participant;
use AppBundle\Form\ParticipantType;

use Cocur\Slugify\Slugify;
use Intervention\Image\ImageManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class AdminController
 * @package eni\AdminBundle\Controller
 * @Route("/admin", name="admin_")
 */
class AdminController extends Controller
{
    /**
     * @Route("/createUser", name="createUser")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createUserAction(Request $request)
    {
        $passwordEncoder = $this->get('security.password_encoder');
        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $participant->setIsActif(true);
            $participant->getIsAdministrateur() ?
                $participant->setRoles(['ROLE_ADMIN']) : $participant->setRoles(['ROLE_USER']);
            // TODO : générer le salt de manière plus complexe
            $participant->setSalt("poivre");
            $toSavePassword = $passwordEncoder->encodePassword($participant, $participant->getPassword());
            $participant->setPassword($toSavePassword);



            if ($participant->getUrlPhoto() != null)
            {
                $filename = "logo-".$participant->getUsername()."-".$participant->getId().".".$participant->getUrlPhoto()->guessExtension();

                $image = $participant->getUrlPhoto();

                // pour l'activer : composer require intervention/image
                $imageManager = new ImageManager();
                $imageOrigin = $imageManager->make($image);
                $imageOrigin->resize(300, null, function($constraint){
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });

                $imageOrigin->save($this->get('kernel')->getProjectDir()."/web/images/upload/".$filename);

                $participant->setUrlPhoto("/images/photos/".$filename);
            }

            $em->persist($participant);
            $em->flush();

            $this->addFlash("success", "Le participant a bien été enregistré.");
            return $this->redirectToRoute("admin_detailUser", [
                "participant"=>$participant->getId()
            ]);
        }
        return $this->render('@eniAdmin/Admin/create_user.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/listUser", name="listUser")
     */
    public function listUserAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repoListeParticipants = $em->getRepository(Participant::class);
        $listeParticipants = $repoListeParticipants->findAll();
        return $this->render('@eniAdmin/Admin/list_user.html.twig', [
            "listeParticipants"=>$listeParticipants
        ]);
    }

    /**
     * @Route("/detailUser/{participant}", name="detailUser")
     * @param Participant $participant
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailUserAction(Participant $participant)
    {

        return $this->render('@eniAdmin/Admin/detail_user.html.twig', [
            "participant"=>$participant
        ]);
    }

    /**
     * @Route("/updateUser/{participant}", name="updateUser")
     * @param Request $request
     * @param Participant $participant
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateUserAction(Request $request, Participant $participant)
    {
        $saveUrlPhoto = $participant->getUrlPhoto();
        $form = $this->createForm(ParticipantType::class, $participant);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $participant->setIsActif(true);
            $participant->getIsAdministrateur() ?
                $participant->setRoles(['ROLE_ADMIN']) : $participant->setRoles(['ROLE_USER']);

            if ($participant->getUrlPhoto() != null)
            {
                $filename = "logo-".$participant->getUsername()."-".$participant->getId().".".$participant->getUrlPhoto()->guessExtension();

                $image = $participant->getUrlPhoto();

                // pour l'activer : composer require intervention/image
                $imageManager = new ImageManager();
                $imageOrigin = $imageManager->make($image);
                $imageOrigin->resize(300, null, function($constraint){
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                //supprimer l'ancienne photo
                $filesystem = new Filesystem();
                $filesystem->remove($saveUrlPhoto);

                $imageOrigin->save($this->get('kernel')->getProjectDir()."/web/images/upload/".$filename);

                $participant->setUrlPhoto("/images/upload/".$filename);
            }
            else
            {
                $participant->setUrlPhoto($saveUrlPhoto);
            }

            $em->persist($participant);
            $em->flush();

            $this->addFlash("success", "Le participant a bien été modifié.");
            return $this->redirectToRoute("admin_detailUser", [
                "participant"=>$participant->getId()
            ]);
        }

        return $this->render('@eniAdmin/Admin/update_user.html.twig', [
            "form"=>$form->createView()
        ]);
    }

    /**
     * @Route("/deleteUser/{participant}", name="deleteUser")
     * @param Participant $participant
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function deleteUserAction(Request $request, Participant $participant)
    {
        $deleteForm = $this->createFormBuilder()
            ->add('Confirmer', SubmitType::class);
        $form = $deleteForm->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->remove($participant);
            $em->flush();

            $this->addFlash("success", "Le participant a bien été supprimé.");
            return $this->redirectToRoute("admin_listUser", [
            ]);
        }
        return $this->render('@eniAdmin/Admin/delete_user.html.twig', [
            'form'=>$form->createView(),
            "participant"=>$participant
        ]);
    }

}
