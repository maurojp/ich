<?php

namespace ich\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\httpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormError;

class SeguridadController extends Controller
{
    public function preloginAction()
    {
        return $this->render('ichTestBundle:Seguridad:prelogin.html.twig');
    }
    
    public function loginAction()
    {
        $authUtils = $this->get('security.authentication_utils');
        
        $error = $authUtils->getLastAuthenticationError();
        
        $lastUsername = $authUtils->getLastUsername();
        
        return $this->render('ichTestBundle:Seguridad:login.html.twig', array('last_username' => $lastUsername, 'error' => $error));
    }
    
    public function loginCheckAction()
    {
        
    }
    
    public function ingresocandidatoAction(Request $request)
    {
        $datosIngreso = array('mensaje' => 'Ingrese los datos de Ingreso.');
        $form = $this->createFormBuilder($datosIngreso)
            ->add('nroDocumento', IntegerType::Class)
            ->add('clave', PasswordType::Class)
            ->add('ingresar', SubmitType::Class)
            ->getForm();
        
        $form->handleRequest($request);
        
        if ($form->isValid())
        {
            $data = $form->getData();
            
            $repositorio = $this->getDoctrine()->getRepository('ichTestBundle:Candidato');
            
            $candidato = $repositorio->findOneBy(array('nroDocumento' => $data['nroDocumento']));
            
            if (!$candidato) 
            {
                $form->addError(new FormError('Candidato no Encontrado.'));
                
                return $this->render('ichTestBundle:Seguridad:ingresocandidato.html.twig', array('form' => $form->createView()));
            }
            
            $repo_cuestionarios = $this->getDoctrine()->getRepository('ichTestBundle:Cuestionario');
            
            $cuestionario = $repo_cuestionarios->findOneBy(array('clave' => $data['clave']));
            
            if (!$cuestionario)
            {
                $form->addError(new FormError('Cuestionario no Encontrado.'));
               
                return $this->render('ichTestBundle:Seguridad:ingresocandidato.html.twig', array('form' => $form->createView())); 
            }
            
            if ($cuestionario->getCandidato()->getId() == $candidato->getId())
            {
                print_r('Acceso Autorizado');
            } else
            {
                print_r('Datos no Coherentes');
            }
            
        }
        
        return $this->render('ichTestBundle:Seguridad:ingresocandidato.html.twig', array('form' => $form->createView()));
    }
}