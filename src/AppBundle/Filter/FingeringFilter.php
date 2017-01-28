<?php
namespace AppBundle\Filter;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type as Filters;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;




class FingeringFilter extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('difficulty', RangeType::class, array(
            'required' => false ,
            'mapped'=> false
        ))->add('string', RangeType::class, array(
            'required' => false ,
            'mapped'=> false
        ))->add('fret', RangeType::class, array(
            'required' => false ,
            'mapped'=> false
        ))->add('arpeggio', ChoiceType::class, array(
            'choices'  => array(
                'Maybe' => null,
                'Yes' => true,
                'No' => false,
            ),
            'required' => false ,
            'mapped'=> false
        ))->add('openstring', ChoiceType::class, array(
            'choices'  => array(
                'Maybe' => null,
                'Yes' => true,
                'No' => false,
            ),
            'required' => false ,
            'mapped'=> true
        ))

        ;
    }

    public function getBlockPrefix()
    {
        return 'fingering_filter';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection'   => false,
            'validation_groups' => array('filtering') // avoid NotBlank() constraint-related message
        ));
    }
}