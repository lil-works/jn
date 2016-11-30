<?php
namespace SiteBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use AppBundle\Entity\Sequence;
use AppBundle\Entity\SequenceChanges;
use AppBundle\Entity\Intervale;

class SequenceController extends Controller
{
    /**
     * Finds and displays all Sequence entities.
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sequences = $em->getRepository('AppBundle:Sequence')->findAll();


        return $this->render('SiteBundle:Sequence:index.html.twig',array('sequences'=>$sequences));
    }
    /**
     * Finds and displays a Sequence entity.
     * @ParamConverter("sequence", class="AppBundle\Entity\Sequence",options={"mapping": {"sequenceId": "id"  }})
     */
    public function showAction(Request $request,Sequence $sequence)
    {

        $em = $this->getDoctrine()->getManager();
        $sequence = $em->getRepository('AppBundle:Sequence')->findChanges($sequence);



        return $this->render('SiteBundle:Sequence:show.html.twig',array(
            'sequence'=>$sequence
        ));
    }


}
