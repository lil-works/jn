<?php
namespace SiteBundle\Controller;

use AppBundle\Entity\Instrument;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
class DefaultController extends Controller
{

    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $instrumentFamilies = $em->getRepository('AppBundle:InstrumentFamily')->findAll();


        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->setTitle($seoPage->getTitle() . " • Welcome")
            ->addMeta('name', 'description', "Choose your instrument and plot tones from scale or search scale selecting tones over the neck")
        ;

        return $this->render('SiteBundle:Default:index.html.twig',array(
            'instrumentFamilies'=>$instrumentFamilies
        ));
    }
    public function aboutAction()
    {
        return $this->render('SiteBundle:Default:about.html.twig',array(

        ));
    }


    public function neckAction(Request $request, $instrumentId)
    {

        $em = $this->getDoctrine()->getManager();
        if(! $instrument = $em->getRepository('AppBundle:Instrument')->find($instrumentId)){
            $instrument = new Instrument();
        }


        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage
            ->setTitle($seoPage->getTitle() . " • ".$instrument->getName()." neck")
            ->addMeta('name', 'description', "Choose your instrument and plot tones from scale or search scale selecting tones over the neck")
        ;

        return $this->render('SiteBundle:Default:neck.html.twig',array(
            "instrument"=>$instrument
        ));
    }

}
