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
		
		if (! $request->isXMLHttpRequest ()) {
			
		$defaultData = array ();
		
		$form = $this->createEvaluarForm($defaultData);
		
		return $this->render ( 'ichTestBundle:Evaluacion:add1.html.twig', array (
				'form' => $form->createView ()
		) );
		}
		
		$query = $em->createQuery ( "SELECT c.apellido, c.nombre, c.nroCandidato FROM ichTestBundle:Candidato c" );
		$candidatos = $query->getResult ();
			
		return new JsonResponse ( $candidatos );
		
		
	}
	
	
	private function createEvaluarForm($array){
		
		$form = $this->createFormBuilder ( $array )->add ( 'candidatos', CollectionType::class, array (
				'entry_type' => NumberType::class,
				'by_reference' => false,
				'allow_add' => true
		) )->add ( 'send', SubmitType::class )
		->setAction ( $this->generateUrl ( 'ich_evaluacion_newStep2' ) )
		->setMethod ( 'POST' )
		->getForm ();
		
		
		return $form;
	}
	
	
	
	
	public function newStep2Action(Request $request) {
		
		$co = $this->getDoctrine ()->getManager ();

		if ($request->getMethod () == 'POST') {
			$defaultData = array ();
			
			$form = $this->createEvaluarForm($defaultData);
			
			$form->handleRequest ( $request );
			
			if ($form->isValid ()) {
				$data = $form->getData ();
	
				$this->get('session')->set('nroCandidatos',$data);

				return $this->render ( 'ichTestBundle:Evaluacion:add2.html.twig', array () );
					
			}
			
			return $this->render ( 'ichTestBundle:Evaluacion:add1.html.twig', array (
					'form' => $form->createView () 
			) );
		}
		
		
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
		
		
		
	}
	
	
	
	public function buscarCandidatosActivosAction(Request $request) {
		
		$data = $request->request->get ( 'nrosCandidato' );
		
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

			for($i=0, $total = count ( $data ); $i < $total; $i ++) {
				
				if ($candidato ['nroCandidato'] == $data [$i])
					$candidatosSeleccionadosActivos [] = $candidato ['nroCandidato'];
			}
		}
		
		$datosCandidatosActivos = array ();
		foreach ( $candidatosSeleccionadosActivos as $nroCandidato ) {
			
			$candidato = $co->getRepository ( 'ichTestBundle:Candidato' )->find ( $nroCandidato );
			
			$datosCandidatosActivos [] = array (
					'apellido' => $candidato->getApellido (),
					'nombre' => $candidato->getNombre () 
			);
		}
		
		return new JsonResponse ( $datosCandidatosActivos );
	}
	
	
	
	
	public function newStep3Action(Request $request, $id) {
		
		$em = $this->getDoctrine ()->getManager ();

		if (! $request->isXMLHttpRequest ()) {
			
			$puesto = $em->getRepository ( 'ichTestBundle:Puesto' )->find ( $id );
			
			if ($puesto->getAuditoria() != NULL) {
				throw $this->createNotFoundException ( 'El Puesto ha sido eliminado.' );
			}
			
			$this->get('session')->set('idPuesto',$id);
			
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
						'competencias' => $competencias,
						'error' => true,
						'puesto' => $puesto
				) );
			}
			
			return $this->render ( 'ichTestBundle:Evaluacion:add3.html.twig', array (
					'competencias' => $competencias,
					'error' => false,
					'puesto' => $puesto
			) );
		}
		
			
		$puesto = $em->getRepository ( 'ichTestBundle:Puesto' )->find ( $id );
			
		if ($puesto->getAuditoria() != NULL){
			throw $this->createNotFoundException ( 'El Puesto ha sido eliminado.' );
		}
			
		$query = $em->createQuery ( 'SELECT c.nombre, c.codigo, c.descripcion, pc.ponderacion
    			FROM ichTestBundle:Puesto_Competencia pc JOIN ichTestBundle:Competencia c
    			WHERE IDENTITY(pc.puesto) = :p and pc.competencia = c and c.auditoria is NULL' )->setParameter ( 'p', $puesto->getId() );
			
		$competencias = $query->getResult ();
		
		$this->get ( 'session' )->set ( 'competencias', $competencias );
		
		return new JsonResponse ( $competencias );
		
		
	}
	
	
	public function newStep4Action(Request $request) {
		
		
		if (! $request->isXMLHttpRequest ()) {
				
			return $this->render ( 'ichTestBundle:Evaluacion:add4.html.twig', array () );
			
		}

		$em = $this->getDoctrine ()->getManager ();
		
		$nroCandidatos = $this->get ( 'session' )->get ( 'nroCandidatos' );
		$array = array ();
			
		$claveNroCandidatos = array ();
			
		for($i=0, $total = count ( $nroCandidatos ['candidatos'] ); $i < $total; $i ++) {
		
			$candidato = $em->getRepository ( 'ichTestBundle:Candidato' )->find ( $nroCandidatos ['candidatos'] [$i] );
			if (!$candidato) {
				$response = new JsonResponse(null,500);
				$response->setData('Candidato seleccionado no encontrado.');
				return $response;
			}
			if($candidato->getTipoDocumento() == 1)
				$tipo = "DNI";
				else if($candidato->getTipoDocumento() == 2)
					$tipo = "LE";
					else if($candidato->getTipoDocumento() == 3)
						$tipo = "LC";
						else if($candidato->getTipoDocumento() == 4)
							$tipo = "PP";
		
							$random = random_bytes(4);
							$clave = bin2hex($random);
		
							$array [] = array (
									'apellido' => $candidato->getApellido(),
									'nombre' => $candidato->getNombre(),
									'tipoDocumento' => $tipo,
									'documento' => $candidato->getNroDocumento(),
									'clave'  => $clave
							);
		
							$claveNroCandidatos  [] = array (
									'nroCandidato' => $nroCandidatos ['candidatos'] [$i],
									'clave'  => $clave
		
							);
		}
			
		$this->get('session')->set('claveNroCandidatos',$claveNroCandidatos);
			
		return new JsonResponse ( $array );

	}
	
	
	public function newStep5Action(Request $request) {
		
		$em = $this->getDoctrine ()->getManager ();
		

		//COMPROBAR SI HAY CANDIDATOS ACTIVOS ENTRE LOS SELECCIONADOS PARA SER EVALUADOS
		$nroCandidatos = $this->get ( 'session' )->get ( 'nroCandidatos' );
		
		$query = $em->createQuery ( "SELECT c.nroCandidato
					FROM ichTestBundle:Candidato c JOIN ichTestBundle:Cuestionario cu
					where cu.candidato = c and cu.estado = 0
					" );
		
		$candidatosSeleccionadosActivos = array ();
		
		$candidatos = array ();
		
		// DEVUELVE ARRAY CON ARRAYS DE CANDIDATO
		$candidatosActivos = $query->getResult ();
		
		foreach ( $candidatosActivos as $candidato ) {
			$i = 0;
				
			for($i, $total = count ( $nroCandidatos ['candidatos'] ); $i < $total; $i ++) {
				if ($candidato ['nroCandidato'] == $nroCandidatos ['candidatos'] [$i])
					$candidatosSeleccionadosActivos [] = $candidato ['nroCandidato'];
			}
		}
		
		if (count ( $candidatosSeleccionadosActivos ) == 0) {
				
			$i = 0;
				
			for($i, $total = count ( $nroCandidatos ['candidatos'] ); $i < $total; $i ++) {
		
				$candidato = $em->getRepository ( 'ichTestBundle:Candidato' )->find ( $nroCandidatos ['candidatos'] [$i] );
				if (!$candidato) {
					$response = new JsonResponse(null,500);
					$response->setData('Candidato seleccionado no encontrado.');
					return $response;
				}
				$candidatos [] = $candidato;
			}
		}
		
		else {
				
			$datosCandidatosActivos = array ();
			foreach ( $candidatosSeleccionadosActivos as $nroCandidato ) {
		
				$candidato = $em->getRepository ( 'ichTestBundle:Candidato' )->find ( $nroCandidato );
		
				$datosCandidatosActivos [] = array (
						'apellido' => $candidato->getApellido (),
						'nombre' => $candidato->getNombre ()
				);
			}
				
			$response = new JsonResponse(null,500);
			$response->setData($datosCandidatosActivos);
			return $response;

		}
		
		
	
		$idPuesto = $this->get('session')->get('idPuesto');
		
		$puesto = $em->getRepository ( 'ichTestBundle:Puesto' )->find ( $idPuesto );
		
		if ($puesto->getAuditoria() != NULL) {
			$response = new JsonResponse(null,500);
			$response->setData('El Puesto seleccionado ha sido eliminado.');
			return $response;
		}
		
		

		$competenciasTemporal = $this->get ( 'session' )->get ( 'competencias' );
		
		//COMPETENCIAS V�LIDAS PARA SER EVALUADAS
		$query = $em->createQuery ( "SELECT c.nombre, c.codigo, c.descripcion, pc.ponderacion
    			FROM ichTestBundle:Puesto_Competencia pc JOIN ichTestBundle:Competencia c
    			WHERE IDENTITY(pc.puesto) = :p and pc.competencia = c and c.auditoria is NULL and c.id IN
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
				HAVING count(distinct pr.id) >= 2)))" )->setParameter ( 'p', $puesto->getId () );
		
		$competencias = $query->getResult ();
		
		
		if (count ( $competencias ) != count ( $competenciasTemporal ))
		{
			$response = new JsonResponse(null,500);
			$response->setData('Existe al menos una Competencia que ya no cumple los requisitos para ser evaluada.');
			return $response;
		}
		
		
		
		$evaluacion = new Evaluacion();
		
		$evaluacion->setPuesto($puesto);
		
		$evaluacion->setFechaCreacion(new \DateTime());
		
		$evaluacion->setNombre("Ev_".''."P_".''.$puesto->getNombre().''."_".''.bin2hex(random_bytes(2)));
		
		$claveNroCandidatos = $this->get('session')->get('claveNroCandidatos');
		
		foreach ( $candidatos as $candidato ) {
				
			$cuestionario = new Cuestionario();
			
			for($i=0, $total = count ($claveNroCandidatos); $i < $total; $i ++) {
				if ($candidato->getNroCandidato() == $claveNroCandidatos[$i]['nroCandidato'])
					$cuestionario->setClave($claveNroCandidatos[$i]['clave']);
			}
				
			$cuestionario->setEstado(0);
			$cuestionario->setCantAccesos(0);
			$cuestionario->setCantMaxAccesos(2);
			$cuestionario->setTiempoMax(48.00);
			$cuestionario->setTiempoMaxActivo(0.30);
			$cuestionario->setPuntajeTotal(0);
			$cuestionario->setCandidato($candidato);
			$cuestionario->setEvaluacion($evaluacion);
			$evaluacion->addCuestionario($cuestionario);	
		}
		
		$em->persist ( $evaluacion );
		
		$em->flush ();
		
		
		$cuestionarios = $evaluacion->getCuestionarios();
			
		foreach ( $cuestionarios as $cuestionario ) {
			
			foreach ( $competencias as $competencia ) {
				
				$copiaCompetencia = new CopiaCompetencia ();
				
				$copiaCompetencia->setCodigo ( $competencia ['codigo'] );
				
				$copiaCompetencia->setDescripcion ( $competencia ['descripcion'] );
				
				$copiaCompetencia->setNombre ( $competencia ['nombre'] );
				
				$copiaCompetencia->setPonderacion ( $competencia ['ponderacion'] );
				
				$copiaCompetencia->setCuestionario ( $cuestionario );
				
				$cuestionario->addCopiaCompetencia ( $copiaCompetencia );
			}
			
			$em->persist ( $cuestionario );
		}
		
		$em->flush ();
		
		
		
		$cuestionarios = $em->getRepository ( 'ichTestBundle:Cuestionario' )->findBy ( array (
				'evaluacion' => $evaluacion 
		) );
		
		foreach ( $cuestionarios as $cuestionario ) {
			
			foreach ( $cuestionario->getCopiaCompetencias () as $copiaCompetencia ) {
				
				$competencia = $em->getRepository ( 'ichTestBundle:Competencia' )->findOneBy ( array (
						'codigo' => $copiaCompetencia->getCodigo () 
				) );
				
				$factores = $competencia->getFactores ();
				
				foreach ( $factores as $factor ) {
					
					if (count ( $factor->getPreguntas () ) >= 2) {
						
						$copiaFactor = new CopiaFactor ();
						
						$copiaFactor->setCodigo ( $factor->getCodigo () );
						
						$copiaFactor->setDescripcion ( $factor->getDescripcion () );
						
						$copiaFactor->setNombre ( $factor->getNombre () );
						
						$copiaFactor->setCopiaCompetencia ( $copiaCompetencia );
						
						$copiaCompetencia->addCopiaFactore ( $copiaFactor );
					}
				}
				
				$em->persist ( $copiaCompetencia );
			}
		}
		
		$em->flush ();
		
		
		
		foreach ( $cuestionarios as $cuestionario ) {
			
			foreach ( $cuestionario->getCopiaCompetencias () as $copiaCompetencia ) {
				
				$competencia = $em->getRepository ( 'ichTestBundle:Competencia' )->findOneBy ( array (
						'codigo' => $copiaCompetencia->getCodigo () 
				) );
				
				$copiaFactores = $copiaCompetencia->getCopiaFactores ();
				$factores = $competencia->getFactores ();
				$factorActual;
				
				foreach ( $copiaFactores as $copiaFactor ) {
					
					foreach ( $factores as $factor ) {
						if ($factor->getCodigo () == $copiaFactor->getCodigo ()) {
							$factorActual = $factor;
							break;
						}
					}
					
					$preguntas = $factorActual->getPreguntas ();
					
					//SELECCIONAR 2 PREGUNTAS AL AZAR
					$posiciones = range ( 0, count ( $preguntas ) - 1 );
					
					shuffle ( $posiciones );
					
					$posicionesRandom = array_slice ( $posiciones, 0, 2 );
					
					for($i = 0; $i < 2; $i ++) {
						
						$copiaPregunta = new CopiaPregunta ();
						
						$copiaPregunta->setCodigo ( $preguntas [$posicionesRandom [$i]]->getCodigo () );
						
						$copiaPregunta->setPregunta ( $preguntas [$posicionesRandom [$i]]->getPregunta () );
						
						$copiaPregunta->setDescripcion ( $preguntas [$posicionesRandom [$i]]->getDescripcion () );
						
						$copiaPregunta->setCopiaFactor ( $copiaFactor );
						
						$copiaFactor->addCopiaPregunta ( $copiaPregunta );
					}
					
					$em->persist ( $copiaFactor );
				}
			}
		}
		
		$em->flush ();
		
		
		
		
		foreach ( $cuestionarios as $cuestionario ) {
			
			foreach ( $cuestionario->getCopiaCompetencias () as $copiaCompetencia ) {
				
				$competencia = $em->getRepository ( 'ichTestBundle:Competencia' )->findOneBy ( array (
						'codigo' => $copiaCompetencia->getCodigo () 
				) );
				
				$copiaFactores = $copiaCompetencia->getCopiaFactores ();
				$factores = $competencia->getFactores ();
				$factorActual;
				
				foreach ( $copiaFactores as $copiaFactor ) {
					
					foreach ( $factores as $factor ) {
						if ($factor->getCodigo () == $copiaFactor->getCodigo ()) {
							$factorActual = $factor;
							break;
						}
					}
					
					$copiaPreguntas = $copiaFactor->getCopiaPreguntas ();
					$preguntas = $factorActual->getPreguntas ();
					$preguntaActual;
					
					foreach ( $copiaPreguntas as $copiaPregunta ) {
						
						foreach ( $preguntas as $pregunta ) {
							if ($pregunta->getCodigo () == $copiaPregunta->getCodigo ()) {
								$preguntaActual = $pregunta;
								break;
							}
						}
						
						$preguntaOpcionesRespuesta = $preguntaActual->getOpcionesRespuesta ();
						
						foreach ( $preguntaOpcionesRespuesta as $preguntaOpcionRespuesta ) {
							
							$opcionRespuesta = $preguntaOpcionRespuesta->getOpcionRespuesta ();
							
							$copiaOpcionRespuesta = new CopiaOpcionRespuesta ();
							
							$copiaOpcionRespuesta->setDescripcion ( $opcionRespuesta->getDescripcion () );
							
							$copiaOpcionRespuesta->setOrdenEvaluacion ( $opcionRespuesta->getOrdenEvaluacion () );
							
							$copiaOpcionRespuesta->setPonderacion ( $preguntaOpcionRespuesta->getPonderacion () );
							
							$copiaOpcionRespuesta->setCopiaPregunta ( $copiaPregunta );
							
							$copiaPregunta->addCopiaOpcionesRespuestum ( $copiaOpcionRespuesta );
						}
						
						$em->persist ( $copiaPregunta );
					}
				}
			}
		}
		
		$em->flush ();
		
		$date = date_format ( new \datetime (), 'Y-m-d-H-i' );
		
		$nombre_xls = $date . '' . "-" . '' . $puesto->getNombre ();
		
		$this->get ( 'session' )->remove ( 'competencias' );
		
		$this->get ( 'session' )->remove ( 'nroCandidatos' );
		
		$this->get ( 'session' )->remove ( 'idPuesto' );
		
		$this->get ( 'session' )->remove ( 'claveNroCandidatos' );
		
		return new JsonResponse ( $nombre_xls );
	}
}