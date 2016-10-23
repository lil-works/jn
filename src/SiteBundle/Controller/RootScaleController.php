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
        $populatedScale = $em->getRepository('AppBundle:Scale')->westernPopulateRootScale($scale->getId(),$westernSystem->getName()) ;
        $intervales = array();
        foreach($scale->getIntervales() as $intervale){
            array_push($intervales,$intervale->getId());
        }
        $matchingScales = $em->getRepository('AppBundle:Scale')->matchingRootScale($scale,$westernSystem) ;


        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->setTitle($seoPage->getTitle() . " • ".$westernSystem->getName().$scale->getName())
            ->addMeta('name', 'description', "details for ".$westernSystem->getName().$scale->getName())
        ;

        $session = $request->getSession();


        return $this->render('SiteBundle:RootScale:index.html.twig',array(
            "scale"=>$scale,
            "westernSystem"=>$westernSystem,
            "populatedScale"=>$populatedScale,
            "matchingScales"=>$matchingScales,
            "instrumentId"=>$session->get("neck/instrumentId")
        ));
    }

}
