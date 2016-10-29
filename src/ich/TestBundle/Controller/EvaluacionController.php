<?php

namespace ich\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints\Date;
use ich\TestBundle\Entity\Cuestionario;
use ich\TestBundle\Entity\Evaluacion;
use ich\TestBundle\Entity\CopiaCompetencia;
use ich\TestBundle\Entity\CopiaFactor;
use ich\TestBundle\Entity\CopiaPregunta;
use ich\TestBundle\Entity\CopiaOpcionRespuesta;

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
		
		$co = $this->getDoctrine ()->getManager ();
		
		if ($request->isXMLHttpRequest ()) {

					
			/*
			 * $dql = "SELECT p FROM ichTestBundle:Puesto p WHERE p.id IN
			* (SELECT IDENTITY(pc.puesto) FROM ichTestBundle:Puesto_Competencia pc WHERE
			* pc.habilitada = true and IDENTITY(pc.competencia) NOT IN
			* (SELECT c.id FROM ichTestBundle:Competencia c where c.auditoria is not NULL))";
			*/
					
			$dql = "SELECT p.id idPuesto, p.nombre nombrePuesto, e.nombre nombreEmpresa 
			FROM ichTestBundle:Puesto p JOIN ichTestBundle:Empresa e
			WHERE e.id = IDENTITY(p.empresa) and p.id IN 
			(SELECT IDENTITY(pc.puesto) FROM ichTestBundle:Puesto_Competencia pc WHERE IDENTITY(pc.competencia) NOT IN 
			(SELECT c.id FROM ichTestBundle:Competencia c where c.auditoria is not NULL))";
			$query = $co->createQuery ( $dql );
					
			$puestos= $query->getResult();

			return new JsonResponse ( $puestos);
		}

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
				
				$candidatosSeleccionadosActivos = array ();
				$candidatos = array ();
				// DEVUELVE ARRAY CON ARRAYS DE CANDIDATO
				$candidatosActivos = $query->getResult ();
				
				foreach ( $candidatosActivos as $candidato ) {
					$i = 0;
					
					for($i, $total = count ( $data ['candidatos'] ); $i < $total; $i ++) {
						if ($candidato ['nroCandidato'] == $data ['candidatos'] [$i])
							$candidatosSeleccionadosActivos [] = $candidato ['nroCandidato'];
					}
					
					
				}
				
				if (count ( $candidatosSeleccionadosActivos ) == 0) {
					
					$i = 0;
					
					for($i, $total = count ( $data ['candidatos'] ); $i < $total; $i ++) {
						
							$candidato = $co->getRepository ( 'ichTestBundle:Candidato' )->find ( $data ['candidatos'] [$i] );
							$candidatos [] = $candidato;
						
					}
						
					
					$this->get('session')->set('candidatos',$candidatos);


					return $this->render ( 'ichTestBundle:Evaluacion:add2.html.twig', array () );
					
				} 

				else {
					$datosCandidatosActivos = array ();
					foreach ( $candidatosSeleccionadosActivos as $nroCandidato ) {
						
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
		
		
		
	}
	
	
	public function newStep3Action(Request $request, $id) {
		
		/*
		 * $this->get('session')->remove('candidatos');
		 * $candidatos = $this->get('session')->get('candidatos');
		 */
		
		$em = $this->getDoctrine ()->getManager ();

		if ($request->isXMLHttpRequest ()) {
			
			$puesto = $this->get('session')->get('puesto');
			
			$query = $em->createQuery ( 'SELECT c.nombre, c.codigo, c.descripcion, pc.ponderacion
    			FROM ichTestBundle:Puesto_Competencia pc JOIN ichTestBundle:Competencia c
    			WHERE IDENTITY(pc.puesto) = :p and pc.competencia = c and c.auditoria is NULL' )->setParameter ( 'p', $puesto->getId() );
			
			$competencias = $query->getResult ();
			return new JsonResponse ( $competencias );
		}

		
		$puesto = $em->getRepository ( 'ichTestBundle:Puesto' )->find ( $id );
		
		$this->get('session')->set('puesto',$puesto);

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
	
	
	public function newStep4Action(Request $request) {
		
		
		if ($request->isXMLHttpRequest ()) {
				
			$cuestionarios = $this->get('session')->get('cuestionarios');
			
			$array = array ();
				
			foreach ( $cuestionarios as $cuestionario ) {
				
				if($cuestionario->getCandidato()->getTipoDocumento() == 1)
				$tipo = "DNI";
				else if($cuestionario->getCandidato()->getTipoDocumento() == 2)
					$tipo = "LE";
					else if($cuestionario->getCandidato()->getTipoDocumento() == 3)
						$tipo = "LC";
						else if($cuestionario->getCandidato()->getTipoDocumento() == 4)
							$tipo = "PP";
										
				$array [] = array (
						'apellido' => $cuestionario->getCandidato()->getApellido(),
						'nombre' => $cuestionario->getCandidato()->getNombre(),
						'tipoDocumento' => $tipo,
						'documento' => $cuestionario->getCandidato()->getNroDocumento(),
						'clave'  => $cuestionario->getClave()
				);
			}
			
			return new JsonResponse ( $array );
		}
		
		
		/* $this->get('session')->remove('candidatos'); */
		
		$candidatos = $this->get('session')->get('candidatos');
		 
		$em = $this->getDoctrine ()->getManager ();
		
		$puesto = $this->get('session')->get('puesto');
		
		/*string3 = $string1.' '.$string2;*/
	  	/*date("y-m-d, Y G:i")*/
		/*throw $this->createNotFoundException ();*/
		
		$evaluacion = new Evaluacion();
		
		$evaluacion->setPuesto($puesto);

		$evaluacion->setFechaCreacion(new \DateTime());

		$evaluacion->setNombre("Ev_".''."P_".''.$puesto->getNombre().''."_".''.bin2hex(random_bytes(2)));

		$cuestionarios = array();
		
		foreach ( $candidatos as $candidato ) {
			
			$cuestionario = new Cuestionario();
			
			$random = random_bytes(4);
			
			$clave = bin2hex($random);
	
			$cuestionario->setClave($clave);
			$cuestionario->setEstado(0);
			$cuestionario->setCantAccesos(0);
			$cuestionario->setCantMaxAccesos(2);
			$cuestionario->setTiempoMax(48.00);
			$cuestionario->setTiempoMaxActivo(0.30);
			$cuestionario->setPuntajeTotal(0);
			$cuestionario->setCandidato($candidato);
			
			$cuestionarios[] =$cuestionario;
			
		}
		$this->get('session')->set('cuestionarios',$cuestionarios);
		$this->get('session')->set('evaluacion',$evaluacion);
		
		return $this->render ( 'ichTestBundle:Evaluacion:add4.html.twig', array () );
	}
	
	
	public function newStep5Action(Request $request) {
		
				
				$em = $this->getDoctrine ()->getManager ();
				
				$evaluacion = $this->get('session')->get('evaluacion');
				
				$em->merge ( $evaluacion );
					
				$em->flush ();


				$cuestionarios = $this->get('session')->get('cuestionarios');

				$puesto = $this->get('session')->get('puesto');

				$query = $em->createQuery ( '
				SELECT e.id
    			FROM ichTestBundle:Evaluacion e
    			WHERE IDENTITY(e.puesto) = :p and e.id > all 
    			(SELECT e1.id FROM ichTestBundle:Evaluacion e1 
    			  WHERE e1.id <> e.id)')->setParameter ( 'p', $puesto->getId() );
			
			    $evaluaciones = $query->getResult ();

			    $evaluacion = $em->getRepository ( 'ichTestBundle:Evaluacion' )->find ( $evaluaciones[0] ['id'] );


				foreach ( $cuestionarios as $cuestionario ) {

				$cuestionario->setEvaluacion($evaluacion);

                $em->merge ( $cuestionario );
					
				

				}
				
				$em->flush ();
				
				$this->get('session')->remove('cuestionarios');

				$this->get('session')->remove('evaluacion');

				$this->get('session')->remove('candidatos');
				
				$this->get('session')->remove('puesto');
				
				return new JsonResponse ( true );
			
			
		
	}
}