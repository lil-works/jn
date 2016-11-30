<?php

namespace SiteBundle\Form;


use AppBundle\Entity\FingeringFinger;
use AppBundle\Form\Type\FingeringFingerType;
use AppBundle\MyClass\FingeringFormater;
use SiteBundle\Form\Type\LandingToneType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class MelodicLineType extends AbstractType
{


    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder
            ->add('description')
            ->add('difficulty',DifficultyType::class)
            ->add('arpeggio')
            ->add('landingTone', CollectionType::class, array(
                'mapped'=>false,
                'allow_add'=>true,
                'required' => true,
                'entry_type'   => LandingToneType::class,
            ))

        ;



    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\MelodicLine'
        ));
    }
}
