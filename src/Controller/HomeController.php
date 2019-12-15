<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     * @Route("/", name="home")
     */
    public function index(PaginatorInterface $paginator,Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Bien::class);
        $biens = $paginator->paginate(
            $repo->findAll(),
            $request->query->getInt('page', 1), /*page number*/
             10 /*limit per page*/
        );
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'biens' => $biens
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
    public function bien(PaginatorInterface $paginator,Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Bien::class);
        $repo1 = $this->getDoctrine()->getRepository(Bien::class);
        $biens = $paginator->paginate(
            $repo->findAll(),
            $request->query->getInt('page', 1), /*page number*/
             10 /*limit per page*/
        );
        $bien = $repo1->findAll();
        return $this->render('home/bien.html.twig', [
            'controller_name' => 'AdminController',
            'biens'=> $biens,
            'bien' => $bien
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

    /**
     * @Route("/home1/{id}", name="home_show")
     */
    public function show($id, Request $request, EntityManagerInterface $manager)
    {
        $repo = $this->getDoctrine()->getRepository(Bien::class);
        $repo1 = $this->getDoctrine()->getRepository(Bien::class);
        $bien = $repo->find($id);
        $biens = $repo1->findAll();
               
        return $this->render('home/show.html.twig', [
            'id' => $bien->getId(),
            'bien'=> $bien,
            'biens'=> $biens
        ]);
    }
}
