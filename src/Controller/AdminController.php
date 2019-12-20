<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\Tipe;
use App\Entity\Categorie;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
    * @Route("/admin/editionCat/{id}", name="admin_editCat")
    */
    public function formulaireCat(Request $request, EntityManagerInterface $manager, Categorie $categorie = null)
    {
        $currentRoute = $request->attributes->get('_route');
        $route = "admin/createCat";
        if($currentRoute == "admin/newCat")
            $route = "admin_createCat";
        else if($currentRoute == "admin/editionCat/{id}")
            $route = "admin_editCat";

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

                $html = ".html.twig";
                return $this->render($route.$html, [
                     'formCategorie' => $form->createView(),
                     'editMode' => $categorie->getId() !== null
                     ]);
    
    }

    /**
    * @Route("/admin/newTip", name="admin_createTip")
    * @Route("/admin/editionTip/{id}", name="admin_editTip")
    */
    public function formulaireTip(Bien $bien = null, Request $request, EntityManagerInterface $manager, Tipe $tipe = null)
    {
        $currentRoute = $request->attributes->get('_route');
        $route = "admin/createTip";
        if($currentRoute == "admin/newTip")
            $route = "admin_createTip";
        else if($currentRoute == "admin/editionTip/{id}")
            $route = "admin_editTip";

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

                $html = ".html.twig";
                return $this->render($route.$html, [
                     'formTipe' => $form->createView(),
                     'editMode' => $tipe->getId() !== null
                     ]);
    
    }

    /**
     * @Route("/admin/newBie", name="admin_createBie")
     * @Route("/admin/editionBie/{id}", name="admin_editBie")
     */
    public function formBie( \Swift_Mailer $mailer, Bien $bien = null, Categorie $categorie = null, Request $request, EntityManagerInterface $manager)
    {
        $currentRoute = $request->attributes->get('_route');
        $route = "admin/createBie";
        if($currentRoute == "admin/newBie")
            $route = "admin_createBie";
        else if($currentRoute == "admin/editionBie/{id}")
            $route = "admin_editBie";

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
            $manager->persist($bien);         
            $manager->flush();
            $this->addFlash('success', 'Bien créé');
            
            $body="Description : ".$bien->getDescription().'</br>'."Surface : ".$bien->getSurface().'</br>'."Etage : ".$bien->getEtage().'</br>'."Chambre : ".$bien->getChambre().'</br>'."Statut : ".$bien->getStatut().'</br>'."Image : ".$bien->getImage().'</br>'."Date : ".$bien->getCreatedAt()->format('Y-m-d H:i:s').'</br>'."Type : ".$bien->getTipe()->getLibelle().'</br>'."Catégorie : ".$bien->getCategorie()->getLibelle();

            $message = (new \Swift_Message('Hello Email'))
                        ->setFrom('nuzzomarcel358@gmail.com')
                        ->setTo('nuzzo.marcel@aliceadsl.fr')
                        ->setBody($body,
                                'text/html'
                            );
            $mailer->send($message);

            return $this->redirectToRoute('admin_bien');
        }

        $html = ".html.twig";
        return $this->render($route.$html, [
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
