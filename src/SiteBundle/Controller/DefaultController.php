<?php
namespace SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $instrument = $em->getRepository('AppBundle:Instrument')->find(1);
        $matrice = $em->getRepository('AppBundle:Instrument')->getMatrice(1);

        return $this->render('SiteBundle:Default:index.html.twig',array('instrument'=>$instrument,'matrice'=>json_encode($matrice)));
    }
    public function neckAction(Request $request)
    {
        $session = $request->getSession();

        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->setTitle($seoPage->getTitle() . " • Instrument neck")
            ->addMeta('name', 'description', "Choose your instrument and plot tones from scale or search scale selecting tones over the neck")
        ;
        return $this->render('SiteBundle:Default:neck.html.twig',array(
            "instrumentId"=>$session->get('neck/instrumentId')
        ));
    }
}
