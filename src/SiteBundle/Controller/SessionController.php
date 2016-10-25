<?php

namespace SiteBundle\Controller;

use AppBundle\Entity\Fingering;
use AppBundle\Entity\FingeringFinger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionController extends Controller
{

    public function indexAction(Request $request)
    {

        $session = $request->getSession();
        $response = new Response();

        $output = array();

        if(! $session->get("neck/instrumentId")> 0){
            $session->set("neck/instrumentId",1);
        }

        if( $session->get("neck/sound") == ""){
            $session->set("neck/sound","piano");
        }


        $output["instrumentId"] = $session->get("neck/instrumentId");
        $output["sound"] = $session->get("neck/sound");

        $response->setContent("");
        return $response;
    }

}
