<?php

namespace ReservationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ReservationBundle\Entity\Salle;
use ReservationBundle\Form\SalleType;
use ReservationBundle\Entity\Reservation;
use Symfony\Component\HttpFoundation\Request;


class DefaultController extends Controller
{
    /**
     * @Route("/Home", name="reservationbundle_index")
     * @Template()
     */
    public function indexAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	
      $repoReservations=$em->getRepository('ReservationBundle:Reservation');
    	
    	$resa = $repoReservations->findAll();
   
        return array('events'=> $resa);
       
    }
    
    /**
    *
    * @Route("/salles", name="reservationbundle_salles")
    * @Template()
    */
    
    public function sallesAction()
    {
    	$em = $this->getDoctrine()->getManager();
    	$repoSalles=$em->getRepository('ReservationBundle:Salle');
    	
    	$salles = $repoSalles->findAll();
    	
    	
    	return array(
    	'mes_salles'=> $salles );
    }
    
        /**
    *
    * @Route("/reservations", name="reservationbundle_reservation")
    * @Template()
    */
    
    public function reservationsAction()
    { 
    	$em = $this->getDoctrine()->getManager();
    	$repoResas=$em->getRepository('ReservationBundle:Reservation');
    	
    	$resas = $repoResas->findAll();
    	
    	
    	return array(
    	'mes_resas'=> $resas );
    	
    }
    
            /**
    *
    * @Route("/reserver", name="reservationbundle_reserver")
    * @Template()
    */
    
    public function reserverAction(Request $request)
    { 
    	$em = $this->getDoctrine()->getManager();
		$reserver = new reservation ();
		$formBuilder=$this->get('form.factory')->CreateBuilder('form',$reserver);
		$formBuilder
			->add('Nom','text')
			->add('Mail','text')
			->add('dateDebut','datetime', array('widget' => 'single_text'))
			->add('dateFin', 'datetime',  array('widget' => 'single_text'))
    		->add('save','submit', array('label'=>'Sauvegarder'));
    		//->add('saveAndAdd','submit',array('label'=>'Sauvegarder et suivant'));
    	$form=$formBuilder->getForm();
    	$form-> handleRequest($request);
    	if($form->isValid())
    		{
				$em=$this->getDoctrine()->getManager();
				$em->persist($reserver);
				$em->flush();
				
			//	if($form->get('saveAndAdd')->isClicked())
				
				return $this->redirect($this->generateUrl('reservationbundle_index'));
					
    		}
    	
    	return array(
    	'formulaire_reservation'=> $form->CreateView());
    	
    }
    /**
    *
    * @Route("/ajouterSalle", name="reservationbundle_ajouter")
    * @Template()
    */
    
    public function ajouterAction()
    {
    	$salle = new Salle();
    	$form=$this->createForm ( new SalleType(), $salle, array());
    	return array(
    	'formulaire_ajout'=> $form->CreateView()
    	);
    }
}
