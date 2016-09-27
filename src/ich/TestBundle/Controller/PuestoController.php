<?php

namespace ich\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
}
