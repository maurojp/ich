<?php

namespace ich\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormError;
use ich\TestBundle\Entity\Puesto;
use ich\TestBundle\Form\PuestoType;

class PuestoController extends Controller
{
    public function indexAction(Request $request)
    {
        // Consulta DQL
        $co = $this->getDoctrine()->getManager();
        $dql = "SELECT p FROM ichTestBundle:Puesto p ORDER BY p.id DESC";
        $puestos = $co->createQuery($dql);
        
        // Paginacion
        $paginator = $this->get('knp_paginator');
        
        $pagination = $paginator->paginate(
            $puestos,
            $request->query->getInt('page',1),
            6
            );
            
        return $this->render('ichTestBundle:Puesto:index.html.twig', array('pagination' => $pagination));
    }
    
    public function addAction()
    {
        $puesto = new Puesto();
        
        $form = $this->createCreateForm($puesto);
        
        return $this->render('ichTestBundle:Puesto:add.html.twig', array('form' => $form->createView()));
    }
    
    private function createCreateForm(Puesto $entity)
    {
        $form = $this->createForm(new PuestoType(), $entity, array(
                'action' => $this->generateUrl('ich_puesto_create'),
                'method' => 'POST'
            ));
        return $form;
    }
    
    public function createAction(Request $request)
    {
        $puesto = new Puesto();
        $form = $this->createCreateForm($puesto);
        $form->handleRequest($request);
        
        if ($form->isValid())
        {
            $co = $this->getDoctrine()->getManager();
            $co->persist($puesto);
            $co->flush();
                
            $this->addFlash('mensaje', 'El puesto ha sido creado.');
                
            return $this->redirectToRoute('ich_puesto_index');    
        }
        
        return $this->render('ichTestBundle:Puesto:add.html.twig', array('form' => $form->createView()));
    }
    
    public function editAction($id)
    {
        $co = $this->getDoctrine()->getManager();
        
        $puesto = $co->getRepository('ichTestBundle:Puesto')->find($id);
        
        if(!$puesto)
        {
            throw $this->createNotFoundException('Puesto no encontrado');
        }
        
        $form = $this->createEditForm($puesto);
        
        return $this->render('ichTestBundle:Puesto:edit.html.twig', array('puesto' => $puesto, 'form' => $form->createView()));
    }
    
    private function createEditForm(Puesto $entity)
    {
        $form = $this->createForm(new PuestoType(), $entity, array('action' => $this->generateUrl('ich_puesto_update', array('id' => $entity->getID())), 'method' => 'PUT'));
        
        return $form;
    }
    
    public function updateAction($id, Request $request)
    {
        $co = $this->getDoctrine()->getManager();
        
        $puesto = $co->getRepository('ichTestBundle:Puesto')->find($id);
        
        if (!$puesto)
        {
            throw $this->createNotFoundException('El puesto no existe');
        }
        
        // Crea un ArrayCollection de las tareas antes del HandleRequest
        
        $originalCompetencias = new ArrayCollection();
        
        foreach ($puesto->getCompetencias() as $competencia) {
            $originalCompetencias->add($competencia);
        }
        
        $form = $this->createEditForm($puesto);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid())
        {
            // Quito las competencias removidas
            foreach ($originalCompetencias as $competencia) {
                if (false === $puesto->getCompetencias()->contains($competencia)) {
                   
                    $co->remove($competencia);
                   
                }
            }
                        
            $co->persist($puesto);
            
            $co->flush();

            $this->addFlash('mensaje', 'Puesto modificado con éxito.');
            return $this->redirectToRoute('ich_puesto_edit', array('id' => $puesto->getId()));
 
        }
        
        return $this->render('ichTestBundle:Puesto:edit.html.twig', array('puesto' => $puesto, 'form' => $form->createView()));
    }
}
