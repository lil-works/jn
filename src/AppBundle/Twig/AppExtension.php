<?php
namespace AppBundle\Twig;

class AppExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('neck', array($this, 'neckFilter')),
            new \Twig_SimpleFilter('scale', array($this, 'scaleFilter')),
            new \Twig_SimpleFilter('root', array($this, 'rootFilter')),
        );
    }


    public function neckFilter($neck, $caseMin = 0, $caseMax = 24,$strings=array())
    {
        $o = array();
        foreach($neck as $n){
            $min = min($n);
            $max = max($n);
            if($min<$caseMin){
                break;
            }
            if($max<$caseMax){
                break;
            }
            array_push($o,$n);
        }
        return $o;
    }
    public function scaleFilter($scale,$descriptorAllowed=array())
    {

        return $scale;
    }
    public function rootFilter($ws)
    {
        $o = array();
        foreach($ws as $w)
            $o[$w->getRelativePosition()] = $w;

        ksort($o);

        return $o;
    }
    public function getName()
    {
        return 'app_extension';
    }
}