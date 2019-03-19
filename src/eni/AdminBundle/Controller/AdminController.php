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
        return $this->render('@eniAdmin/Admin/create_user.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/listUser")
     */
    public function listUserAction()
    {
        return $this->render('@eniAdmin/Admin/list_user.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/detailUser")
     */
    public function detailUserAction()
    {
        return $this->render('@eniAdmin/Admin/detail_user.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/updateUser")
     */
    public function updateUserAction()
    {
        return $this->render('@eniAdmin/Admin/update_user.html.twig', array(
            // ...
        ));
    }

    /**
     * @Route("/deleteUser")
     */
    public function deleteUserAction()
    {
        return $this->render('@eniAdmin/Admin/delete_user.html.twig', array(
            // ...
        ));
    }

}
