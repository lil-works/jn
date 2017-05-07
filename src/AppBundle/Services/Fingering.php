<?php


namespace AppBundle\Services;



use AppBundle\Entity\WesternSystem;
use AppBundle\Entity\Scale;
use AppBundle\Entity\Intervale;
use Symfony\Component\Form\Extension\Templating\TemplatingExtension;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Fingering implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected $em;
    protected $scale;
    protected $westernSystem;
    protected $intervale;
    protected $templating;


    public function __construct(\Doctrine\ORM\EntityManager $em ,  $templating , ContainerInterface $container )
    {
        $this->em = $em;
        $this->templating = $templating;
        $this->container = $container;

    }
    public function init(Scale $scale, WesternSystem $westernSystem ,Intervale $intervale = null)
    {
        $this->scale = $scale;
        $this->westernSystem = $westernSystem;
        $this->intervale = $intervale;
        return $this;
    }

    public function getRootScale(){
        return $this->westernSystem->getName() . " " . $this->scale->getName();
    }

    private function calculateCycling(){
        $o = array();
        $aIntervales = array();
        $delta = $this->intervale->getDelta();

        $step = 12;
        for($i=1;$i<$step;$i++ ){
            $realDelta = ($delta*$i)%12;
            if($realDelta == 0)
                break;
            $intervale = $this->em->getRepository('AppBundle:Intervale')->findOneByDelta(
                $realDelta
            );
            array_push($aIntervales,$intervale);
        }

        array_push($o,array("wId"=>$this->westernSystem->getId(),"sId"=>$this->scale->getId()));
        foreach($aIntervales as $intervale){


            $forNewRootName = $this->em->getRepository('AppBundle:WesternSystem')->findOneInFamilyRootByIntervale(
                array('root'=>$this->westernSystem->getId() , 'intervale'=>$intervale->getId()));

            $newWs = $this->em->getRepository('AppBundle:WesternSystem')->findOneByRootNameAndIntervale(
                array('rootName'=>$forNewRootName->getName() , 'iId'=>1));

            array_push($o,array("wId"=>$newWs->getId(),"sId"=>$this->scale->getId()));

        }


        return $o;
    }
    private function calculateAgainst($list){
        $o = array();
        foreach(explode(',',$list) as $rs){
            $rsE = explode('_',$rs);
            array_push($o,array("wId"=>$rsE[0],"sId"=>$rsE[1]));
        }
        return $o;
    }
    public function getFingeringsAgainst($instrument,$list){
        $fingerings = $this->em->getRepository('AppBundle:Fingering')->findFingeringByRootAndScaleCycle($instrument,$this->calculateAgainst($list));
        $arrayOfFingeringJSON = array();

        foreach($fingerings as $fingering){
            array_push($arrayOfFingeringJSON,json_encode($fingering));
        }

        $instrumentForJs = array();
        $instrumentStringsForJs = $this->em->getRepository('AppBundle:InstrumentString')->findByInstrument($instrument);
        foreach($instrumentStringsForJs as $instrumentStringForJs){
            array_push($instrumentForJs,$instrumentStringForJs->getDigit()->getInfoTone().$instrumentStringForJs->getOctave());
        }


        $view = "Services/fingering-against.html.twig";
        $neckTemplate = $this->templating->render('Services/neck.html.twig', array(
            "instrument"=>$instrument,
            "fingerings"=>$fingerings,
            "arrayOfFingeringJSON"=>$arrayOfFingeringJSON,
            "instrumentForJs"=>json_encode($instrumentForJs),
            "fingeringsJSON"=>json_encode($fingerings),
            "drawNeck"=>true,
        ));
        $fingeringTemplate = $this->templating->render($view, array(
            "instrument"=>$instrument,
            "fingerings"=>$fingerings,
            "arrayOfFingeringJSON"=>$arrayOfFingeringJSON,
            "instrumentForJs"=>json_encode($instrumentForJs),
            "fingeringsJSON"=>json_encode($fingerings),
            "drawNeck"=>true,
        ));

        return array("neck"=>$neckTemplate,"fingering"=>$fingeringTemplate);
    }
    public function getFingerings($instrument,$drawNeck=true,$neckName = null){

        if(is_null($this->intervale) || $this->intervale->getDelta()%12 == 0){
            $fingerings = $this->em->getRepository('AppBundle:Fingering')->findFingeringByRootAndScale($instrument,$this->scale,$this->westernSystem);
            $view = "Services/fingering.html.twig";
        }else{
            $fingerings = $this->em->getRepository('AppBundle:Fingering')->findFingeringByRootAndScaleCycle($instrument,$this->calculateCycling());
            $view = "Services/fingering-cycle.html.twig";
        }



            $arrayOfFingeringJSON = array();
            foreach($fingerings as $fingering){
                array_push($arrayOfFingeringJSON,json_encode($fingering));
            }

            $instrumentForJs = array();
            $instrumentStringsForJs = $this->em->getRepository('AppBundle:InstrumentString')->findByInstrument($instrument);
            foreach($instrumentStringsForJs as $instrumentStringForJs){
                array_push($instrumentForJs,$instrumentStringForJs->getDigit()->getInfoTone().$instrumentStringForJs->getOctave());
            }

        $neckTemplate = $this->templating->render('Services/neck.html.twig', array(
            "instrument"=>$instrument,
            "westernSystem"=>$this->westernSystem,
            "scale"=>$this->scale,
            "fingerings"=>$fingerings,
            "arrayOfFingeringJSON"=>$arrayOfFingeringJSON,
            "instrumentForJs"=>json_encode($instrumentForJs),
            "fingeringsJSON"=>json_encode($fingerings),
            "drawNeck"=>$drawNeck,
            "myNeck"=>$neckName
        ));
        $fingeringTemplate = $this->templating->render($view, array(
            "instrument"=>$instrument,
            "westernSystem"=>$this->westernSystem,
            "scale"=>$this->scale,
            "fingerings"=>$fingerings,
            "arrayOfFingeringJSON"=>$arrayOfFingeringJSON,
            "instrumentForJs"=>json_encode($instrumentForJs),
            "fingeringsJSON"=>json_encode($fingerings),
            "drawNeck"=>$drawNeck,
            "myNeck"=>$neckName
        ));

        return array("neck"=>$neckTemplate,"fingering"=>$fingeringTemplate);

    }

}