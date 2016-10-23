<?php

namespace AppBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class InstrumentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('caseMax')
            ->add('color')
            ->add('icon', FileType::class, array('label' => 'icon' , 'data_class' => null,'required'=>false))
            ->add('strings', EntityType::class, array(
                'class'    => 'AppBundle:InstrumentString' ,
                'choice_label' => function ($obj) { return   "y:".$obj->getPos()." tone:".$obj->getDigit()->getInfoTone()." octave:".$obj->getOctave() ; },
                'required' => true ,
                'mapped'=> true,
                'expanded' => false ,
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
            'data_class' => 'AppBundle\Entity\Instrument'
        ));
    }

}
