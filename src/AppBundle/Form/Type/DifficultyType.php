<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DifficultyType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => array(
                'really easy' => 0,
                'easy' => 1,
                'regular' => 2,
                'difficult' => 3,
                'tricky' => 4
            )
        ));
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}