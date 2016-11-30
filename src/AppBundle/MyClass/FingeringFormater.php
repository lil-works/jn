<?php

namespace AppBundle\MyClass;



class FingeringFormater
{
    public $formatedData = array() ;

    public function __construct($datas){




        $availableFinger = array();
        foreach($datas as $a){

            if(isset($a["played"]))
                array_push($availableFinger,$a);
        }



        $minX = $minY = 999;
        $haveZeroY = $haveOneX = false;

        $s = $c = $cno0 = $aK = array();
        foreach($availableFinger as $k=>$v){

            if($v["y"] == 0)
                $haveZeroY = true;

            if($v["x"] == 1)
                $haveOneX = true;



            if($v["x"] != 0)
                array_push($cno0,$v["x"]);

            array_push($s,$v["y"]);
            array_push($c,$v["x"]);
            array_push($aK,$k);

        }


        if($haveZeroY == false){
            $minY = min($s);
            foreach($s as $k=>$v){
                $s[$k] = $v - $minY;
            }
        }
        if($haveOneX == false){

            $minX = min($cno0);
            foreach($c as $k=>$v){
                if($v!=0){
                    $c[$k] = $v - $minX + 1 ;
                }
            }
        }


        $o = array();
        foreach($s as $k=>$v){
            array_push($o, array(
                "x"=>$c[$k],
                "y"=>$s[$k],
                "lh"=>$availableFinger[$aK[$k]]["lh"],
                "rh"=>$availableFinger[$aK[$k]]["rh"],
            ))    ;
        }


        $this->formatedData =  $o;
    }
}
