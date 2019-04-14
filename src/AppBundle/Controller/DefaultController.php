<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity\utilisateur;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    { 
    return $this->render('home.html.twig');
    }

    /**
     * @Route("/connexion", name="connexion")
     */
    public function connexion (Request $request) {
        $repository = $this ->getDoctrine ()->getRepository('AppBundle:utilisateur');
        
        $utilisateur = new utilisateur();
        $form = $this->createFormBuilder($utilisateur)
        ->add('mail')
        ->add('mdp',PasswordType::class)
        ->add('save', SubmitType::class, array('label' => "Connexion"))
                ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $public = $repository ->findAll();    
            for($i=0;$i<count($public);$i++){
                 if($public[$i]->getMail()==$form->get('mail')->getData() and $public[$i]->getMdp()==$form->get('mdp')->getData()) 
                return $this->redirectToRoute('accueil',array('public' => $public));
            }
            return $this->render('connexion1.html.twig',array('form' =>$form->createView()) );
        }    
        return $this->render('connexion.html.twig',array('form' => $form->createView()));    
        
        }


    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription (Request $request) {
        $utilisateur = new utilisateur();

        $form = $this->createFormBuilder($utilisateur)
        ->add('civilite')
        ->add('nom')
        ->add('prenom')
        ->add('age')
        ->add('mail')
        ->add('mdp',PasswordType::class)
        ->add('telephone')
                ->add('save', SubmitType::class, array('label' => "S'inscrire"))
                ->getForm();    

        // tester si le formulaire est déjà rempli
          $form->handleRequest($request);
          if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($utilisateur); // prépare l'insertion dans la BD
            $em->flush(); // insère dans la BD  

            $form1 = $this->createFormBuilder($utilisateur)
            ->add('mail')
            ->add('mdp')
            ->add('save', SubmitType::class, array('label' => "Connexion"))
                ->getForm();
            return $this->redirectToRoute('connexion',array('form' => $form1->createView()));
        }
        return $this->render('inscription.html.twig',array('form' => $form->createView()));
    }


    /**
     * @Route("/accueil", name="accueil")
     */
    public function accueil (Request $request) {
         $repository = $this ->getDoctrine ()->getRepository('AppBundle:utilisateur');
         $public = $repository ->findAll();
        return $this->render('accuel.html.twig',array('public' => $public));

    }
}
