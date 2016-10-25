<?php
namespace SiteBundle\Controller;

use AppBundle\Entity\Descriptor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use AppBundle\Entity\Scale;
class ScaleController extends Controller
{
    public function indexAction(Request $request)
    {
        $search = null;
        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm('SiteBundle\Form\SearchScaleType');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData();
        }
        $scales = $em->getRepository('AppBundle:Scale')->findAllByEverything($search) ;

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->setTitle($seoPage->getTitle() . " • search in scales")
            ->addMeta('name', 'description', "search by name, intervals, and tag in all scales")
        ;

        return $this->render('SiteBundle:Scale:index.html.twig',array(
            'scales'=>$scales,
            'form' => $form->createView(),
            'search'=>$search
        ));
    }
    /**
     * Finds and displays a Author entity.
     * @ParamConverter("descriptor", class="AppBundle\Entity\Descriptor",options={"mapping": {"descriptor_name": "name"  }})
     */
    public function descriptorAction(Request $request,Descriptor $descriptor)
    {
        $em = $this->getDoctrine()->getManager();
        $scaleTypes = $em->getRepository('AppBundle:Descriptor')->findAll() ;
        $scales = $descriptor->getScales() ;

        return $this->render('SiteBundle:Scale:descriptor.html.twig',array(
            'descriptor'=>$descriptor,
            'scales'=>$scales,
            'scaleTypes'=>$scaleTypes
        ));
    }
    /**
     * Finds and displays a Author entity.
     * @ParamConverter("scale", class="AppBundle\Entity\Scale",options={"mapping": {"scale_name": "name"  }})
     */
    public function showAction(Request $request,Scale $scale)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();

        if(!$session->get('neck/instrumentId'))
            $session->set('neck/instrumentId',1);

        $instrument = $em->getRepository('AppBundle:Instrument')->find($session->get('neck/instrumentId')) ;
        $matrice = $em->getRepository('AppBundle:Instrument')->getMatrice($instrument->getId());


        $populatedScale = $em->getRepository('AppBundle:Scale')->westernPopulateScale($scale->getId()) ;
        $intervales = array();
        foreach($scale->getIntervales() as $intervale){
            array_push($intervales,$intervale->getId());
        }
        $matchingScales = $em->getRepository('AppBundle:Scale')->matchingScale($intervales,$scale->getId()) ;

        $westernSystem = $em->getRepository('AppBundle:WesternSystem')->findOneByName(array("name"=>"D","intervale"=>1));

        $fingerings = $em->getRepository('AppBundle:Fingering')->findFingeringByRootAndScale($instrument,$scale,$westernSystem) ;

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->setTitle($seoPage->getTitle() . " • ".$scale->getName())
            ->addMeta('name', 'description', "details for ".$scale->getName())
        ;
        $arrayOfFingeringJSON = array();
        foreach($fingerings as $fingering){
            array_push($arrayOfFingeringJSON,json_encode($fingering));
        }
        return $this->render('SiteBundle:Scale:show.html.twig',array(
            "scale"=>$scale,
            "populatedScale"=>$populatedScale,
            "matchingScales"=>$matchingScales,
            "instrumentId"=>$session->get("neck/instrumentId"),
            "fingeringsJSON"=>json_encode($fingerings),
            "instrumentJSON"=>json_encode($matrice),
            "fingerings"=>$fingerings,
            "westernSystem"=>$westernSystem,
            "arrayOfFingeringJSON"=>$arrayOfFingeringJSON,
            "instrument"=>$instrument,
        ));
    }
}
