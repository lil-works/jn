<?php
namespace SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use AppBundle\Entity\Instrument;
use AppBundle\Entity\Fingering;

class FingeringController extends Controller
{
    /**
     * Finds and displays a Author entity.
     * @ParamConverter("instrument", class="AppBundle\Entity\Instrument",options={"mapping": {"instrumentId": "id"  }})
     */
    public function indexAction(Request $request,Instrument $instrument)
    {
        $em = $this->getDoctrine()->getManager();
        $fingerings = $em->getRepository('AppBundle:Fingering')->findAll();


        return $this->render('SiteBundle:Fingering:index.html.twig',array('fingerings'=>$fingerings));
    }
    /**
     * Finds and displays a Author entity.
     * @ParamConverter("instrument", class="AppBundle\Entity\Instrument",options={"mapping": {"instrumentId": "id"  }})
     * @ParamConverter("fingering", class="AppBundle\Entity\Fingering",options={"mapping": {"fingeringId": "id"  }})
     */
    public function showAction(Request $request,Instrument $instrument,Fingering $fingering)
    {
        $em = $this->getDoctrine()->getManager();
        $matrice = $em->getRepository('AppBundle:Instrument')->getMatrice($instrument->getId());
        $rootScales = $em->getRepository('AppBundle:Fingering')->findRootScaleForInstrument($fingering,$instrument);

        return $this->render('SiteBundle:Fingering:show.html.twig',array(
            'instrument'=>$instrument,
            "instrumentJSON"=>json_encode($matrice),
            'rootScales'=>$rootScales,
            'fingering'=>$fingering
        ));
    }


}
