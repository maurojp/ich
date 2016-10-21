<?php

namespace ich\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use ich\TestBundle\Form\PreguntaOpcionRespuestaType;
use Doctrine\ORM\EntityRepository;

class PreguntaType extends AbstractType {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add ( 'codigo', null, array (
				'label' => 'Código',
				'required' => true
		) )->add ( 'pregunta', null, array (
				'label' => 'Pregunta',
				'required' => true
		) )->add ( 'descripcion', null, array (
				'label' => 'Descripción' 
		) )->add ( 'competencia', EntityType::class, array (
				'class' => 'ichTestBundle:Competencia',
				'placeholder' => 'Seleccione',
				'mapped' => false,
				'required' => true
		) )->add ( 'grupoOpciones', EntityType::class, array (
				'class' => 'ichTestBundle:GrupoOpciones',
				'placeholder' => 'Seleccione',
				'label' => 'Grupo de Opciones',
				'property' => 'grupoOpciones',
				'required' => true,
				'query_builder' => function (EntityRepository $er) {
					return $er->createQueryBuilder ( 'u' );
				},
				'required' => true 
		) )->add ( 'opcionesRespuesta', CollectionType::class, array (
				'entry_type' => PreguntaOpcionRespuestaType::class,
				'by_reference' => false,
				'allow_add' => true,
				'allow_delete' => true,
				'label' => 'Opciones de Respuesta',
				'cascade_validation' => true,
				'required' => true,
				'attr' => array (
						'class' => 'row opcionesRespuesta' 
				) 
		) )->add ( 'save', 'submit', array () );
		
		$formModifier = function (FormInterface $form, $competencia = null) {
			$factores = null === $competencia ? array () : $competencia->getFactores ();
			
			$form->add ( 'factor', EntityType::class, array (
					'class' => 'ichTestBundle:Factor',
					'placeholder' => 'Seleccione',
					'choices' => $factores,
					'required' => true 
			) );
		};
		
		$builder->addEventListener ( FormEvents::PRE_SET_DATA, function ($event) use ($formModifier) {
			
			$data = $event->getData ();
			if (null === $data) {
				return;
			}
			
			if (null === $data->getFactor ()) {
				$formModifier ( $event->getForm (), null );
				return;
			}
			
			$formModifier ( $event->getForm (), $data->getFactor ()->getCompetencia () );
		} );
		
		$builder->get ( 'competencia' )->addEventListener ( FormEvents::POST_SUBMIT, function ($event) use ($formModifier) {
			
			$competencia = $event->getForm ()->getData ();
			
			$formModifier ( $event->getForm ()->getParent (), $competencia );
		} );
        
       
        

        
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
            'data_class' => 'ich\TestBundle\Entity\Pregunta',
        		'cascade_validation' => true
        ));
    }}
