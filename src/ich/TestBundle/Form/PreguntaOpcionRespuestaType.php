<?php

namespace ich\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\TextType;
use Doctrine\ORM\EntityRepository;

class PreguntaOpcionRespuestaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('opcionRespuesta', 'entity', array(
                'class' => 'ichTestBundle:OpcionRespuesta',
                'read_only' => true,
                'choice_label' => 'getDescripcion'
            ))
            ->add('ponderacion', null, array('label' => 'Ponderación'));
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ich\TestBundle\Entity\Pregunta_OpcionRespuesta'
        ));
    }
}

