<?php

namespace AppBundle\Form\Type;

use AppBundle\Entity\BreakdownsInterferos;
use AppBundle\Entity\InterferoRepository;
use AppBundle\Form\Type\OnoffType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;


class FingersType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {

        });

        $builder
            ->add('x',null,array('mapped'=>true))
            ->add('y',null,array('mapped'=>true))
            ->add('lh',null,array('mapped'=>true))
            ->add('rh',null,array('mapped'=>true))
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\FingeringFinger'
        ));
    }
}
