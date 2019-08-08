<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainWebsiteController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @Security("is_granted('IS_AUTHENTICATED_ANONYMOUSLY')")
     */
    public function index()
    {
        if (!$this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute("auth_dialog");
        } else {
            return $this->redirectToRoute("dashboard_main");
        }
    }
}


// TODO: If user has ROLE_BANNED, then display error instead of authenticatingUs