<?php
namespace SiteBundle\Controller;

use AppBundle\Entity\Instrument;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
class MelodicLineController extends Controller
{
    public function indexAction(Request $request , $context = null)
    {

        $form = $this->createForm('SiteBundle\Form\MelodicLineType');

        return $this->render('SiteBundle:MelodicLine:index.html.twig',array(
            'form'=>$form->createView()
        ));
    }


}
