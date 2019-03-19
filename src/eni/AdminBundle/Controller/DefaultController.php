<?php

namespace eni\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('eniAdminBundle:Default:index.html.twig');
    }
}
