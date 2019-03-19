<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Participants;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ConnectionController
 * @Route("/connect", name="connect_")
 * @package AppBundle\Controller
 */
class ConnectionController extends Controller
{

     /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function connectAction(AuthenticationUtils $authenticationUtils){

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $formBuilder = $this->createFormBuilder(Participants::class);
        $formBuilder
            ->add("username", TextType::class, [
                "label"=>"pseudo"
            ])
            ->add("password", PasswordType::class)
            ->add("valider", SubmitType::class);

        $form = $formBuilder->getform();

        return $this->render("login.html.twig", [
            "formLogin"=>$form->createView(),
            "error"=>$error,
            "lastUsername"=>$lastUsername
        ]);
    }

    /**
     * @Route("logout", name="logout")
     */
    public function logoutAction(){
        return $this->render("login.html.twig");
    }
}
