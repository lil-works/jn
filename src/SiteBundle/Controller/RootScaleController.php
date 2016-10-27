<?php
namespace SiteBundle\Controller;


use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Scale;
use AppBundle\Entity\WesternSystem;

class RootScaleController extends Controller
{
    /**
     * Finds and displays a Author entity.
     * @ParamConverter("westernSystem", class="AppBundle\Entity\WesternSystem",options={"mapping": {"root": "name"  }})
     * @ParamConverter("scale", class="AppBundle\Entity\Scale",options={"mapping": {"scale": "name"  }})
     */
    public function indexAction(Request $request,Scale $scale,WesternSystem $westernSystem)
    {
        $em = $this->getDoctrine()->getManager();



        $session = $request->getSession();
        if(!$session->get('neck/instrumentId'))
            $session->set('neck/instrumentId',1);

        $instrument = $em->getRepository('AppBundle:Instrument')->find($session->get('neck/instrumentId')) ;
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
        return $this->render('SiteBundle:RootScale:index.html.twig',array(
            "scale"=>$scale,
            "westernSystem"=>$westernSystem,
            "populatedScale"=>$populatedScale,
            "matchingScales"=>$matchingScales,
            "instrumentId"=>$session->get("neck/instrumentId"),
            "fingeringsJSON"=>json_encode($fingerings),
            "instrumentJSON"=>json_encode($matrice),
            "fingerings"=>$fingerings,
            "arrayOfFingeringJSON"=>$arrayOfFingeringJSON,
            "instrument"=>$instrument,
            "roots"=>$roots
        ));
    }

}
