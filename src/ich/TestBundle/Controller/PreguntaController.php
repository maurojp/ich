<?php

namespace ich\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormError;
use ich\TestBundle\Entity\Pregunta;
use ich\TestBundle\Entity\Pregunta_OpcionRespuesta;
use ich\TestBundle\Form\PreguntaType;

class PreguntaController extends Controller
{
	public function indexAction(Request $request) {
		// Consulta DQL
		$co = $this->getDoctrine ()->getManager ();
		$dql = "SELECT p FROM ichTestBundle:Pregunta p ORDER BY p.id DESC";
		$preguntas = $co->createQuery ( $dql );
		
		// Paginacion
		$paginator = $this->get ( 'knp_paginator' );
		
		$pagination = $paginator->paginate ( $preguntas, $request->query->getInt ( 'page', 1 ), 6 );
		$deleteFormAjax = $this->createCustomForm ( ':PREGUNTA_ID', 'DELETE', 'ich_pregunta_delete' );
		
		return $this->render ( 'ichTestBundle:Pregunta:index.html.twig', array (
				'pagination' => $pagination,
				'delete_form_ajax' => $deleteFormAjax->createView () 
		) );
	}
	
	
	public function addAction() {
		$pregunta = new Pregunta ();
		
		$form = $this->createCreateForm ( $pregunta );
		
		return $this->render ( 'ichTestBundle:Pregunta:add.html.twig', array (
				'valid' => json_encode ( true ),
				'ponderaciones' => 0,
				'id' => $pregunta->getId (),
				'form' => $form->createView () 
		) );
	}
	
	
	private function createCreateForm(Pregunta $entity) {
		$form = $this->createForm ( new PreguntaType (), $entity, array (
				'action' => $this->generateUrl ( 'ich_pregunta_create' ),
				'method' => 'POST' 
		) );
		return $form;
	}
	
	
	public function cargarOpcionesAction(Request $request) {
		$grupoOpciones_id = $request->request->get ( 'grupoOpciones_id' );
		
		$em = $this->getDoctrine ()->getManager ();
		
		$grupoOpciones = $em->getRepository ( 'ichTestBundle:GrupoOpciones' )->find ( $grupoOpciones_id );
		
		$arrayCollection = array ();
		
		if (null != $grupoOpciones) {
			$opcionesRespuesta = $em->getRepository ( 'ichTestBundle:OpcionRespuesta' )->findByGrupoOpciones ( $grupoOpciones );
			
			foreach ( $opcionesRespuesta as $item ) {
				$arrayCollection [] = array (
						'ordenEvaluacion' => $item->getOrdenEvaluacion (),
						'id' => $item->getId (),
						'descripcion' => $item->getDescripcion (),
						'ponderacion' => 0 
				);
			}
		}
		
		return new JsonResponse ( $arrayCollection );
	}
	
	
	public function cargarCompetenciaEditAction(Request $request) {
		$factor_id = $request->request->get ( 'factor_id' );
		
		$em = $this->getDoctrine ()->getManager ();
		
		$factor = $em->getRepository ( 'ichTestBundle:Factor' )->find ( $factor_id );
		
		if (null != $factor)
			return new JsonResponse ( $factor->getCompetencia ()->getId () );
		return new JsonResponse ( 0 );
	}
	
	
	public function cargarOpcionesEditAction(Request $request) {
		$pregunta_id = $request->request->get ( 'pregunta_id' );
		
		$em = $this->getDoctrine ()->getManager ();
		
		$pregunta = $em->getRepository ( 'ichTestBundle:Pregunta' )->find ( $pregunta_id );
		
		$query = $em->createQuery ( 'SELECT op.id, op.descripcion, op.ordenEvaluacion, preOp.ponderacion
    			FROM ichTestBundle:Pregunta_OpcionRespuesta preOp JOIN ichTestBundle:OpcionRespuesta op
    			WHERE preOp.pregunta = :p and preOp.opcionRespuesta = op' )->setParameter ( 'p', $pregunta );
		
		$opcionesRespuesta = $query->getResult ();
		
		return new JsonResponse ( $opcionesRespuesta );
	}
	public function createAction(Request $request) {
		if ($request->isXMLHttpRequest ()) {
			
			$competencia_id = $request->request->get ( 'competencia_id' );
			
			$em = $this->getDoctrine ()->getManager ();
			
			$competencia = $em->getRepository ( 'ichTestBundle:Competencia' )->findById ( $competencia_id );
			
			$factores = $em->getRepository ( 'ichTestBundle:Factor' )->findByCompetencia ( $competencia );
			
			$arrayCollection = array ();
			
			foreach ( $factores as $item ) {
				$arrayCollection [] = array (
						'id' => $item->getId (),
						'nombre' => $item->getNombre () 
				);
			}
			return new JsonResponse ( $arrayCollection );
		}
		
		$pregunta = new Pregunta ();
		$form = $this->createCreateForm ( $pregunta );
		$form->handleRequest ( $request );
		
		if ($form->isValid ()) {
			$co = $this->getDoctrine ()->getManager ();
			$co->persist ( $pregunta );
			$co->flush ();
			
			$this->addFlash ( 'mensaje', 'La pregunta ha sido creada.' );
			
			return $this->redirectToRoute ( 'ich_pregunta_index' );
		}
		
		$arrayCollection = array ();
		
		foreach ( $pregunta->getOpcionesRespuesta () as $item ) {
			$arrayCollection [] = array (
					$item->getPonderacion () 
			);
		}
		
		return $this->render ( 'ichTestBundle:Pregunta:add.html.twig', array (
				'valid' => json_encode ( false ),
				'ponderaciones' => json_encode ( $arrayCollection ),
				'form' => $form->createView () 
		) );
	}
	
	private function createCustomForm($id, $method, $route) {
		return $this->createFormBuilder ()->setAction ( $this->generateUrl ( $route, array (
				'id' => $id 
		) ) )->setMethod ( $method )->getForm ();
	}
	
	public function editAction($id) {
		$co = $this->getDoctrine ()->getManager ();
		
		$pregunta = $co->getRepository ( 'ichTestBundle:Pregunta' )->find ( $id );
		
		if (! $pregunta) {
			throw $this->createNotFoundException ( 'Pregunta no encontrada' );
		}
		
		$form = $this->createEditForm ( $pregunta );
		
		return $this->render ( 'ichTestBundle:Pregunta:edit.html.twig', array (
				'valid' => json_encode ( true ),
				'ponderaciones' => 0,
				'pregunta' => $pregunta,
				'form' => $form->createView () 
		) );
	}
	
	private function createEditForm(Pregunta $entity) {
		$form = $this->createForm ( new PreguntaType (), $entity, array (
				'action' => $this->generateUrl ( 'ich_pregunta_update', array (
						'id' => $entity->getID () 
				) ),
				'method' => 'PUT' 
		) );
		
		return $form;
	}
	
	public function updateAction($id, Request $request) {
		$co = $this->getDoctrine ()->getManager ();
		
		$pregunta = $co->getRepository ( 'ichTestBundle:Pregunta' )->find ( $id );
		
		if (! $pregunta) {
			throw $this->createNotFoundException ( 'La pregunta no existe' );
		}
		
		$form = $this->createEditForm ( $pregunta );
		
		$form->handleRequest ( $request );
		
		if ($form->isValid ()) {
			$co->persist ( $pregunta );
			
			$co->flush ();
			
			$this->addFlash ( 'mensaje', 'Pregunta modificada con éxito.' );
			return $this->redirectToRoute ( 'ich_pregunta_index', array (
					'id' => $pregunta->getId () 
			) );
		}
		
		$arrayCollection = array ();
		
		foreach ( $pregunta->getOpcionesRespuesta () as $item ) {
			$arrayCollection [] = array (
					$item->getPonderacion () 
			);
		}
		
		return $this->render ( 'ichTestBundle:Pregunta:edit.html.twig', array (
				'valid' => json_encode ( false ),
				'ponderaciones' => json_encode ( $arrayCollection ),
				'pregunta' => $pregunta,
				'form' => $form->createView () 
		) );
	}
	
	
	public function deleteAction(Request $request, $id) {
		$em = $this->getDoctrine ()->getManager ();
		
		$pregunta = $em->getRepository ( 'ichTestBundle:Pregunta' )->find ( $id );
		
		if (! $pregunta) {
			throw $this->createNotFoundException ( 'Pregunta no encontrada.' );
		}
		
		$form = $this->createCustomForm ( $pregunta->getId (), 'DELETE', 'ich_pregunta_delete' );
		$form->handleRequest ( $request );
		
		if ($form->isSubmitted () && $form->isValid () && $request->isXMLHttpRequest ()) {
			$em->remove ( $pregunta );
			$em->flush ();
			return new Response ( json_encode ( array (
					'message' => 'La pregunta ha sido eliminada.' 
			) ), 200, array (
					'Content-Type' => 'application/json' 
			) );
		}
	}
}
