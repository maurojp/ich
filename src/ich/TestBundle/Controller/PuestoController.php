<?php

namespace ich\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use ich\TaskBundle\Entity\Puesto;

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
}
