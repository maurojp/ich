<?php

namespace ich\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormError;
use ich\TestBundle\Entity\Pregunta;
use ich\TestBundle\Form\PreguntaType;

class PreguntaController extends Controller
{
    public function indexAction(Request $request)
    {
        // Consulta DQL
        $co = $this->getDoctrine()->getManager();
        $dql = "SELECT p FROM ichTestBundle:Pregunta p ORDER BY p.id DESC";
        $preguntas = $co->createQuery($dql);
        
        // Paginacion
        $paginator = $this->get('knp_paginator');
        
        $pagination = $paginator->paginate(
            $preguntas,
            $request->query->getInt('page',1),
            6
            );
            
        return $this->render('ichTestBundle:Pregunta:index.html.twig', array('pagination' => $pagination));
    }
    
    public function addAction()
    {
        $pregunta = new Pregunta();
        
        $form = $this->createCreateForm($pregunta);
        
        return $this->render('ichTestBundle:Pregunta:add.html.twig', array('form' => $form->createView()));
    }
    
    private function createCreateForm(Pregunta $entity)
    {
        $form = $this->createForm(new PreguntaType(), $entity, array(
                'action' => $this->generateUrl('ich_pregunta_create'),
                'method' => 'POST'
            ));
        return $form;
    }
    
  
    public function createAction(Request $request)
    {
    	if ($request->isXMLHttpRequest ()) {
    		
			$competencia_id = $request->request->get ( 'competencia_id' );
			
			$em = $this->getDoctrine ()->getManager ();
			
			$competencia = $em->getRepository ( 'ichTestBundle:Competencia' )->findById ( $competencia_id );
			
			$factores = $em->getRepository ( 'ichTestBundle:Factor' )->findByCompetencia ( $competencia );
			
			
			$arrayCollection = array();
			
			foreach($factores as $item) {
				$arrayCollection[] = array(
						'id' => $item->getId(),'nombre' => $item->getNombre(),
						
				);
			}
			return new JsonResponse($arrayCollection);

		}
		
        $pregunta = new Pregunta();
        $form = $this->createCreateForm($pregunta);
        $form->handleRequest($request);
        
        if ($form->isValid())
        {
            $co = $this->getDoctrine()->getManager();
            $co->persist($pregunta);
            $co->flush();
                
            $this->addFlash('mensaje', 'La pregunta ha sido creado.');
                
            return $this->redirectToRoute('ich_pregunta_index');    
        }
        
        return $this->render('ichTestBundle:Pregunta:add.html.twig', array('form' => $form->createView()));
    }
    
    public function editAction($id)
    {
        $co = $this->getDoctrine()->getManager();
        
        $pregunta = $co->getRepository('ichTestBundle:Pregunta')->find($id);
        
        if(!$pregunta)
        {
            throw $this->createNotFoundException('Pregunta no encontrado');
        }
        
        $form = $this->createEditForm($pregunta);
        
        return $this->render('ichTestBundle:Pregunta:edit.html.twig', array('pregunta' => $pregunta, 'form' => $form->createView()));
    }
    
    private function createEditForm(Pregunta $entity)
    {
        $form = $this->createForm(new PreguntaType(), $entity, array('action' => $this->generateUrl('ich_pregunta_update', array('id' => $entity->getID())), 'method' => 'PUT'));
        
        return $form;
    }
    
    public function updateAction($id, Request $request)
    {
        $co = $this->getDoctrine()->getManager();
        
        $pregunta = $co->getRepository('ichTestBundle:Pregunta')->find($id);
        
        if (!$pregunta)
        {
            throw $this->createNotFoundException('La pregunta no existe');
        }
        
        // Crea un ArrayCollection de las tareas antes del HandleRequest
        
        $originalOpcionesRespuesta = new ArrayCollection();
        
        foreach ($pregunta->getOpcionesRespuesta() as $opcionRespuesta) {
            $originalOpcionesRespuesta->add($opcionRespuesta);
        }
        
        $form = $this->createEditForm($pregunta);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            // Quito las opcionRespuestas removidas
            foreach ($originalOpcionesRespuesta as $opcionRespuesta) {
                if (false === $pregunta->getOpcionesRespuesta()->contains($opcionRespuesta)) {
                   
                    $co->remove($opcionRespuesta);
                   
                }
            }
                        
            $co->persist($pregunta);
            
            $co->flush();

            $this->addFlash('mensaje', 'Pregunta modificado con éxito.');
            return $this->redirectToRoute('ich_pregunta_edit', array('id' => $pregunta->getId()));
 
        }
        
        return $this->render('ichTestBundle:Pregunta:edit.html.twig', array('pregunta' => $pregunta, 'form' => $form->createView()));
    }
}
