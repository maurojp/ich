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
			
		$defaultData = array ();
		
		$form = $this->createEvaluarForm($defaultData);

		$query = $em->createQuery ( "SELECT c.apellido, c.nombre, c.nroCandidato FROM ichTestBundle:Candidato c" );
		$candidatos = $query->getResult ();

		return $this->render ( 'ichTestBundle:Evaluacion:add1.html.twig', array ('candidatos' => json_encode($candidatos),
				'form' => $form->createView ()
		) );	
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

			$defaultData = array ();
			
			$form = $this->createEvaluarForm($defaultData);

			$form->handleRequest ( $request );
			
			if ($form->isValid ()) {
				$data = $form->getData ();
	
				$this->get('session')->set('nroCandidatos',$data);

				/*
				 * $dql = "SELECT p FROM ichTestBundle:Puesto p WHERE p.id IN
			 	* (SELECT IDENTITY(pc.puesto) FROM ichTestBundle:Puesto_Competencia pc WHERE
			 	* pc.habilitada = true)";
			 	*/
				
				//BUSCAR PUESTOS CON AL MENOS UNA COMPETENCIA ASIGNADA
				$dql = "SELECT p.id idPuesto, p.nombre nombrePuesto, e.nombre nombreEmpresa
				FROM ichTestBundle:Puesto p JOIN ichTestBundle:Empresa e
				WHERE e.id = IDENTITY(p.empresa) and p.id IN
				(SELECT IDENTITY(pc.puesto) FROM ichTestBundle:Puesto_Competencia pc)";
				$query = $co->createQuery ( $dql );
				
				$puestos= $query->getResult();

				return $this->render ( 'ichTestBundle:Evaluacion:add2.html.twig', array ('puestos' => json_encode($puestos)) );
					
			}
			
			
			$form = $this->createEvaluarForm($defaultData);
			
			//ERROR EN FORMULARIO - VOLVER A SOLICITAR CANDIDATOS
			return $this->render ( 'ichTestBundle:Evaluacion:add1.html.twig', array (
					'form' => $form->createView () 
			) );
			
	}
	
	
	
	public function buscarCandidatosActivosAction(Request $request) {
		
		$data = $request->request->get ( 'nrosCandidato' );
		
		$co = $this->getDoctrine ()->getManager ();
		
		$query = $co->createQuery ( "SELECT c
					FROM ichTestBundle:Candidato c JOIN ichTestBundle:Cuestionario cu
					where cu.candidato = c and cu.estado = 0
					" );
		
		$datosCandidatosActivos = array ();
		// DEVUELVE ARRAY CON ARRAYS DE CANDIDATO
		$candidatosActivos = $query->getResult ();
		
		foreach ( $candidatosActivos as $candidato ) {

			for($i=0, $total = count ( $data ); $i < $total; $i ++) {
				
				if ($candidato->getNroCandidato() == $data [$i])
					$datosCandidatosActivos [] = array (
					'apellido' => $candidato->getApellido (),
					'nombre' => $candidato->getNombre ());
			}
		}
		
		return new JsonResponse ( $datosCandidatosActivos );
	}
	
	
	
	
	public function newStep3Action($id) {
		
		$em = $this->getDoctrine ()->getManager ();
			
		$puesto = $em->getRepository ( 'ichTestBundle:Puesto' )->find ( $id );
			
		if ($puesto->getAuditoria() != NULL) {
			throw $this->createNotFoundException ( 'El Puesto ha sido eliminado.' );
		}
			
		$this->get('session')->set('idPuesto',$id);
			
		$query = $em->createQuery ( "SELECT c.nombre
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
			
		$competenciasInvalidas = $query->getResult ();
		$competencias =  array();

		if(count($competenciasInvalidas) == 0){

			$query = $em->createQuery ( 'SELECT c.nombre, c.codigo, c.descripcion, pc.ponderacion
    			FROM ichTestBundle:Puesto_Competencia pc JOIN ichTestBundle:Competencia c
    			WHERE IDENTITY(pc.puesto) = :p and pc.competencia = c and c.auditoria is NULL' )
				->setParameter ( 'p', $puesto->getId() );
			
			$competencias = $query->getResult ();

			$this->get ( 'session' )->set ( 'competencias', $competencias );
		
		}
	
		return $this->render ( 'ichTestBundle:Evaluacion:add3.html.twig', array (
			'competencias' => json_encode($competencias),
			'competenciasInvalidas' => $competenciasInvalidas,
			'nombrePuesto' => $puesto->getNombre()
				) );
		
	}
	
	
	public function newStep4Action() {
		
		$em = $this->getDoctrine ()->getManager ();
		
		$nroCandidatos = $this->get ( 'session' )->get ( 'nroCandidatos' );
			
		$claveNroCandidatos = array ();
		
		$candidatosSeleccionados = array ();
		
		for($i=0, $total = count ( $nroCandidatos ['candidatos'] ); $i < $total; $i ++) {
		
			$candidato = $em->getRepository ( 'ichTestBundle:Candidato' )->find ( $nroCandidatos ['candidatos'] [$i] );

			if (!$candidato)
			throw $this->createNotFoundException ( "Candidato Nro ".' '.$nroCandidatos ['candidatos'] [$i].''." no encontrado.");

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
		
			$candidatosSeleccionados [] = array (
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
			
		return $this->render ( 'ichTestBundle:Evaluacion:add4.html.twig', array (
			'candidatosSeleccionados' => json_encode($candidatosSeleccionados)
		) );

	}
	
	
	private function comprobarCandidatosActivos($nroCandidatos){
		
	$em = $this->getDoctrine ()->getManager ();
		
	$query = $em->createQuery ( "SELECT c.nroCandidato
					FROM ichTestBundle:Candidato c JOIN ichTestBundle:Cuestionario cu
					where cu.candidato = c and cu.estado = 0
					" );
	
	$candidatosSeleccionadosActivos = array ();
	// DEVUELVE ARRAY CON ARRAYS DE CANDIDATO
	$candidatosActivos = $query->getResult ();
	
	foreach ( $candidatosActivos as $candidato ) {
		$i = 0;
	
		for($i, $total = count ( $nroCandidatos ['candidatos'] ); $i < $total; $i ++) {
			if ($candidato ['nroCandidato'] == $nroCandidatos ['candidatos'] [$i])
				$candidatosSeleccionadosActivos [] = $candidato ['nroCandidato'];
		}
	}
	
	$datosCandidatosActivos = array ();
	
	if (count ( $candidatosSeleccionadosActivos ) != 0) {

		foreach ( $candidatosSeleccionadosActivos as $nroCandidato ) {
	
			$candidato = $em->getRepository ( 'ichTestBundle:Candidato' )->find ( $nroCandidato );
	
			$datosCandidatosActivos [] = array (
					'apellido' => $candidato->getApellido (),
					'nombre' => $candidato->getNombre ()
			);
		}
	}
	
	return $datosCandidatosActivos;
	
}

	
	public function newStep5Action(Request $request) {
		
		$em = $this->getDoctrine ()->getManager ();
		
		$nroCandidatos = $this->get ( 'session' )->get ( 'nroCandidatos' );
		
		$candidatos = array ();
		
		//COMPROBAR SI HAY CANDIDATOS ACTIVOS ENTRE LOS SELECCIONADOS PARA SER EVALUADOS
		if(count($candidatosSeleccionadosActivos = $this->comprobarCandidatosActivos($nroCandidatos)) != 0){
			$response = new JsonResponse(null,500);
			$response->setData($candidatosSeleccionadosActivos);
			return $response;
		}
		else //OBTENER ENTIDADES DE CANDIDATOS SELECCIONADOS
			{
				for($i= 0, $total = count ( $nroCandidatos ['candidatos'] ); $i < $total; $i ++) {
			
					$candidato = $em->getRepository ( 'ichTestBundle:Candidato' )->find ( $nroCandidatos ['candidatos'] [$i] );
					if (!$candidato) {
						$response = new JsonResponse(null,500);
						$response->setData('Candidato seleccionado no encontrado.');
						return $response;
					}
					
					$candidatos [] = $candidato;
				}
			}
	
			
		$idPuesto = $this->get('session')->get('idPuesto');
		
		$puesto = $em->getRepository ( 'ichTestBundle:Puesto' )->find ( $idPuesto );
		
		if ($puesto->getAuditoria() != NULL) {
			$response = new JsonResponse(null,500);
			$response->setData('El Puesto seleccionado ha sido eliminado.');
			return $response;
		}
		
		

		$competenciasTemporal = $this->get ( 'session' )->get ( 'competencias' );
		
		//COMPETENCIAS VÁLIDAS PARA SER EVALUADAS
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
		
		//VERIFICAR SI COMPETENCIAS PREVIAMENTE VALIDADAS COINCIDEN CON ACTUALES
		if (count ( $competencias ) != count ( $competenciasTemporal ))
		{
			$response = new JsonResponse(null,500);
			$response->setData('Existe al menos una Competencia que ya no cumple los requisitos para ser evaluada.');
			return $response;
		}
		
		//VERIFICAR SI EXISTE PARÁMETRO DE CONFIGURACIÓN
		if(!$this->container->hasParameter('ichTestBundle.preguntasPorBloque'))
		{
			$response = new JsonResponse(null,500);
			$response->setData('ParÃ¡metro preguntasPorBloque requerido no disponible.');
			return $response;
		}
		
		
		
		//CREAR EVALUACIÓN
		
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
						
						$copiaFactor->setNroOrden ( $factor->getNroOrden () );
						
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
			
			//GUARDAR COPIA PREGUNTAS PARA MEZCLARLAS Y DIVIDIRLAS POR BLOQUES
			$copiaPreguntasCuestionario = array();
			
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
						
						$copiaPreguntasCuestionario[] = $copiaPregunta;
		
					}
				}
			}
			
			//ASIGNAR A COPIA PREGUNTAS NRO ORDEN Y NRO BLOQUE ALEATORIAMENTE 
			shuffle ( $copiaPreguntasCuestionario );
			
			$preguntasPorBloque = $this->container->getParameter('ichTestBundle.preguntasPorBloque');
			
			$bloqueActual = 1;
			
			$preguntasBloqueActual = 0;
				
			for($i = 0, $total = count ($copiaPreguntasCuestionario); $i < $total; $i ++) {
			
				if($preguntasPorBloque == $preguntasBloqueActual){
					$preguntasBloqueActual = 0;
					$bloqueActual++;
				}
				
				$copiaPregunta = $copiaPreguntasCuestionario[$i];
				
				$preguntasBloqueActual++;
				
				$copiaPregunta->setNroOrden($preguntasBloqueActual);
				
				$copiaPregunta->setNroBloque($bloqueActual);
				
			}
				
		}
		
		$em->flush ();
		
		//GENERAR NOMBRE PLANILLA EXCEL
		$nombre_xls = date_format ( new \datetime (), 'Y-m-d-H-i' ) . '' . "-" . '' . $puesto->getNombre ();
		
		$this->get ( 'session' )->remove ( 'competencias' );
		
		$this->get ( 'session' )->remove ( 'nroCandidatos' );
		
		$this->get ( 'session' )->remove ( 'idPuesto' );
		
		$this->get ( 'session' )->remove ( 'claveNroCandidatos' );
		
		return new JsonResponse ( $nombre_xls );
	}
	
	
	public function verificarEstadoCuestionarioAction(){
	
		if($request->getMethod() == 'POST');
		
		$date2 = new \DateTime;
		$date1 = new \DateTime('2016-11-10');
		$diff = $date1->diff($date2);
		echo ($diff->format('%d') * 24)+ $diff->format('%h')+ ($diff->format('%i')/100) == 82.55;
	}
	

	
	public function ingresoCuestionarioAction(){
		
		$this->container->getParameter('ichTestBundle.instruccionesCuestionario');
	}

}