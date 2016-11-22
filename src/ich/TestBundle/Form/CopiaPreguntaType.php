<?php

namespace ich\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use ich\TestBundle\Form\CopiaOpcionRespuestaType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\EntityRepository;

class CopiaPreguntaType extends AbstractType {
	/**
	 *
	 * @param FormBuilderInterface $builder        	
	 * @param array $options        	
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {
		$builder->add ( 'id', 'hidden', array () )
		->add ( 'pregunta', 'text', array () );
	
	

	$formModifier = function (FormInterface $form, $idCopiaPregunta) {

			$form->add ( 'copiaOpcionesRespuesta', 'entity', array (
					'class' => 'ichTestBundle:CopiaOpcionRespuesta',
					'query_builder' => function(EntityRepository $er ) use ( $idCopiaPregunta ) {
        return $er->createQueryBuilder('opt')
                  ->orderBy('opt.ordenEvaluacion', 'ASC')
                  ->where('identity(opt.copiaPregunta) = ?1')
                  ->setParameter(1, $idCopiaPregunta);
                                    },
					'choice_label' => 'getDescripcion',
                	'expanded' => true, 
			) );
		};
		
		$builder->addEventListener ( FormEvents::PRE_SET_DATA, function ($event) use ($formModifier) {
			
			$data = $event->getData ();
			if (null === $data) {
				return;
			}
			
			$formModifier ( $event->getForm (), $data['id'] );
		} );


		$builder->get ( 'id' )->addEventListener ( FormEvents::POST_SUBMIT, function ($event) use ($formModifier) {
			
			
			$formModifier ( $event->getForm ()->getParent (), $event->getForm ()->getData () );
		} );

		}



}

