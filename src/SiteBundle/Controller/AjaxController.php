<?php

namespace SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends Controller
{
    public function instrumentAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $instrument_id = $request->get('instrument_id');

        if($instrument_id<=0){
            $instrument = $em->getRepository('AppBundle:Instrument')->findOneByIsDefault(1);
            $instrument_id = $instrument->getId();
        }

        $instrument = $em->getRepository('AppBundle:Instrument')->getMatrice($instrument_id);


        $response = new Response();
        $response->setContent(json_encode($instrument));


        return $response;
    }
    public function instrumentsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $instruments = $em->getRepository('AppBundle:Instrument')->ajaxFindAll();

        $response = new Response();
        $response->setContent(json_encode($instruments));


        return $response;
    }
    public function rootAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $roots = $em->getRepository('AppBundle:WesternSystem')->ajaxFindAllRoot();

        $response = new Response();
        $response->setContent(json_encode($roots));


        return $response;
    }
    public function scaleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $scales = $em->getRepository('AppBundle:Scale')->ajaxFindAll();


        $response = new Response();
        $response->setContent(json_encode($scales));


        return $response;
    }
    public function rootScaleAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $r = $request->get('r');
        $s = $request->get('s');
        $i = $request->get('i');


        $instrument = $em->getRepository('AppBundle:Instrument')->getMatriceRootScale($i,$r,$s);


        $response = new Response();
        $response->setContent(json_encode($instrument));


        return $response;
    }
    public function searchRootScaleByDigitsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $digits = $request->get('digits');

        $instrument = $em->getRepository('AppBundle:Instrument')->findRootScaleByDigits($digits);


        $response = new Response();
        $response->setContent(json_encode($instrument));


        return $response;
    }

}