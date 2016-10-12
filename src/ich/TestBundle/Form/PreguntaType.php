<?php

namespace ich\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvents;

class PreguntaType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('codigo', null, array('label' => 'CÃ³digo'))
        ->add('nombre', null, array('label' => 'Nombre'))
        ->add('pregunta', null, array('label' => 'Pregunta'))
        ->add('descripcion', null, array('label' => 'DescripciÃ³n'))
        ->add('save', 'submit', array('label' => 'Guardar'))
        ->add('competencia', EntityType::class, array(
                'class'       => 'ichTestBundle:Competencia',
                'placeholder' => 'Competencia',
                'choice_label' => 'getNombre',
                'mapped' => false,
            ));
        

        $formModifier = function (FormInterface $form, $competencia = null) {
            $factores = null === $competencia ? array() : $competencia->getFactores();

            $form->add('factor', EntityType::class, array(
                'class'       => 'ichTestBundle:Factor',
                'placeholder' => 'Factor',
                'choices'     => $factores,
            ));
        };
        
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function ( $event) use ($formModifier) {
                // this would be your entity, i.e. SportMeetup
                $data = $event->getData();
                if (null === $data) {
                    return;
                }
                $formModifier($event->getForm(), $data->getFactor());
            }
        );

        $builder->get('competencia')->addEventListener(
            FormEvents::POST_SUBMIT,
            function ( $event) use ($formModifier) {
                // It's important here to fetch $event->getForm()->getData(), as
                // $event->getData() will get you the client data (that is, the ID)
                $competencia = $event->getForm()->getData();

                // since we've added the listener to the child, we'll have to pass on
                // the parent to the callback functions!
                $formModifier($event->getForm()->getParent(), $competencia);
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
