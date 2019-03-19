<?php

namespace eni\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AdminController extends Controller
{
    /**
     * @Route("/createUser")
     */
    public function createUserAction()
    {
        return $this->render('eniAdminBundle:Admin:create_user.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/listUser")
     */
    public function listUserAction()
    {
        return $this->render('eniAdminBundle:Admin:list_user.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/detailUser")
     */
    public function detailUserAction()
    {
        return $this->render('eniAdminBundle:Admin:detail_user.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/updateUser")
     */
    public function updateUserAction()
    {
        return $this->render('eniAdminBundle:Admin:update_user.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/deleteUser")
     */
    public function deleteUserAction()
    {
        return $this->render('eniAdminBundle:Admin:delete_user.html.twig', array(
            // ...
        ));
    }

}
