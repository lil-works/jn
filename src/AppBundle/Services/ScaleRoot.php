<?php


namespace AppBundle\Services;


use AppBundle\AppBundle;
use AppBundle\Entity\WesternSystem;
use AppBundle\Entity\Scale;
use Symfony\Component\Form\Extension\Templating\TemplatingExtension;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;


class ScaleRoot implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected $em;
    protected $scale;
    protected $westernSystem;
    protected $templating;
    protected $active = false;


    public function __construct(\Doctrine\ORM\EntityManager $em ,  $templating , ContainerInterface $container )
    {
        $this->em = $em;
        $this->templating = $templating;
        $this->container = $container;

    }
    public function init(Scale $scale, WesternSystem $westernSystem,$active = false)
    {
        $this->scale = $scale;
        $this->westernSystem = $westernSystem;
        $this->active = $active;
        return $this;
    }


    public function getScaleRoot($instrument=null,$descriptors=null){

        if($this->active === false){
            return $this->templating->render('Services/scaleroot-inactive.html.twig', array(

            ));
        }else{
            $containTheRefAndIsContainedInRef =
                $this->em->getRepository('AppBundle:Scale')
                    ->findForScaleRootService(
                        $this->scale,
                        $this->westernSystem,
                        $descriptors
                    );


            $populatedScale = $this->em->getRepository('AppBundle:Scale')->westernPopulateRootScale($this->scale->getId(),$this->westernSystem->getName()) ;
            $roots = $this->em->getRepository('AppBundle:WesternSystem')->findByIntervale(1) ;
            return $this->templating->render('Services/scaleroot.html.twig', array(
                "instrument"=>$instrument,
                "root"=>$this->westernSystem->getName(),
                "roots"=>$roots,
                "populatedScale"=>$populatedScale,
                "scale"=>$this->scale,
                "containTheRefAndIsContainedInRef"=>$containTheRefAndIsContainedInRef
            ));
        }

    }

    public function getScaleRootSmall($instrument=null,$descriptors=null){


            $containTheRefAndIsContainedInRef =
                $this->em->getRepository('AppBundle:Scale')
                    ->findForScaleRootLimitedService(
                        $this->scale,
                        $this->westernSystem,
                        $descriptors
                    );


            $populatedScale = $this->em->getRepository('AppBundle:Scale')->westernPopulateRootScale($this->scale->getId(),$this->westernSystem->getName()) ;
            $roots = $this->em->getRepository('AppBundle:WesternSystem')->findByIntervale(1) ;
            return $this->templating->render('Services/scaleroot-limited.html.twig', array(
                "instrument"=>$instrument,
                "root"=>$this->westernSystem->getName(),
                "rootId"=>$this->westernSystem->getId(),
                "rootId"=>$this->westernSystem->getName(),
                "roots"=>$roots,
                "populatedScale"=>$populatedScale,
                "scale"=>$this->scale,
                "containTheRefAndIsContainedInRef"=>$containTheRefAndIsContainedInRef
            ));
        }



}