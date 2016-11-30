<?php
namespace SiteBundle\Controller;

use AppBundle\Entity\Instrument;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


use Knp\Menu\Matcher\Matcher;
use Knp\Menu\MenuFactory;
use Knp\Menu\Renderer\ListRenderer;

class InstrumentController extends Controller
{
    public function menuAction($context = null)
    {
        $stack = $this->get('request_stack');
        $masterRequest = $stack->getMasterRequest();
        $currentRoute = $masterRequest->get('_route');
        $currentRouteParams = $masterRequest->get('_route_params');

        if(is_null($context)){
            $view = "menu";
        }else{
            $view = "navbar";
        }

        $em = $this->getDoctrine()->getManager();
        $instrumentFamilies = $em->getRepository('AppBundle:InstrumentFamily')->findAll();

        return $this->render('SiteBundle:Instrument:'.$view.'.html.twig',array(
            'instrumentFamilies'=>$instrumentFamilies,
            "currentRoute"=>$currentRoute,
            "currentRouteParams"=>$currentRouteParams
        ));
    }


}
