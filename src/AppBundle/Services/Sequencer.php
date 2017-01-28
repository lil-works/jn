<?php


namespace AppBundle\Services;



use AppBundle\Entity\WesternSystem;
use AppBundle\Entity\Scale;
use AppBundle\Entity\Intervale;
use Symfony\Component\Form\Extension\Templating\TemplatingExtension;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;


class Sequencer implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected $em;
    protected $templating;
    protected $bin = array();

    public function __construct(\Doctrine\ORM\EntityManager $em ,  $templating , ContainerInterface $container )
    {
        $this->em = $em;
        $this->templating = $templating;
        $this->container = $container;

    }
    public function init()
    {

        return $this;
    }
    public function addRootScale($westernSystem,$scale)
    {

        $this->bin[$westernSystem->getId()."_".$scale->getId()]  = $this->em->getRepository('AppBundle:Scale')->populateScaleByWesternSystem($westernSystem,$scale) ;

        return $this;
    }

    public function getView(){

        return $this->templating->render("Services/Sequencer/sequencer.html.twig", array(
            "bin"=>json_encode($this->bin)
        ));

    }

}