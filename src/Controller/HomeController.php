<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
    * @route("/apropos", name="apropos")
    */
    public function apropos()
    {

        return $this->render('home/apropos.html.twig', [
            'controller_name' => 'HomeController'
        ]);
    }

    /**
    *  @Route("/contact", name="contact")
    */
    public function contact()
    {

        return $this->render('home/contact.html.twig', [
            'controller_name' => 'HomeController'
        ]);
    }

    /**
     *  @Route("/admin/admin", name="admin")
    */
    public function admin()
    {
        return $this->render('home/admin.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
