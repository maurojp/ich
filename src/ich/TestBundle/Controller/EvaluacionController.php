<?php

namespace ich\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class EvaluacionController extends Controller {
	public function newStep1Action(Request $request) {
		$em = $this->getDoctrine ()->getManager ();
		
		if ($request->isXMLHttpRequest ()) {
			$query = $em->createQuery ( "SELECT c.apellido, c.nombre, c.nroCandidato FROM ichTestBundle:Candidato c" );
			$candidatos = $query->getResult ();
			
			return new JsonResponse ( $candidatos );
		}
		
		$defaultData = array ();
		
		$form = $this->createFormBuilder ( $defaultData )->add ( 'candidatos', CollectionType::class, array (
				'entry_type' => NumberType::class,
				'by_reference' => false,
				'allow_add' => true 
		) )->add ( 'send', SubmitType::class )->setAction ( $this->generateUrl ( 'ich_evaluacion_newStep2' ) )->setMethod ( 'POST' )->getForm ();
		
		return $this->render ( 'ichTestBundle:Evaluacion:add1.html.twig', array (
				'candidatosActivos' => false,
				'candidatos' => null,
				'form' => $form->createView () 
		) );
	}
	public function newStep2Action(Request $request) {
		if ($request->getMethod () == 'POST') {
			$defaultData = array ();
			
			$form = $this->createFormBuilder ( $defaultData )->add ( 'candidatos', CollectionType::class, array (
					'entry_type' => NumberType::class,
					'by_reference' => false,
					'allow_add' => true 
			) )->add ( 'send', SubmitType::class )->setAction ( $this->generateUrl ( 'ich_evaluacion_newStep2' ) )->setMethod ( 'POST' )->getForm ();
			$form->handleRequest ( $request );
			
			if ($form->isValid ()) {
				$data = $form->getData ();
				
				$co = $this->getDoctrine ()->getManager ();
				
				$query = $co->createQuery ( "SELECT c.nroCandidato
					FROM ichTestBundle:Candidato c JOIN ichTestBundle:Cuestionario cu
					where cu.candidato = c and cu.estado = 0
					" );
				
				$CandidatosSeleccionadosActivos = array ();
				// DEVUELVE ARRAY CON ARRAYS DE CANDIDATO
				$candidatosActivos = $query->getResult ();
				foreach ( $candidatosActivos as $candidato ) {
					$i = 0;
					
					for($i, $total = count ( $data ['candidatos'] ); $i < $total; $i ++) {
						if ($candidato ['nroCandidato'] == $data ['candidatos'] [$i])
							$CandidatosSeleccionadosActivos [] = $candidato ['nroCandidato'];
					}
				}
				
				if (count ( $CandidatosSeleccionadosActivos ) == 0) {
					/*
					 * $dql = "SELECT p FROM ichTestBundle:Puesto p WHERE p.id IN
					 * (SELECT IDENTITY(pc.puesto) FROM ichTestBundle:Puesto_Competencia pc WHERE
					 * pc.habilitada = true and IDENTITY(pc.competencia) NOT IN
					 * (SELECT c.id FROM ichTestBundle:Competencia c where c.auditoria is not NULL))";
					 */
					
					$dql = "SELECT p FROM ichTestBundle:Puesto p WHERE p.id IN 
					(SELECT IDENTITY(pc.puesto) FROM ichTestBundle:Puesto_Competencia pc WHERE IDENTITY(pc.competencia) NOT IN 
					(SELECT c.id FROM ichTestBundle:Competencia c where c.auditoria is not NULL))";
					$puestos = $co->createQuery ( $dql );
					
					// Paginacion
					$paginator = $this->get ( 'knp_paginator' );
					
					$pagination = $paginator->paginate ( $puestos, $request->query->getInt ( 'page', 1 ), 6 );
					
					$this->get ( 'session' )->set ( 'candidatos', $data );
					
					return $this->render ( 'ichTestBundle:Evaluacion:add2.html.twig', array (
							'pagination' => $pagination 
					) );
				} 

				else {
					$datosCandidatosActivos = array ();
					foreach ( $CandidatosSeleccionadosActivos as $nroCandidato ) {
						
						$candidato = $co->getRepository ( 'ichTestBundle:Candidato' )->find ( $nroCandidato );
						
						$datosCandidatosActivos [] = array (
								'apellido' => $candidato->getApellido (),
								'nombre' => $candidato->getNombre () 
						);
					}
					
					return $this->render ( 'ichTestBundle:Evaluacion:add1.html.twig', array (
							'candidatosActivos' => true,
							'candidatos' => $datosCandidatosActivos,
							'form' => $form->createView () 
					) );
				}
			}
			
			return $this->render ( 'ichTestBundle:Evaluacion:add1.html.twig', array (
					'candidatosActivos' => false,
					'candidatos' => null,
					'form' => $form->createView () 
			) );
		}
		
		$co = $this->getDoctrine ()->getManager ();
		
		/*
		 * $dql = "SELECT p FROM ichTestBundle:Puesto p WHERE p.id IN
		 * (SELECT IDENTITY(pc.puesto) FROM ichTestBundle:Puesto_Competencia pc WHERE
		 * pc.habilitada = true and IDENTITY(pc.competencia) NOT IN
		 * (SELECT c.id FROM ichTestBundle:Competencia c where c.auditoria is not NULL))";
		 */
		
		$dql = "SELECT p FROM ichTestBundle:Puesto p WHERE p.id IN 
					(SELECT IDENTITY(pc.puesto) FROM ichTestBundle:Puesto_Competencia pc WHERE IDENTITY(pc.competencia) NOT IN 
					(SELECT c.id FROM ichTestBundle:Competencia c where c.auditoria is not NULL))";
		$puestos = $co->createQuery ( $dql );
		
		// Paginacion
		$paginator = $this->get ( 'knp_paginator' );
		
		$pagination = $paginator->paginate ( $puestos, $request->query->getInt ( 'page', 1 ), 6 );
		
		return $this->render ( 'ichTestBundle:Evaluacion:add2.html.twig', array (
				'pagination' => $pagination 
		) );
	}
	public function newStep3Action(Request $request, $id) {
		
		/*
		 * $this->get('session')->remove('candidatos');
		 * $candidatos = $this->get('session')->get('candidatos');
		 */
		$em = $this->getDoctrine ()->getManager ();
		
		$puesto = $em->getRepository ( 'ichTestBundle:Puesto' )->find ( $id );
		
		if ($request->isXMLHttpRequest ()) {
			
			$query = $em->createQuery ( 'SELECT c.nombre, c.codigo, c.descripcion, pc.ponderacion
    			FROM ichTestBundle:Puesto_Competencia pc JOIN ichTestBundle:Competencia c
    			WHERE pc.puesto = :p and pc.competencia = c and c.auditoria is NULL' )->setParameter ( 'p', $puesto );
			
			$competencias = $query->getResult ();
			return new JsonResponse ( $competencias );
		}
		
		/*
		 * $query = $em->createQuery ( 'SELECT c3.id
		 * FROM ichTestBundle:Competencia c3 JOIN ichTestBundle:Factor f
		 * WHERE c3 = f.competencia and f.auditoria is NULL
		 * GROUP BY c3.id
		 * HAVING count(f.id) >= 2');
		 */
		
		/* throw $this->createNotFoundException (strval(count($competencias[0]))); */
		$query = $em->createQuery ( "SELECT distinct c.id, c.nombre
    			FROM ichTestBundle:Puesto_Competencia pc JOIN ichTestBundle:Competencia c
    			WHERE pc.puesto = :p and pc.competencia = c and c.auditoria is NULL and c.id NOT IN
				(SELECT distinct c2.id
    			FROM ichTestBundle:Puesto_Competencia pc2 JOIN ichTestBundle:Competencia c2
    			WHERE pc2.puesto = :p and pc2.competencia = c2 and c2.auditoria is NULL and c2.id in (
				SELECT distinct c3.id
				FROM ichTestBundle:Competencia c3 JOIN ichTestBundle:Factor f
				WHERE c3 = f.competencia and f.id IN (				
				SELECT distinct f2.id 
				FROM ichTestBundle:Factor f2 JOIN ichTestBundle:Pregunta pr 
				WHERE pr.factor = f2 and f2.auditoria is NULL and pr.auditoria is NULL
				GROUP BY f2.id
				HAVING count(distinct pr.id) >= 2)))" )->setParameter ( 'p', $puesto );
		
		$competencias = $query->getResult ();
		
		if (count ( $competencias ) > 0) {
			return $this->render ( 'ichTestBundle:Evaluacion:add3.html.twig', array (
					'competencias' => $query->getResult (),
					'error' => true,
					'puesto' => $puesto 
			) );
		}
		
		return $this->render ( 'ichTestBundle:Evaluacion:add3.html.twig', array (
				'competencias' => $query->getResult (),
				'error' => false,
				'puesto' => $puesto 
		) );
	}
	public function newStep4Action($id) {
		
		throw $this->createNotFoundException ("Parala acá");
		/*
		 * $this->get('session')->remove('candidatos');
		 * $candidatos = $this->get('session')->get('candidatos');
		 */
		$em = $this->getDoctrine ()->getManager ();
		
		$puesto = $em->getRepository ( 'ichTestBundle:Puesto' )->find ( $id );
		
		$query = $em->createQuery ( 'SELECT c.nombre, pc.ponderacion
    			FROM ichTestBundle:Puesto_Competencia pc JOIN ichTestBundle:Competencia c
    			WHERE pc.puesto = :p and pc.competencia = c' )->setParameter ( 'p', $puesto );
		
		$competencias = $query->getResult ();
		
		return $this->render ( 'ichTestBundle:Evaluacion:add3.html.twig', array (
				'competencias' => json_encode ( $competencias ),
				'puesto' => $puesto 
		) );
	}
}