<?php

namespace SiteBundle\Controller;

use AppBundle\Entity\Fingering;
use AppBundle\Entity\FingeringFinger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AjaxController extends Controller
{

    public function sessionGetAction(Request $request)
    {
        $session = $request->getSession();
        $response = new Response();

        $output = array();
        $output["instrumentId"] = $session->get("neck/instrumentId");
        $output["sound"] = $session->get("neck/sound");

        $response->setContent(json_encode($output));
        return $response;
    }
    public function sessionSetAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $instrumentId = $request->get('instrumentId');
        $sound = $request->get('sound');
        if($instrumentId<=0){
            $instrument = $em->getRepository('AppBundle:Instrument')->findOneByIsDefault(1);
            $instrumentId = $instrument->getId();
        }
        $session = $request->getSession();
        $session->set('neck/instrumentId', $instrumentId);
        $session->set('neck/sound', $sound);
        $response = new Response();
        $response->setContent($session->get("neck"));
        return $response;
    }
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

    public function fingeringAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em = $this->getDoctrine()->getManager();
        $fingering = new Fingering();
        $fingering->setDescription("From controller");
        //$em->persist($fingering);
        foreach( $request->get('i') as $k=>$v){
            $ssc = explode("_",$v);
            $finger = new FingeringFinger();
            $finger->setX($ssc[1]);
            $finger->setY($ssc[0]);
            $finger->setFingering($fingering);
            $fingering->addFinger($finger);
            //$em->persist($finger);
        }
        $em->persist($fingering);
        $em->flush();
        $response = new Response();
        $response->setContent(json_encode($request->get('i')));

        return $response;
    }

}
