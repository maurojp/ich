<?php

namespace ich\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\FormError;
use ich\TestBundle\Entity\Puesto;
use ich\TestBundle\Form\PuestoType;
use Doctrine\ORM\EntityRepository;
use ich\TestBundle\Entity\Auditoria;
use Symfony\Component\HttpFoundation\JsonResponse;


class PuestoController extends Controller
{
    public function indexAction(Request $request)
    {
       
        if ($request->getMethod () == 'POST')
        {
            
            $indexForm = $this->createIndexForm();

            $indexForm->handleRequest($request);

            $nombre = $indexForm->getData()['nombre'];
            $codigo = $indexForm->getData()['codigo'];
            $empresa = null;

            if($indexForm->getData()['empresa'] != NULL)
                $empresa = $indexForm->getData()['empresa']->getId();

            $co = $this->getDoctrine()->getManager();

            $dql = "SELECT p FROM ichTestBundle:Puesto p WHERE p.auditoria is NULL and p.codigo LIKE '%$codigo%' and p.nombre LIKE '%$nombre%' and IDENTITY(p.empresa) LIKE '%$empresa%' ORDER BY p.codigo ASC";
            $puestos = $co->createQuery($dql);

        }
        
        else
        {
            $co = $this->getDoctrine()->getManager();
            $dql = "SELECT p FROM ichTestBundle:Puesto p where p.auditoria is NULL ORDER BY p.codigo ASC";
            $puestos = $co->createQuery($dql);    
            $indexForm = $this->createIndexForm();
        }
        
        // Paginacion
        $paginator = $this->get('knp_paginator');
        
        $pagination = $paginator->paginate(
            $puestos,
            $request->query->getInt('page',1),
            6
            );


        return $this->render('ichTestBundle:Puesto:index.html.twig', array('form' => $indexForm->createView(), 'pagination' => $pagination));
    }
    
    private function createIndexForm() {
        $form = $this->createFormBuilder ( array () )->add ( 'codigo', null, array (
            ) )->add ( 'nombre', null, array (
            ) )->add ( 'empresa', 'entity', array (
            'class' => 'ichTestBundle:Empresa',
            'choice_label' => 'getNombre',
            'placeholder' => 'Empresa',
            'query_builder' => function (EntityRepository $er) {
                return $er->createQueryBuilder('u')->orderBy('u.nombre', 'ASC');
            }, 
            ) )->add ( 'send', 'submit' )->setAction ( $this->generateUrl ( 'ich_puesto_index' ) )->setMethod ( 'POST' )->getForm ();

            return $form;
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
            
        // Verificar si existen competencias repetidas
            $competencias = new arrayCollection();

            foreach($puesto->getCompetencias() as $puestoCompetencia){

                if($competencias->contains($puestoCompetencia->getCompetencia()->getCodigo())){

                    $form->get('competencias')->addError(new FormError('Hay competencias duplicadas en el puesto.'));

                    return $this->render('ichTestBundle:Puesto:add.html.twig', array('form' => $form->createView()));
                }

                else
                    $competencias->add($puestoCompetencia->getCompetencia()->getCodigo());
            }

            if ($form->isValid())
            {
                $co = $this->getDoctrine()->getManager();
                $co->persist($puesto);
                $co->flush();

                $this->addFlash('mensaje', 'El puesto ha sido creado.');

                return $this->redirectToRoute('ich_puesto_add', array('loop' => 'true'));    
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

        // Verificar si existen competencias repetidas
            $competencias = new arrayCollection();

            foreach($puesto->getCompetencias() as $puestoCompetencia){

                if($competencias->contains($puestoCompetencia->getCompetencia()->getCodigo())){

                    $form->get('competencias')->addError(new FormError('Hay competencias duplicadas en el puesto.'));

                    return $this->render('ichTestBundle:Puesto:edit.html.twig', array('puesto' => $puesto, 'form' => $form->createView()));
                }

                else
                    $competencias->add($puestoCompetencia->getCompetencia()->getCodigo());
            }
            
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


        public function deleteAction(Request $request) {
            $em = $this->getDoctrine ()->getManager ();
            
            $id = $request->request->get ( 'id' );

            $puesto = $em->getRepository ( 'ichTestBundle:Puesto' )->find ( $id );
            
            if (! $puesto) {
                throw $this->createNotFoundException ( 'Puesto no encontrado.' );
            }

            if($this->esPuestoEnUso($puesto)){

                $response = new JsonResponse ( null, 500 );
                $response->setData ( "El puesto " .''.$puesto->getNombre().''." esta siendo usado en la base de datos y no puede eliminarse." );
                return $response;
            }

            $auditoria = new Auditoria;
            
            $usuario = $this->getUser();

            $auditoria->setUsuario($usuario);

            $auditoria->setFechaHoraEliminacion(new \DateTime());

            $em->persist ( $auditoria );
            
            $em->flush ();
            
            $puesto->setAuditoria($auditoria);

            $em->persist ( $puesto );
            
            $em->flush ();

            return new Response ( json_encode ( array (
                'message' => "El Puesto ".''.$puesto->getNombre().''." ha sido eliminado." 
                ) ), 200, array (
            'Content-Type' => 'application/json' 
            ) );
        } 


        private function esPuestoEnUso($puesto){

            foreach($puesto->getEvaluaciones() as $evaluacion){

                foreach($evaluacion->getCuestionarios() as $cuestionario){

                    if($cuestionario->getEstado() == 2 || $cuestionario->getEstado() == 0)
                        return true;
                }
            }

            return false;

        }

    }
