<?php

namespace ich\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EvaluacionController extends Controller {
	
	public function newStep1Action(Request $request) {
		$em = $this->getDoctrine ()->getManager ();
		
		if ($request->isXMLHttpRequest ()) {
			$query = $em->createQuery ( "SELECT c.apellido, c.nombre, c.nroCandidato FROM ichTestBundle:Candidato c" );
			$candidatos = $query->getResult ();
			
			return new JsonResponse ($candidatos);
		}
		
		$defaultData = array();
   
    $form = $this->createFormBuilder($defaultData)
        ->add('candidatos', CollectionType::class, array (
				'entry_type' => NumberType::class,
				'by_reference' => false,
				'allow_add' => true))
        ->add('send', SubmitType::class)
        ->setAction ( $this->generateUrl ( 'ich_evaluacion_newStep2') )
        ->setMethod ('POST' )
        ->getForm ();
       
		return $this->render ( 'ichTestBundle:Evaluacion:add1.html.twig', array ('form' => $form->createView ()));
	}
	
	
	public function newStep2Action(Request $request) {
		
		$defaultData = array();
		
		$form = $this->createFormBuilder($defaultData)
        ->add('candidatos', CollectionType::class, array (
				'entry_type' => NumberType::class,
				'by_reference' => false,
				'allow_add' => true))
        ->add('send', SubmitType::class)
        ->setAction ( $this->generateUrl ( 'ich_evaluacion_newStep2') )
        ->setMethod ('POST' )
        ->getForm ();
		$form->handleRequest ( $request );
		
		if ($form->isValid ()) {
			$data = $form->getData();
		}
		
		return $this->render ( 'ichTestBundle:Evaluacion:add1.html.twig', array ('form' => $form->createView ()));
	}
}
