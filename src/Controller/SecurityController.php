<?php

namespace App\Controller;

use App\Entity\Bien;
use App\Entity\User;
use App\Form\FormUserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/security", name="security")
     */
    public function index()
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    /**
    * @Route("security/formUser", name="security_formUser")
    * 
    */
    public function formUser(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $user = new User();
        
        $form = $this->createFormBuilder($user)
                     ->add('username')
                     ->add('email')
                     ->add('password', PasswordType::class)
                     ->add('confirm_password', PasswordType::class)
                     ->getForm();
                     
           
                $form->handleRequest($request);
               
                if($form->isSubmitted() && $form->isValid()) {
                    $hash = $encoder->encodePassword($user, $user->getPassword());

                    $user->setPassword($hash);
                    
                    $manager->persist($user);
                    $manager->flush();
                    //$this->addFlash('success', 'Votre compte à bien été enregistré.');
                        return $this->redirectToRoute('security_login');
                }

        return $this->render('security/formUser.html.twig', [
            'controller_name' => 'SecurityController',
            'formUser' => $form->createView()
        ]);
    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login() {
        $repo = $this->getDoctrine()->getRepository(Bien::class);
        $articles = $repo->findAll();

        return $this->render('security/login.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout() {}
}
