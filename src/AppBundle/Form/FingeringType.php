<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class FingeringType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('description')
            ->add('difficulty')
            ->add('fingers', EntityType::class, array(
                'class'    => 'AppBundle:FingeringFinger' ,
                'choice_label' => function ($obj) { return   "y:".$obj->getY()." x:".$obj->getX(); },
                'required' => true ,
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
            'data_class' => 'AppBundle\Entity\Fingering'
        ));
    }
}
