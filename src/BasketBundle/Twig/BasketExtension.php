<?php
namespace BasketBundle\Twig;

class BasketExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('fingeringOffline', array($this, 'fingeringOfflineFunction')),
            new \Twig_SimpleFunction('instrumentToFretboard', array($this, 'instrumentToFretboardFunction')),
        );
    }

    public function fingeringOfflineFunction($yxs)
    {


        $xList = $yList = $intervaleList = $intervaleColorList = $wsNameList = $digitAList =array();
        foreach($yxs as $k=>$yx){
            array_push($xList,$yx->getX());
            array_push($yList,$yx->getY());
            array_push($intervaleList,$yx->getIntervale()->getName());
            array_push($intervaleColorList,$yx->getIntervale()->getColor());
            array_push($wsNameList,$yx->getWesternSystem()->getName());
            array_push($digitAList,$yx->getDigitA());
        }

        return json_encode(array(
            "xList"=>implode(",",$xList),
            "yList"=>implode(",",$yList),
            "intervaleList"=>implode(",",$intervaleList),
            "intervaleColorList"=>implode(",",$intervaleColorList),
            "wsNameList"=>implode(",",$wsNameList),
            "digitAList"=>implode(",",$digitAList)
        ));
    }
    public function instrumentToFretboardFunction($strings)
    {
        $o = array();
        foreach($strings as $s){
            array_push($o,$s->getDigit()->getInfoTone().$s->getOctave());
        }

        return json_encode($o);
    }
    public function getName()
    {
        return 'basket_extension';
    }
}