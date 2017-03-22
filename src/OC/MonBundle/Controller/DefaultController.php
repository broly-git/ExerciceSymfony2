<?php

namespace OC\MonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use OC\MonBundle\Entity\Apprenant;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MonBundle:Default:index.html.twig', array('name' => $name));
    }

    public function addAction(Request $request)
    {

    	$apprenant = new Apprenant();

	    // J'ai raccourci cette partie, car c'est plus rapide à écrire !
	    $form = $this->get('form.factory')->createBuilder('form', $apprenant)
	      ->add('nom',          'text')
	      ->add('prenom',       'text')
	      ->add('naissance',    'date')
	      ->add('mail',    	 	'text')
	      ->add('telephone', 	'text')
	      ->add('groupe', 'entity', array(
				'class'    => 'MonBundle:Groupe',
				'property' => 'designation',
				'multiple' => false))
	      ->add('enregistrer',  'submit')
	      ->add('quitter',      'reset')
	      ->getForm();

	    $form->handleRequest($request);

	    if ($form->isValid()) {
	      $em = $this->getDoctrine()->getManager();
	      $em->persist($apprenant);
	      $em->flush();

	      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien enregistrée.');
	      // On redirige vers la page de visualisation de l'annonce nouvellement créée
	      
	      return $this->redirect($this->generateUrl('mon_lister', array('id' => $apprenant->getId())));
	    	}

	      return $this->render('MonBundle:Default:add.html.twig', array(
      			'form' => $form->createView(),
    		)); 
    }


     public function editAction($id, Request $request)
    {

    	$apprenant = new Apprenant();
    	$em = $this->getDoctrine()->getManager();
    	$apprenant = $em->getRepository('MonBundle:Apprenant')->find($id);

	    // J'ai raccourci cette partie, car c'est plus rapide à écrire !
	    $form = $this->get('form.factory')->createBuilder('form', $apprenant)
	      ->add('nom',          'text')
	      ->add('prenom',       'text')
	      ->add('naissance',    'date')
	      ->add('mail',    	 	'text')
	      ->add('telephone', 	'text')
	      ->add('groupe', 'entity', array(
				'class'    => 'MonBundle:Groupe',
				'property' => 'designation',
				'multiple' => false))
	      ->add('enregistrer',  'submit')
	      ->add('quitter',      'reset')
	      ->getForm();  

	    if ($form->handleRequest($request)->isValid()) {
	      $em->flush();
	      $request->getSession()->getFlashBag()->add('notice', 'Annonce bien modifier.');
	      // On redirige vers la page de visualisation de l'annonce nouvellement créée
	      
	      return $this->redirect($this->generateUrl('mon_lister', array('id' => $apprenant->getId())));
	    	}

	      return $this->render('MonBundle:Default:edit.html.twig', array(
      			'apprenant' => $apprenant,
      			'form' => $form->createView()
    		)); 
    }


    public function listAction()
    {
    	
    	$apprenant = new Apprenant();
    	$em = $this->getDoctrine()->getManager();

	    $apprenant = $em->getRepository('MonBundle:Apprenant')->findAll();

	    return $this->render('MonBundle:Default:list.html.twig', array('apprenant' => $apprenant));

    }


  	public function deleteAction($id, Request $request)
  	{
    	$em = $this->getDoctrine()->getManager();
    	$apprenant = $em->getRepository('MonBundle:Apprenant')->find($id);

	    $form = $this->createFormBuilder()->getForm();

	    if ($form->handleRequest($request)->isValid()) {
	      $em->remove($apprenant);
	      $em->flush();

	      $request->getSession()->getFlashBag()->add('info', "L'annonce a bien été supprimée.");

	      return $this->redirect($this->generateUrl('mon_lister', array('id' => $apprenant->getId())));
	    }

	    return $this->render('MonBundle:Default:delete.html.twig', array(
	      'apprenant' => $apprenant,
	      'form'   => $form->createView()
	    ));
  	}
}
