<?php

namespace ich\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use ich\TestBundle\Form\CopiaOpcionRespuestaType;

class CopiaPreguntaType extends AbstractType {
	/**
	 *
	 * @param FormBuilderInterface $builder        	
	 * @param array $options        	
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add ( 'pregunta', 'text', array () )
		->add ( 'copiaOpcionesRespuesta',  CollectionType::class, array (
				'entry_type' => CopiaOpcionRespuestaType::class,
				'by_reference' => false,
		) );
	}
	

}

