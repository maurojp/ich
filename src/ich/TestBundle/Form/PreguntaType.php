<?php

namespace ich\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvents;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\CallbackTransformer;
use Doctrine\ORM\PersistentCollection;

class PreguntaType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('codigo', null, array('label' => 'Código'))
        ->add('pregunta', null, array('label' => 'Pregunta'))
        ->add('descripcion', null, array('label' => 'Descripción'))
        ->add('save', 'submit', array('label' => 'Guardar'))
        ->add('competencia', EntityType::class, array(
                'class'       => 'ichTestBundle:Competencia',
                'placeholder' => 'Seleccione',
                'mapped' => false,
            ))
        ->add('grupoOpciones', EntityType::class, array(
                'class'       => 'ichTestBundle:GrupoOpciones',
                'placeholder' => 'Seleccione'
            ));
        

        $formModifier = function (FormInterface $form, $competencia = null) {
            $factores = null === $competencia ? array() : $competencia->getFactores();

            $form->add('factor', EntityType::class, array(
                'class'       => 'ichTestBundle:Factor',
                'placeholder' => 'Seleccione',
                'choices'     => $factores,
            ));
        };
        
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function ( $event) use ($formModifier) {

                $data = $event->getData();
                if (null === $data) {
                    return;
                }
               
                $formModifier($event->getForm(), $data->getFactor()->getCompetencia());
            }
        );

        $builder->get('competencia')->addEventListener(
            FormEvents::POST_SUBMIT,
            function ( $event) use ($formModifier) {

                $competencia = $event->getForm()->getData();

                $formModifier($event->getForm()->getParent(), $competencia);
            }
        );
        
        
        
        
        $formModifier2 = function (FormInterface $form, $GrupoOpciones) {
        	$opcionesRespuesta = null === $GrupoOpciones ? array() : $GrupoOpciones->getOpcionesRespuesta();
     	
        	$form->add('opcionesRespuesta', CollectionType::class, array(
            		'entry_type'     => PreguntaOpcionRespuestaType::class,
            		'by_reference'   => false,
            		'allow_add'      => true,
        			'prototype'      => true,
        			'label' => 'Opciones de Respuesta',
        			'cascade_validation' => true
        			));};
        
        $builder->addEventListener(
        		FormEvents::PRE_SET_DATA,
        		function ( $event) use ($formModifier2) {
        
        			$data = $event->getData();
        			if (null === $data) {
        				return;
        			}
        			$formModifier2($event->getForm(), $data->getGrupoOpciones());
        		}
        		);
        
        $builder->get('grupoOpciones')->addEventListener(
        		FormEvents::POST_SUBMIT,
        		function ( $event) use ($formModifier2) {
        
        			$grupoOpciones = $event->getForm()->getData();
        
        			$formModifier2($event->getForm()->getParent(), $grupoOpciones);
        		}
        		);
        
       /* $builder->addEventListener(FormEvents::POST_SUBMIT, function ( $event) {
            $event->stopPropagation();
        }, 900);*/
        }
         
    
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ich\TestBundle\Entity\Pregunta'
        ));
    }}
