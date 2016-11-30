<?php
namespace SiteBundle\Controller;

use AppBundle\Entity\Instrument;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Knp\Menu\Matcher\Matcher;
use Knp\Menu\MenuFactory;
use Knp\Menu\Renderer\ListRenderer;

class LangController extends Controller
{
    public function menuAction(Request $request , $context = null)
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
        return $this->render('SiteBundle:Lang:'.$view.'.html.twig',array(
            "currentRoute"=>$currentRoute,
            "currentRouteParams"=>$currentRouteParams
        ));
    }


}
