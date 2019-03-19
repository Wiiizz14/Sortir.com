<?php

namespace eni\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('@eniAdmin/Default/index.html.twig');
    }
}
