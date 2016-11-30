<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class StringCaseType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $a = array();
        for($s=0;$s<6;$s++){
            for($c=0;$c<8;$c++){
                $a[$s."_".$c] = $s."_".$c;
            }
        }

        $resolver->setDefaults(array(
            'choices' => $a
        ));
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}