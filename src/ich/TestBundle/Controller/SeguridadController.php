<?php

namespace ich\TestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\httpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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
        
        if($authUtils->getLastAuthenticationError())
            $error = 1;

        else
            $error = 0;
        
        $lastUsername = $authUtils->getLastUsername();
        
        return $this->render('ichTestBundle:Seguridad:login.html.twig', array('last_username' => $lastUsername, 'error' => $error));
    }
    
    public function loginCheckAction()
    {
        
    }
    
    public function ingresocandidatoAction(Request $request)
    {
        $datosIngreso = array('mensaje' => 'Complete los datos de Ingreso.');
        $form = $this->createFormBuilder($datosIngreso)
            ->add('tipoDocumento', ChoiceType::class, array(
            'choices' => array('DNI' => 1, 'LE' => 2, 'LC' => 3, 'PP' => 4),
            'choices_as_values' => true,
            'placeholder' => "Tipo de Documento"))
            ->add('nroDocumento', IntegerType::Class)
            ->add('clave', PasswordType::Class)
            ->add('ingresar', SubmitType::Class)
            ->getForm();

        $form->handleRequest($request);
        
        if ($form->isValid())
        {
            $data = $form->getData();
            
            $repositorio = $this->getDoctrine()->getRepository('ichTestBundle:Candidato');
            
            $candidato = $repositorio->findOneBy(array('nroDocumento' => $data['nroDocumento'], 'tipoDocumento' => $data['tipoDocumento']));
            
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
              
              return $this->redirectToRoute( 'ich_evaluacion_verificarEstadoCuestionario', array (
                    'id' => $cuestionario->getId(), 'esUltimoBloque' => 0
            ) );  

            } else
            {
                print_r('Datos no Coherentes');
            }
            
        }
        
        return $this->render('ichTestBundle:Seguridad:ingresocandidato.html.twig', array('form' => $form->createView()));
    }
}