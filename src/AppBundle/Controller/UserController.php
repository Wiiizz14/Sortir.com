<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Participant;
use AppBundle\Form\ParticipantType;
use Intervention\Image\ImageManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController extends Controller
{
    /**
     * @Route("/detailUser", name="detail")
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function detailUserAction(UserInterface $user)
    {
        return $this->render('User/detail_user.html.twig', [
            "participant"=>$user
        ]);
    }

    /**
     * @Route("/updateUser", name="updateUser")
     * @param Request $request
     * @param UserInterface $user
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function updateUserAction(Request $request, UserInterface $user)
    {
        $passwordEncoder = $this->get('security.password_encoder');
        $saveUrlPhoto = $user->getUrlPhoto();
        $form = $this->createForm(ParticipantType::class, $user);
        $form->remove('isAdministrateur');

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $user->setIsActif(true);
            $user->getIsAdministrateur() ?
                $user->setRoles(['ROLE_ADMIN']) : $user->setRoles(['ROLE_USER']);

            $toSavePassword = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($toSavePassword);

            if ($user->getUrlPhoto() != null)
            {
                $filename = "photo-".$user->getUsername()."-".$user->getId().".".$user->getUrlPhoto()->guessExtension();

                $image = $user->getUrlPhoto();

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

                $user->setUrlPhoto("/images/upload/".$filename);
            }
            else
            {
                $user->setUrlPhoto($saveUrlPhoto);
            }

            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "Le participant a bien été modifié.");
            return $this->redirectToRoute("updateUser", [
                "participant"=>$user->getId()
            ]);
        }

        return $this->render('User/update_user.html.twig', [
            "form"=>$form->createView()
        ]);
    }

}
