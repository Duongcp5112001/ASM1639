<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/homeadmin", name="index")
     */
    public function index(){
        return $this->render("main/index.html.twig");
    }

    /**
     * @Route("/homeuser", name="index1")
     */
    public function indexuser(){
        return $this->render("main/index1.html.twig");
    }
}
