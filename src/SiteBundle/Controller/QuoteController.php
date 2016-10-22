<?php
namespace SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
class QuoteController extends Controller
{
    public function randomAction()
    {
        $em = $this->getDoctrine()->getManager();
        $quotes = $em->getRepository('AppBundle:Quote')->findAll();
        $count = count($quotes);
        $quote = $em->getRepository('AppBundle:Quote')->find($quotes[rand(0,$count-1)]->getId());

        return $this->render('SiteBundle:Quote:random.html.twig',array('quote'=>$quote));
    }

}
