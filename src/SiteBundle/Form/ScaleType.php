<?php

namespace SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ScaleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('intervales', EntityType::class, array(
                'class'    => 'AppBundle:Intervale' ,
                'choice_label' => function ($obj) { return   $obj->getName() ; },
                'required' => true ,
                'mapped'=> true,
                'expanded' => true ,
                'multiple' => true
            ))
            ->add('descriptors', EntityType::class, array(
                'class'    => 'AppBundle:Descriptor' ,
                'choice_label' => function ($obj) { return   $obj->getName() ; },
                'required' => false ,
                'mapped'=> true,
                'expanded' => true ,
                'multiple' => true
            ))
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Scale'
        ));
    }
}
