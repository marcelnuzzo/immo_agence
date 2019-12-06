<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\Categorie;
//use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     * @Route("/bien", name="bien")
     *  
     */
    public function bien()
    {
        $repo = $this->getDoctrine()->getRepository(Bien::class);
        $biens = $repo->findAll();
        
        return $this->render('home/bien.html.twig', [
            'controller_name' => 'AdminController',
            'biens'=> $biens
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
