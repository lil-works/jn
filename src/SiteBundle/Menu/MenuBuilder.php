<?php
// src/AppBundle/Menu/MenuBuilder.php

namespace SiteBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    private $factory;
    protected $em;
    protected $router;



    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory,\Doctrine\ORM\EntityManager $em, ContainerInterface $container    )
    {
        $this->factory = $factory;
        $this->em = $em;
        $this->container = $container;
    }

    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', array('route' => 'site_homepage'));


        return $menu;
    }
    public function createScaleMenu(array $options)
    {


        $menu = $this->factory->createItem('scales', array('childrenAttributes' => array('class' => 'dropdown-menu')));
        //$menu->addChild('Scales', array('route' => 'site_scale_index'));
        $descriptors = $this->em->getRepository('AppBundle:Descriptor')->findAll();

        foreach($descriptors as $descriptor){
            $menu->addChild($descriptor->getName(), array(
                'route' => 'site_scale_descriptor_show',
                'routeParameters' => array(
                    'descriptorId' => $descriptor->getId(),
                    'descriptorName' =>$descriptor->getName()
                )
            ));
        }


        return $menu;
    }
    public function createLangMenu(array $options)
    {

        $request = $this->container->get('request_stack');
        $params = $request->getParentRequest()->get('_route_params');
        $route = $request->getParentRequest()->get('_route');

        $menu = $this->factory->createItem('Available languages', array('childrenAttributes' => array('class' => 'dropdown-menu')));

        $paramsEn = $paramsFr = $paramsEs = $params;
        $paramsEn["_locale"] = "en";
        $menu->addChild("English", array(
            'route' => $route,
            'routeParameters' => $paramsEn,

        ));

        $paramsFr["_locale"] = "fr";
        $menu->addChild("French", array(
            'route' =>$route,
            'routeParameters' => $paramsFr
        ));

        $paramsEs["_locale"] = "es";
        $menu->addChild("Spanish", array(
            'route' => $route,
            'routeParameters' => $paramsEs,

        ));
        return $menu;
    }



    public function createInstrumentMenu(array $options)
    {

        $request = $this->container->get('request_stack');
        $params = $request->getParentRequest()->get('_route_params');
        $route = $request->getParentRequest()->get('_route');

        $menu = $this->factory->createItem('Available necks', array('childrenAttributes' => array('class' => 'dropdown-menu')));

        return $menu;
    }

    public function createUserMenu(array $options)
    {

        $request = $this->container->get('request_stack');
        $params = $request->getParentRequest()->get('_route_params');
        $route = $request->getParentRequest()->get('_route');

        $menu = $this->factory->createItem('User', array('childrenAttributes' => array('class' => 'dropdown-menu')));


        $menu->addChild('Register', array('route' => 'fos_user_registration_register'));
        $menu->addChild('Login', array('route' => 'fos_user_security_login'));
        $menu->addChild('Profile', array('route' => 'fos_user_profile_show'));
        $menu->addChild('Logout', array('route' => 'fos_user_security_logout'));

        return $menu;
    }
}