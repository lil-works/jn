<?php
namespace SiteBundle\Controller;

use AppBundle\Entity\Instrument;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
class DefaultController extends Controller
{

    public function indexAction()
    {

        $em = $this->getDoctrine()->getManager();
        $instrumentFamilies = $em->getRepository('AppBundle:InstrumentFamily')->findAll();


        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->setTitle($seoPage->getTitle() . " • Welcome")
            ->addMeta('name', 'description', "Choose your instrument and plot tones from scale or search scale selecting tones over the neck")
        ;

        return $this->render('SiteBundle:Default:index.html.twig',array(
            'instrumentFamilies'=>$instrumentFamilies
        ));
    }
    public function aboutAction()
    {
        return $this->render('SiteBundle:Default:about.html.twig',array(

        ));
    }


    public function neckAction(Request $request, $instrumentId)
    {

        $em = $this->getDoctrine()->getManager();
        if(! $instrument = $em->getRepository('AppBundle:Instrument')->find($instrumentId)){
            $instrument = new Instrument();
        }


        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->setTitle($seoPage->getTitle() . " • ".$instrument->getName()." neck")
            ->addMeta('name', 'description', "Choose your instrument and plot tones from scale or search scale selecting tones over the neck")
        ;

        return $this->render('SiteBundle:Default:neck.html.twig',array(
            "instrument"=>$instrument
        ));
    }

    public function searchScaleAction(Request $request, $instrumentId)
    {


        $em = $this->getDoctrine()->getManager();
        if(! $instrument = $em->getRepository('AppBundle:Instrument')->find($instrumentId)){
            $instrument = new Instrument();
        }

        $instrumentFamilies = $em->getRepository('AppBundle:InstrumentFamily')->findAll();


        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->setTitle($seoPage->getTitle() . " • Search fingering")
            ->addMeta('name', 'description', "select your instrument and scale and get corresponding fingering")
        ;


        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->setTitle($seoPage->getTitle() . " • ".$instrument->getName()." neck")
            ->addMeta('name', 'description', "Choose your instrument and plot tones from scale or search scale selecting tones over the neck")
        ;

        return $this->render('SiteBundle:Default:search.html.twig',array(
            "instrument"=>$instrument,
            "instrumentFamilies"=>$instrumentFamilies
        ));
    }
    public function rootScaleSearchInstrumentedResultsAction(Request $request, $instrumentId, $rootNameList,$scaleIdList){
        $services = $tabs = array();
        $em = $this->getDoctrine()->getManager();
        if(! $instrument = $em->getRepository('AppBundle:Instrument')->find($instrumentId)){
            $instrument = new Instrument();
        }
        $scaleIds = explode(",",$scaleIdList);
        foreach( explode(",",$rootNameList) as $k=>$rootName){
            $westernSystem = $em->getRepository('AppBundle:WesternSystem')->findOneByRootName($rootName);
            $scale = $em->getRepository('AppBundle:Scale')->find($scaleIds[$k]);




            $fingeringService = $this->get('app.fingering')->init($scale,$westernSystem)->getFingerings($instrument,true,"myNeck_".$westernSystem->getId()."_".$scale->getId());

            $intervales = $em->getRepository('AppBundle:Intervale')->findAll() ;
            $roots = $em->getRepository('AppBundle:WesternSystem')->findByIntervale(1);

            $populatedScale = $em->getRepository('AppBundle:Scale')->westernPopulateRootScale($scaleIds[$k],$westernSystem->getName()) ;
            array_push($services,$fingeringService);
            array_push($tabs,array("id"=>"tab_".$westernSystem->getId()."_".$scale->getId(),"name"=>$westernSystem->getName()." ".$scale->getName()));

        }

            return $this->render('SiteBundle:Default:searchResults.html.twig',array(
                'services'=>$services,
                'tabs'=>$tabs
        ));
    }

}
