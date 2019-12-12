<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\Tipe;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController'
        ]);
    }

    /**
     * @Route("/cat", name="cat")
     */
    public function cat()
    {
        $repo = $this->getDoctrine()->getRepository(Categorie::class);
        $categories = $repo->findAll();

        return $this->render('blog/cat.html.twig', [
            'controller_name' => 'BlogController',
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/tip", name="tip")
     */
    public function tip()
    {
        $repo = $this->getDoctrine()->getRepository(Tipe::class);
        $tipes = $repo->findAll();

        return $this->render('blog/tip.html.twig', [
            'controller_name' => 'BlogController',
            'tipes' => $tipes
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show($id, Request $request, EntityManagerInterface $manager)
    {
        $repo = $this->getDoctrine()->getRepository(Bien::class);
        
        $bien = $repo->find($id);
       
        $form = $this->createFormBuilder($bien)
                     ->add('description')
                     ->add('image')
                     ->add('surface')
                     ->getForm();

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()) {
                   
                    $manager->persist($bien);
                    $manager->flush();
            
                        return $this->redirectToRoute('blog_show',  ['id' => $bien->getId()
                        ]);
                }
               
        return $this->render('blog/show.html.twig', [
            'bien'=> $bien,
            'formCommentaire' => $form->createView()
        ]);
    }
}
