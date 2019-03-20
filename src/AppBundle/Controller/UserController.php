<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends Controller
{
    /**
     * @Route("/detail")
     */
    public function detailAction()
    {
        return $this->render('AppBundle:User:detail.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/update")
     */
    public function updateAction()
    {
        return $this->render('AppBundle:User:update.html.twig', array(
            // ...
        ));
    }

}
