<?php

namespace eni\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="homeAdmin")
     */
    public function indexAction()
    {
        return $this->render('@eniAdmin/Default/index.html.twig');
    }
}
