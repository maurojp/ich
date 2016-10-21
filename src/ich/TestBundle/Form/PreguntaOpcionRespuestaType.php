<?php

namespace ich\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreguntaOpcionRespuestaType extends AbstractType {
	/**
	 *
	 * @param FormBuilderInterface $builder        	
	 * @param array $options        	
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add ( 'opcionRespuesta', 'entity', array (
				'class' => 'ichTestBundle:OpcionRespuesta' 
		)
		 )->add ( 'descripcion', 'text', array (
				'mapped' => false,
				'required' => false 
		) )->add ( 'ordenEvaluacion', 'text', array (
				'mapped' => false,
				'required' => false 
		) )->add ( 'ponderacion', null, array (
				'label' => 'Ponderación',
				'required' => true
		) );
	}
	
	/**
	 *
	 * @param OptionsResolver $resolver        	
	 */
	public function configureOptions(OptionsResolver $resolver) {
		$resolver->setDefaults ( array(
            'data_class' => 'ich\TestBundle\Entity\Pregunta_OpcionRespuesta'
        ));
    }
}

