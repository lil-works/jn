<?php
namespace SiteBundle\Controller;


use AppBundle\Entity\Intervale;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Scale;
use AppBundle\Entity\WesternSystem;
use AppBundle\Entity\Instrument;


class RootScaleController extends Controller
{
    /**
     * Blablabla
     * @ParamConverter("westernSystem", class="AppBundle\Entity\WesternSystem",options={"mapping": { "rootId":"id"  }})
     * @ParamConverter("scale", class="AppBundle\Entity\Scale",options={"mapping": {"scaleId": "id"  }})
     * @ParamConverter("instrument", class="AppBundle\Entity\Instrument",options={"mapping": {"instrumentId": "id"  }})
     *   @ParamConverter("rootScale", class="AppBundle:Scale", converter="rootScale_converter")
     */
    public function indexAction(Request $request, Scale $scale,WesternSystem $westernSystem, Instrument $instrument,$addRoot=null)
    {
        $em = $this->getDoctrine()->getManager();
        $westernSystem = $em->getRepository('AppBundle:WesternSystem')->findOneByRootName($westernSystem->getName());

        if(!is_null($addRoot)){
            $addRoot = $em->getRepository('AppBundle:WesternSystem')->findOneByRootName($addRoot);

            return $this->render('SiteBundle:RootScale:index-addroot.html.twig',array(
                "westernSystem"=>$westernSystem,
                "addRoot"=>$addRoot,
                "scale"=>$scale,
            ));
        }else{



            $sequencerService = $this->get('app.sequencer');
            $sequencerService->addRootScale($westernSystem,$scale);

            $scaleRootService = $this->get('app.scaleRoot')->init($scale,$westernSystem,true)->getScaleRoot($instrument);
            $fingeringService = $this->get('app.fingering')->init($scale,$westernSystem)->getFingerings($instrument,true);

            $intervales = $em->getRepository('AppBundle:Intervale')->findAll() ;
            $roots = $em->getRepository('AppBundle:WesternSystem')->findByIntervale(1);

            $populatedScale = $em->getRepository('AppBundle:Scale')->westernPopulateRootScale($scale->getId(),$westernSystem->getName()) ;

            $seoPage = $this->container->get('sonata.seo.page');
            $seoPage
                ->setTitle($seoPage->getTitle() . " • ".$westernSystem->getName().$scale->getName())
                ->addMeta('name', 'description', "details for ".$westernSystem->getName().$scale->getName())
            ;



            return $this->render('SiteBundle:RootScale:index.html.twig',array(
                "fingeringService"=>$fingeringService,
                "scaleRootService"=>$scaleRootService,
                "sequencerService"=>$sequencerService->getView(),
                "scale"=>$scale,
                "westernSystem"=>$westernSystem,
                "populatedScale"=>$populatedScale,
                "instrument"=>$instrument,
                "roots"=>$roots,
                "intervales"=>$intervales
            ));
        }
    }

    /**
     * Blablabla
     * @ParamConverter("westernSystem", class="AppBundle\Entity\WesternSystem",options={"mapping": {"rootId": "id"  }})
     * @ParamConverter("scale", class="AppBundle\Entity\Scale",options={"mapping": {"scaleId": "id"  }})
     * @ParamConverter("instrument", class="AppBundle\Entity\Instrument",options={"mapping": {"instrumentId": "id"  }})
     * @ParamConverter("intervale", class="AppBundle\Entity\Intervale",options={"mapping": {"intervaleRoman": "roman"  }})
     */
    public function cycleAction(Request $request, Scale $scale,WesternSystem $westernSystem,Intervale $intervale, Instrument $instrument,$omit=null)
    {

        $em = $this->getDoctrine()->getManager();
        $westernSystem = $em->getRepository('AppBundle:WesternSystem')->findOneByRootName($westernSystem->getName());

        $scaleRootService = $this->get('app.scaleRoot')->init($scale,$westernSystem,true)->getScaleRootSmall($instrument);
        $fingeringService = $this->get('app.fingering')->init($scale,$westernSystem,$intervale)->getFingerings($instrument,true);
        $roots = $em->getRepository('AppBundle:WesternSystem')->findByIntervale(1);
        $populatedScale = $em->getRepository('AppBundle:Scale')->westernPopulateRootScale($scale->getId(),$westernSystem->getName()) ;
        $intervales = $em->getRepository('AppBundle:Intervale')->findAll() ;

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->setTitle($seoPage->getTitle() . " • ".$westernSystem->getName().$scale->getName())
            ->addMeta('name', 'description', "details for ".$westernSystem->getName().$scale->getName())
        ;



        return $this->render('SiteBundle:RootScale:cycle.html.twig',array(
            "intervale"=>$intervale,
            "intervales"=>$intervales,
            "scaleRootService"=>$scaleRootService,
            "fingeringService"=>$fingeringService,
            "scale"=>$scale,
            "westernSystem"=>$westernSystem,
            "populatedScale"=>$populatedScale,
            "instrument"=>$instrument,
            "roots"=>$roots
        ));
    }
    /**
     * Blablabla
     * @ParamConverter("instrument", class="AppBundle\Entity\Instrument",options={"mapping": {"instrumentId": "id"  }})
     */
    public function againstAction(Request $request,  Instrument $instrument,$rootScaleList)
    {

        $em = $this->getDoctrine()->getManager();

        $fingeringService = $this->get('app.fingering')->getFingeringsAgainst($instrument,$rootScaleList);
        $sequencerService = $this->get('app.sequencer')->getView();
        $roots = $em->getRepository('AppBundle:WesternSystem')->findByIntervale(1);

        $intervales = $em->getRepository('AppBundle:Intervale')->findAll() ;

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->setTitle($seoPage->getTitle() )
            ->addMeta('name', 'description', "Against")
        ;


        return $this->render('SiteBundle:RootScale:against.html.twig',array(
            "intervales"=>$intervales,
            "fingeringService"=>$fingeringService,
            "sequencerService"=>$sequencerService,


            "instrument"=>$instrument,
            "roots"=>$roots
        ));
    }
    /**
     * Blablabla
     * @ParamConverter("westernSystem", class="AppBundle\Entity\WesternSystem",options={"mapping": {"root": "name"  }})
     * @ParamConverter("scale", class="AppBundle\Entity\Scale",options={"mapping": {"scale": "name"  }})
     * @ParamConverter("instrument", class="AppBundle\Entity\Instrument",options={"mapping": {"instrumentId": "id"  }})
     */
    public function omitAction(Request $request, Scale $scale,WesternSystem $westernSystem, Instrument $instrument,$omit=null)
    {
        $em = $this->getDoctrine()->getManager();



        $matrice = $em->getRepository('AppBundle:Instrument')->getMatrice($instrument->getId());
        $roots = $em->getRepository('AppBundle:WesternSystem')->findByIntervale(1);

        $populatedScale = $em->getRepository('AppBundle:Scale')->westernPopulateRootScale($scale->getId(),$westernSystem->getName()) ;
        $intervales = array();
        foreach($scale->getIntervales() as $intervale){
            array_push($intervales,$intervale->getId());
        }
        $matchingScales = $em->getRepository('AppBundle:Scale')->matchingRootScale($scale,$westernSystem) ;
        $fingerings = $em->getRepository('AppBundle:Fingering')->findFingeringByRootAndScale($instrument,$scale,$westernSystem) ;

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->setTitle($seoPage->getTitle() . " • ".$westernSystem->getName().$scale->getName())
            ->addMeta('name', 'description', "details for ".$westernSystem->getName().$scale->getName())
        ;

        $session = $request->getSession();


        $arrayOfFingeringJSON = array();
        foreach($fingerings as $fingering){
            array_push($arrayOfFingeringJSON,json_encode($fingering));
        }
        return $this->render('SiteBundle:RootScale:omit.html.twig',array(
            "scale"=>$scale,
            "westernSystem"=>$westernSystem,
            "populatedScale"=>$populatedScale,
            "matchingScales"=>$matchingScales,
            "fingeringsJSON"=>json_encode($fingerings),
            "instrumentJSON"=>json_encode($matrice),
            "fingerings"=>$fingerings,
            "arrayOfFingeringJSON"=>$arrayOfFingeringJSON,
            "instrument"=>$instrument,
            "roots"=>$roots
        ));
    }
}
