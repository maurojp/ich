<?php

namespace ich\TestBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PuestoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codigo', null, array('label' => 'Código'))
            ->add('nombre', null, array('label' => 'Nombre'))
            ->add('descripcion', null, array('label' => 'Descripción'))
            ->add('empresa', 'entity', array(
                'class' => 'ichTestBundle:Empresa',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u');
                },
                'choice_label' => 'getNombre'
            ))
            ->add('competencias', CollectionType::class, array(
                'entry_type' => PuestoCompetenciaType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'cascade_validation' => true,
                'required' => false
            ))
            ->add('save', 'submit', array('label' => 'Guardar'))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ich\TestBundle\Entity\Puesto',
            'cascade_validation' => true
        ));
    }
}
