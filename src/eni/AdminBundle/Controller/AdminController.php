<?php

namespace eni\AdminBundle\Controller;

use AppBundle\Entity\Participant;
use AppBundle\Form\ParticipantType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller
{
    /**
     * @Route("/createUser", name="createUser")
     */
    public function createUserAction(Request $request)
    {
        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $participant->setIsActif(true);
            $participant->getIsAdministrateur() ?
                $participant->setRoles(['ADMIN_ROLE']) : $participant->setRoles(['PARTICIPANT_ROLE']);
            // TODO : générer le salt de manière plus complexe
            $participant->setSalt("poivre");
            $em->persist($participant);
            $em->flush();

            $this->addFlash("success", "Le participant a bien été enregistré");
            return $this->redirectToRoute("detailUser", [
                "participant"=>$participant
            ]);
        }
        return $this->render('@eniAdmin/Admin/create_user.html.twig', [
            'form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/listUser", name="listUser")
     */
    public function listUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repoListeParticipants = $em->getRepository(Participant::class);
        $listeParticipants = $repoListeParticipants->findAll();
        return $this->render('@eniAdmin/Admin/list_user.html.twig', [
            "listeParticipants"=>$listeParticipants
        ]);
    }

    /**
     * @Route("/detailUser/{id}", name="detailUser")
     */
    public function detailUserAction(Request $request, Participant $participant)
    {

        return $this->render('@eniAdmin/Admin/detail_user.html.twig', [
            "participant"=>$participant
        ]);
    }

    /**
     * @Route("/updateUser", name="updateUser")
     */
    public function updateUserAction()
    {
        return $this->render('@eniAdmin/Admin/update_user.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/deleteUser", name="deleteUser")
     */
    public function deleteUserAction()
    {
        return $this->render('@eniAdmin/Admin/delete_user.html.twig', array(
            // ...
        ));
    }

}
