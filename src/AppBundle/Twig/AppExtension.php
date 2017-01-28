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
            new \Twig_SimpleFilter('fingeringStringLoop', array($this, 'fingeringStringLoopFilter')),
            new \Twig_SimpleFilter('infoToneToWs', array($this, 'infoToneToWsFilter')),
            new \Twig_SimpleFilter('XYtoJSON', array($this, 'XYtoJSONFilter')),

        );
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('sortFingerings', array($this, 'sortFingeringsFunction')),
            new \Twig_SimpleFunction('sortByScoreAndRoman', array($this, 'sortByScoreAndRomanFunction')),
            new \Twig_SimpleFunction('sortByRoot', array($this, 'sortByRootFunction')),
            new \Twig_SimpleFunction('sortByScore', array($this, 'sortByScoreFunction')),
        );
    }

    public function sortFingeringsFunction($fingerings)
    {
/*
 * "fId" => "44"
    "minX" => "9"
    "minY" => "2"
    "xList" => "9,12,9,10"
    "yList" => "2,3,4,5"
    "digitAList" => "47,55,56,62"
    "intervaleList" => "5,#9,3,b7"
    "intervaleColorList" => "555555,555588,548B54,E8A317"
    "wsNameList" => "B,F##,G#,D"
 */
        $xListed = array();
        $yListed = array();
        $wListed = array();


        foreach($fingerings as $f){

            $f['json'] = json_encode($f);

            if(isset($f['wsIdCycle'])){

                if(!isset($wListed[$f['wsNameCycle']])){
                    $wListed[$f['wsNameCycle']] = array();
                }

                array_push($wListed[$f['wsNameCycle']],$f);
            }

            if(isset($f['minXnoOs'])){
                if($f['minXnoOs']>0){
                $f['minX']=$f['minXnoOs'];
                }
            }

            if(!isset($xListed[$f['minX']])){
                $xListed[$f['minX']] = array();
            }
            if(!isset($yListed[$f['minY']])){
                $yListed[$f['minY']] = array();
            }


            array_push($xListed[$f['minX']],$f);
            array_push($yListed[$f['minY']],$f);


        }
        ksort($xListed);
        ksort($yListed);
        return array("count"=>count($fingerings),"wListed"=>$wListed,"xListed"=>$xListed,"yListed"=>$yListed);
    }

    public function sortByScoreAndRomanFunction($rootScale)
    {

        $o = array();
        $i = array();
        foreach($rootScale as $v){
            if(!isset($o[$v["score"]])){
                $o[$v["score"]] = array();
            }
            if(!isset($o[$v["score"]][$v["iRootRoman"]])){
                $o[$v["score"]][$v["iRootRoman"]] = array();
            }
            if(!in_array($v["iRootRoman"],$i)){
                array_push(
                    $i,
                    $v["iRootRoman"]
                );
            }


            array_push(
                $o[$v["score"]][$v["iRootRoman"]],
                $v
            );
        }
        return array("datas"=>$o,"romans"=>$i);
    }

    public function sortByScoreFunction($rootScale)
    {
        $o = array();
        foreach($rootScale as $v){
            if(!isset($o[$v["score"]])){
                $o[$v["score"]] = array();
            }
            array_push($o[$v["score"]],$v);
        }
        return $o;
    }

    public function sortByRootFunction($rootScale)
    {
        $o = array();
        foreach($rootScale as $v){
            if(!isset($o[$v["rootName"]])){
                $o[$v["rootName"]] = array();
            }
            array_push($o[$v["rootName"]],$v);
        }
        return $o;
    }
    public function infoToneToWsFilter($f)
    {
        if(strstr($f,'/')){
           $o =  explode("/",$f);
            return $o[1];
        }

        return $f;
    }

    public function fingeringStringLoopFilter($f)
    {
       $o = array();
        foreach($f as $v){
            if(!isset($o[$v["stringLoop"]]))
                $o[$v["stringLoop"]]  = array();

            $v['JSON'] = json_encode($v);
           array_push($o[$v["stringLoop"]] , $v) ;
        }

        return $o;
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