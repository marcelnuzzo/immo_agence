<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\Tipe;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="index_admin")
     */
    public function index()
    {
        return $this->render('admin/index_admin.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/index_categorie", name="index_categorie")
     *  @Route("/admin/{id}/editCat", name="admin_editCat")
     */
    public function categorie()
    {
        $repo = $this->getDoctrine()->getRepository(Categorie::class);
        
        $categories = $repo->findAll();
       
        return $this->render('admin/index_categorie.html.twig', [
            'controller_name' => 'AdminController',
            'categories'=> $categories
        ]);
    }

    /**
     * @Route("/index_tipe", name="index_tipe")
     *  @Route("/admin/{id}/editTip", name="admin_editTip")
     */
    public function tipe()
    {
        $repo = $this->getDoctrine()->getRepository(Tipe::class);
        $tipes = $repo->findAll();
        
        return $this->render('admin/index_tipe.html.twig', [
            'controller_name' => 'AdminController',
            'tipes'=> $tipes
        ]);
    }

    /**
     * @Route("/index_bien", name="index_bien")
     */
    public function bien()
    {
        $repo = $this->getDoctrine()->getRepository(Bien::class);
        $biens = $repo->findAll();
        
        return $this->render('admin/index_bien.html.twig', [
            'controller_name' => 'AdminController',
            'biens'=> $biens
        ]);
    }

    /**
    * @Route("/admin/newCat", name="admin_createCat")
    * 
    */
    public function formulaireCat(Request $request, ObjectManager $manager, Categorie $categorie = null)
    {
        if(!$categorie) {
            $categorie = new Categorie();
        }
    
        $form = $this->createFormBuilder($categorie)
                     ->add('libelle')
                     ->getForm();

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()) {
                    if(!$categorie)
                        $editMode = 0;
                    else
                        $editMode = 1;
                    $manager->persist($categorie);
                    $manager->flush();
            
                        return $this->redirectToRoute('index_categorie');
                }

                return $this->render('admin/createCat.html.twig', [
                     'formCategorie' => $form->createView(),
                     'editMode' => $categorie->getId() !== null
                     ]);
    
    }

    /**
    * 
    * @Route("/admin/editionCat/{id}", name="admin_editCat")
    */
    public function formulaireCat2(Request $request, ObjectManager $manager, Categorie $categorie = null)
    {
        if(!$categorie) {
            $categorie = new Categorie();
        }
    
        $form = $this->createFormBuilder($categorie)
                     ->add('libelle')
                     ->getForm();

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()) {
                    if(!$categorie)
                        $editMode = 0;
                    else
                        $editMode = 1;
                    $manager->persist($categorie);
                    $manager->flush();
            
                        return $this->redirectToRoute('index_categorie',  ['id' => $categorie->getId()
                        ]);
                }

                return $this->render('admin/createCat.html.twig', [
                     'formCategorie' => $form->createView(),
                     'editMode' => $categorie->getId() !== null
                     ]);
    
    }

     /**
    * @Route("/admin/newTip", name="admin_createTip")
    * 
    */
    public function formulaireTip(Bien $bien = null, Request $request, ObjectManager $manager, Tipe $tipe = null)
    {
        if(!$tipe) {
            $tipe = new Tipe();
            $bien = new Bien();
        }
    
        $form = $this->createFormBuilder($tipe)
                     ->add('libelle')
                     ->getForm();

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()) {
                    if(!$tipe)
                        $editMode = 0;
                    else
                        $editMode = 1;
                    $manager->persist($tipe);
                    $manager->flush();
            
                        return $this->redirectToRoute('index_tipe');
                }

                return $this->render('admin/createTip.html.twig', [
                     'formTip' => $form->createView(),
                     'editMode' => $tipe->getId() !== null
                     ]);
    
    }

    /**
    * 
    * @Route("/admin/editionTip/{id}", name="admin_editTip")
    */
    public function formulaireTyp1(Bien $bien = null, Request $request, ObjectManager $manager, Tipe $tipe = null)
    {    
        $form = $this->createFormBuilder($tipe)
                     ->add('libelle')
                     ->getForm();

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()) {
                    if(!$tipe)
                        $editMode = 0;
                    else
                        $editMode = 1;
                    $manager->persist($tipe);
                    $manager->flush();
            
                        return $this->redirectToRoute('index_type',  ['id' => $tipe->getId()
                        ]);
                }

                return $this->render('admin/createTip.html.twig', [
                     'formTipe' => $form->createView(),
                     'editMode' => $tipe->getId() !== null
                     ]);
    
    }

    /**
     * @Route("/admin/newBie", name="admin_createBie")
     * 
     */
    public function formBie(Bien $bien = null, Categorie $categorie = null, Request $request, ObjectManager $manager)
    {
        if(!$bien) {
            $bien = new Bien();
            $categorie = new Categorie();
        }
       
        $form = $this->createFormBuilder($bien)
                     ->add('description')
                     ->add('surface')
                     ->add('etage')
                     ->add('chambre')
                     ->add('image')
                     ->add('statut')
                     ->add('type', EntityType::class, [
                        'class' => Type::class,
                        "choice_label" => 'libelle'
                    ])
                     ->add('categorie', EntityType::class, [
                        'class' => Categorie::class,
                        "choice_label" => 'libelle'
                    ])
                     ->getForm();

        
            $form->handleRequest($request);
       
        
        if($form->isSubmitted() && $form->isValid()) {
            if(!$bien->getId()) {
                $bien->setCreatedAt(new \DateTime());
                $editMode = 0;
            }
            else {
                $editMode = 1;
            }
            $manager->persist($bien);
           
            $manager->flush();
            $this->addFlash('success', 'Bien créé');

            return $this->redirectToRoute('index_bien');
        }

    return $this->render("admin/createBie.html.twig", [
            'formBien' => $form->createView(),
            'editMode' => $bien->getId() !== null
        ]);
    }

     /**
     * 
     * @Route("/admin/editionBie/{id}", name="admin_editBie")
     */
    public function formBie1 (Bien $bien = null, Categorie $categorie = null, Request $request, ObjectManager $manager)
    {
       
        $form = $this->createFormBuilder($bien)
                     ->add('description')
                     ->add('surface')
                     ->add('etage')
                     ->add('chambre')
                     ->add('image')
                     ->add('statut')
                     ->add('type', EntityType::class, [
                     'class' => Type::class,
                     "choice_label" => 'libelle'
                     ])
                     ->add('categorie', EntityType::class, [
                     'class' => Categorie::class,
                     "choice_label" => 'libelle'
                     ])
                     ->getForm();

        
            $form->handleRequest($request);
       
        
        if($form->isSubmitted() && $form->isValid()) {
            if(!$bien->getId()) {
                $bien->setCreatedAt(new \DateTime());
                $editMode = 0;
            }
            else {
                $editMode = 1;
            }
            $manager->persist($bien);
           
            $manager->flush();

            return $this->redirectToRoute('index_bien', ['id' => $bien->getId()
            ]);
        }

    return $this->render("admin/createBie.html.twig", [
            'formBien' => $form->createView(),
            'editMode' => $bien->getId() !== null
        ]);
    }

    /**
     * @Route("/admin/index_bien/{id}/deleteBie", name="admin_deleteBie")
     */
    public function deleteBie($id, ObjectManager $Manager, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Bien::class);
        $bien = $repo->find($id);

        $Manager->remove($bien);
        $Manager->flush();
        
        return $this->redirectToRoute('index_bien');
       
    }

    /**
     * @Route("/admin/index_categorie/{id}/deleteCat", name="admin_deleteCat")
     */
    public function deleteCat($id, ObjectManager $Manager, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Categorie::class);
        $categorie = $repo->find($id);
       
        $Manager->remove($categorie);
        $Manager->flush();
        
        return $this->redirectToRoute('index_categorie');
       
    }

    /**
     * @Route("/admin/index_tipe/{id}/deleteTip", name="admin_deleteTip")
     */
    public function deleteTip($id, ObjectManager $Manager, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Tipe::class);
        $tipe = $repo->find($id);
        $Manager->remove($tipe);
        $Manager->flush();
        
        return $this->redirectToRoute('index_tipe');
       
    }

}
