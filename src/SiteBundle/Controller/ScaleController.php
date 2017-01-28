<?php
namespace SiteBundle\Controller;

use AppBundle\Entity\Descriptor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use AppBundle\Entity\Scale;
use AppBundle\Entity\Instrument;

use Knp\Menu\Matcher\Matcher;
use Knp\Menu\MenuFactory;
use Knp\Menu\Renderer\ListRenderer;

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
     * @ParamConverter("descriptor", class="AppBundle\Entity\Descriptor",options={"mapping": {"descriptorId": "id"  }})
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
     * @ParamConverter("scale", class="AppBundle\Entity\Scale",options={"mapping": {"scale": "name"  }})

     */
    public function showAction(Request $request,Scale $scale)
    {
        $session = $request->getSession();
        $em = $this->getDoctrine()->getManager();


        //$matrice = $em->getRepository('AppBundle:Instrument')->getMatrice($instrument->getId());


        $populatedScale = $em->getRepository('AppBundle:Scale')->westernPopulateScale($scale->getId()) ;
        $intervales = array();
        foreach($scale->getIntervales() as $intervale){
            array_push($intervales,$intervale->getId());
        }
        $matchingScales = $em->getRepository('AppBundle:Scale')->matchingScale($intervales,$scale->getId()) ;

        $westernSystem = $em->getRepository('AppBundle:WesternSystem')->findOneByName(array("name"=>"D","intervale"=>1));

        //$fingerings = $em->getRepository('AppBundle:Fingering')->findFingeringByRootAndScale($instrument,$scale,$westernSystem) ;

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->setTitle($seoPage->getTitle() . " • ".$scale->getName())
            ->addMeta('name', 'description', "details for ".$scale->getName())
        ;
        /*
        $arrayOfFingeringJSON = array();
        foreach($fingerings as $fingering){
            array_push($arrayOfFingeringJSON,json_encode($fingering));
        }*/
        return $this->render('SiteBundle:Scale:show.html.twig',array(
            "scale"=>$scale,
            "populatedScale"=>$populatedScale,
            "matchingScales"=>$matchingScales,
            "instrumentId"=>$session->get("neck/instrumentId"),
          //  "fingeringsJSON"=>json_encode($fingerings),
          //  "instrumentJSON"=>json_encode($matrice),
          //  "fingerings"=>$fingerings,
            "westernSystem"=>$westernSystem,
          //  "arrayOfFingeringJSON"=>$arrayOfFingeringJSON,
           // "instrument"=>$instrument,
        ));
    }
    /**
     * Finds and displays a Rooted Scale entity.
     * @ParamConverter("scale", class="AppBundle\Entity\Scale",options={"mapping": {"scaleId": "id"  }})
     */
    public function rootAction(Request $request,Scale $scale,$root)
    {

        $em = $this->getDoctrine()->getManager();
        $containTheRefAndIsContainedInRef = $em->getRepository('AppBundle:Scale')->findContainTheRefAndIsContainedInRef($scale,$root) ;
        $roots = $em->getRepository('AppBundle:WesternSystem')->findByIntervale(1) ;
        $populatedScale = $em->getRepository('AppBundle:Scale')->westernPopulateRootScale($scale->getId(),$root) ;
        $westernSystem = $em->getRepository('AppBundle:WesternSystem')->findOneByName(array("intervale"=>1,"name"=>$root)) ;

        $scaleRootService = $this->get('app.scaleRoot')->init($scale,$westernSystem,true)->getScaleRoot();

        return $this->render('SiteBundle:Scale:root.html.twig',array(
            "scaleRootService"=>$scaleRootService,
            "root"=>$root,
            "roots"=>$roots,
            "populatedScale"=>$populatedScale,
            "scale"=>$scale,
            "containTheRefAndIsContainedInRef"=>$containTheRefAndIsContainedInRef

        ));
    }
    public function networkAction(Request $request)
    {


        $em = $this->getDoctrine()->getManager();
$scaleId = 5;
        $edgesAndNodes = $em->getRepository('AppBundle:Scale')->findEdgesAndNodes(
            array(
                array("wId"=>21,"sId"=>$scaleId),
                array("wId"=>48,"sId"=>$scaleId),
                array("wId"=>39,"sId"=>$scaleId),
                     array("wId"=>49,"sId"=>$scaleId),
                       array("wId"=>285,"sId"=>$scaleId),
                       array("wId"=>30,"sId"=>$scaleId),
                       array("wId"=>375,"sId"=>$scaleId),
                       array("wId"=>37,"sId"=>$scaleId),
                       array("wId"=>465,"sId"=>$scaleId),
                       array("wId"=>41,"sId"=>$scaleId),
                       array("wId"=>442,"sId"=>$scaleId),
                       array("wId"=>45,"sId"=>$scaleId),
            )
        ) ;
//41 44 39 49 285 30 37 375 465 41 442 45
        return $this->render('SiteBundle:Scale:network.html.twig',array(
            "nodes"=>json_encode($edgesAndNodes["nodes"]),
            "edges"=>json_encode($edgesAndNodes["edges"]),
        ));
    }

    public function menuAction($context = null)
    {
        if(is_null($context)){
            $view = "menu";
        }else{
            $view = "navbar";
        }

        return $this->render('SiteBundle:Scale:'.$view.'.html.twig',array());
    }
}
