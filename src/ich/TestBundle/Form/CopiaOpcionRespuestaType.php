<?php

namespace ich\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CopiaOpcionRespuestaType extends AbstractType {
	/**
	 *
	 * @param FormBuilderInterface $builder        	
	 * @param array $options        	
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add ( 'id', 'number', array () )
		->add ( 'descripcion', 'text', array () )
		->add ( 'seleccionada', 'checkbox', array () );
	}
	

}

