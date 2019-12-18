<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\Tipe;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_index")
     */
    public function index()
    {
        return $this->render('admin/admin_index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin_categorie", name="admin_categorie")
     */
    public function categorie()
    {
        $repo = $this->getDoctrine()->getRepository(Categorie::class);
        
        $categories = $repo->findAll();
       
        return $this->render('admin/admin_categorie.html.twig', [
            'controller_name' => 'AdminController',
            'categories'=> $categories
        ]);
    }

    /**
     * @Route("/admin_tipe", name="admin_tipe")
     */
    public function tipe()
    {
        $repo = $this->getDoctrine()->getRepository(Tipe::class);
        $tipes = $repo->findAll();
        
        return $this->render('admin/admin_tipe.html.twig', [
            'controller_name' => 'AdminController',
            'tipes'=> $tipes
        ]);
    }

    /**
     * @Route("/admin_bien", name="admin_bien")
     */
    public function bien()
    {
        $repo = $this->getDoctrine()->getRepository(Bien::class);
        $biens = $repo->findAll();
        
        return $this->render('admin/admin_bien.html.twig', [
            'controller_name' => 'AdminController',
            'biens'=> $biens
        ]);
    }

    /**
    * @Route("/admin/newCat", name="admin_createCat")
    */
    public function formulaireCat(Request $request, EntityManagerInterface $manager, Categorie $categorie = null)
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
            
                        return $this->redirectToRoute('admin_categorie');
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
    public function formulaireCat2(Request $request, EntityManagerInterface $manager, Categorie $categorie = null)
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
            
                        return $this->redirectToRoute('admin_categorie',  ['id' => $categorie->getId()
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
    public function formulaireTip(Bien $bien = null, Request $request, EntityManagerInterface $manager, Tipe $tipe = null)
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
            
                        return $this->redirectToRoute('admin_tipe');
                }

                return $this->render('admin/createTip.html.twig', [
                     'formTipe' => $form->createView(),
                     'editMode' => $tipe->getId() !== null
                     ]);
    
    }

    /**
    * 
    * @Route("/admin/editionTip/{id}", name="admin_editTip")
    */
    public function formulaireTyp1(Bien $bien = null, Request $request, EntityManagerInterface $manager, Tipe $tipe = null)
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
            
                        return $this->redirectToRoute('admin_type',  ['id' => $tipe->getId()
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
    public function formBie( \Swift_Mailer $mailer, Bien $bien = null, Categorie $categorie = null, Request $request, EntityManagerInterface $manager)
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
                     ->add('tipe', EntityType::class, [
                        'class' => Tipe::class,
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
            //$manager->persist($bien);
            
            //$manager->flush();
            
            $toto="Description : ".$bien->getDescription().'</br>'."Surface : ".$bien->getSurface().'</br>'."Statut : ".$bien->getStatut().'</br>'."Image : ".$bien->getImage().'</br>'."Date : ".$bien->getCreatedAt()->format('Y-m-d H:i:s').'</br>'."Type : ".$bien->getTipe()->getLibelle().'</br>'."Catégorie : ".$bien->getCategorie()->getLibelle();

            $this->addFlash('success', 'Bien créé');
            
            $message = (new \Swift_Message('Hello Email'))
                                ->setFrom('nuzzomarcel358@gmail.com')
                                ->setTo('nuzzo.marcel@aliceadsl.fr')
                                ->setBody($toto,
                                      'text/html'
                                    )
                                ;
                                $mailer->send($message);

            return $this->redirectToRoute('admin_bien');
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
    public function formBie1 (Bien $bien = null, Categorie $categorie = null, Request $request, EntityManagerInterface $manager)
    {
       
        $form = $this->createFormBuilder($bien)
                     ->add('description')
                     ->add('surface')
                     ->add('etage')
                     ->add('chambre')
                     ->add('image')
                     ->add('statut')
                     ->add('tipe', EntityType::class, [
                     'class' => Tipe::class,
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

            return $this->redirectToRoute('admin_bien', ['id' => $bien->getId()
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
    public function deleteBie($id, EntityManagerInterface $Manager, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Bien::class);
        $bien = $repo->find($id);

        $Manager->remove($bien);
        $Manager->flush();
        
        return $this->redirectToRoute('admin_bien');
       
    }

    /**
     * @Route("/admin/index_categorie/{id}/deleteCat", name="admin_deleteCat")
     */
    public function deleteCat($id, EntityManagerInterface $Manager, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Categorie::class);
        $categorie = $repo->find($id);
       
        $Manager->remove($categorie);
        $Manager->flush();
        
        return $this->redirectToRoute('admin_categorie');
       
    }

    /**
     * @Route("/admin/index_tipe/{id}/deleteTip", name="admin_deleteTip")
     */
    public function deleteTip($id, EntityManagerInterface $Manager, Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(Tipe::class);
        $tipe = $repo->find($id);
        $Manager->remove($tipe);
        $Manager->flush();
        
        return $this->redirectToRoute('admin_tipe');
       
    }

}
