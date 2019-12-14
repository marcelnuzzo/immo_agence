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

    /**
    * @Route("/appart", name="appart")
    * @Route("/terrain", name="terrain")
    * @Route("/maison", name="maison")
    * @Route("/igloo", name="igloo")
    */
    public function catFilter (Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Categorie::class);
        
        $categories = $repo->findAll();

        $currentRoute = $request->attributes->get('_route');

        $libelle="";
        if($currentRoute == "appart")
            $libelle = 'appartement';
        else if($currentRoute == "terrain")
            $libelle = 'terrain';
        else if($currentRoute == "maison")
            $libelle = "maison";
        else if($currentRoute == "igloo")
            $libelle = "igloo";

        $categories = $this->getDoctrine()
                           ->getRepository(Categorie::class)
                           ->findByCatFilter($libelle);
        
        return $this->render('blog/catFilter.html.twig', [
            'controller_name' => 'BlogController',
            'categories'=> $categories,
            'libelle' => $libelle
        ]);
    }
    

    /**
     * @Route("/bienCat/{id}", name="bienCat" )
     */
    public function bienCat($id, Request $request, EntityManagerInterface $manager)
    {
        $repo = $this->getDoctrine()->getRepository(Categorie::class);
        $repo1 = $this->getDoctrine()->getRepository(Categorie::class);
        $categorie = $repo->find($id);
        $categories = $repo1->findAll();
        $repo2 = $this->getDoctrine()->getRepository(Bien::class);
        $bien = $repo2->findAll();

        return $this->render('blog/bienCat.html.twig', [
            'id' => $categorie->getId(),
            'categorie'=> $categorie,
            'categories'=> $categories,
            'bien'=> $bien
        ]);

    }

    /**
     * @Route("/appart2", name="appart2")
     * @Route("/terrain2", name="terrain2")
     * @Route("/maison2", name="maison2")
     * @Route("/igloo2", name="igloo2")
     */
    public function findAllBienInByCategory(Request $request)
    {

        $repo = $this->getDoctrine()->getRepository(Bien::class);
        $biens = $repo->findAll();

        $currentRoute = $request->attributes->get('_route');

        $libelle = '';
        if($currentRoute == "appart2")
            $libelle = 'appartement';
        else if($currentRoute == "terrain2")
            $libelle = 'terrain';
        else if($currentRoute == "maison2")
            $libelle = "maison";
        else if($currentRoute == "igloo2")
            $libelle = "igloo";
        
        $categories = $this->getDoctrine()
        ->getRepository(Categorie::class)
        ->findAllBienInByCategory($libelle);

        //dd($biens);
        //dd($categories);

        return $this->render('blog/categorieFiltre.html.twig', [
            'controller_name' => 'BlogController',
            'categories'=> $categories,
            'biens' => $biens
        ]);

    }

    /**
     * @Route("/location", name="location")
     * @Route("/vente", name="vente")
     */
    public function tipFilter (Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Bien::class);
        $biens = $repo->findAll();
        $repo1 = $this->getDoctrine()->getRepository(Tipe::class);
        $tipe = $repo1->findAll();

        $currentRoute = $request->attributes->get('_route');

        $libelle = '';
        if($currentRoute == "location")
            $libelle = 'location';
        else if($currentRoute == "vente")
            $libelle = 'ventes';

            $tipes = $this->getDoctrine()
            ->getRepository(Tipe::class)
            ->findByTypeLoc($libelle);

            return $this->render('blog/vente.html.twig', [
                'controller_name' => 'BlogController',
                'tipes'=> $tipes,
                'biens' => $biens,
                'tipe' => $tipe
            ]);
    }
}
