<?php

namespace BasketBundle\Controller;

use AppBundle\Entity\FingeringOffline;
use AppBundle\Entity\Yx;
use BasketBundle\Entity\FingeringBasket;
use BasketBundle\Entity\FingeringBasketsFingerings;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use AppBundle\Entity\Instrument;
use AppBundle\Entity\Fingering;
use AppBundle\Entity\WesternSystem;
use AppBundle\Entity\Scale;
use AppBundle\Entity\User;

use Knp\Menu\Matcher\Matcher;
use Knp\Menu\MenuFactory;
use Knp\Menu\Renderer\ListRenderer;

class DefaultController extends Controller
{
    /**
     * @ParamConverter("user", class="AppBundle\Entity\User",options={"mapping": {"username": "username"  }})
     */
    public function userAction(Request $request,User $user)
    {
        $em    = $this->get('doctrine.orm.entity_manager');

        $dql   = "SELECT fb FROM BasketBundle:FingeringBasket fb WHERE fb.createdBy = " . $user->getId();
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        $session = new Session();

        return $this->render('BasketBundle:Default:user.html.twig', array(
            'pagination' => $pagination,
            'current'=> $session->get('fingeringBasket/id')
        ));

    }
    /**
     * @ParamConverter("fingeringBasket", class="BasketBundle\Entity\FingeringBasket",options={"mapping": {"fBasketId": "id"  }})
     */
    public function deleteAction(Request $request,FingeringBasket $fingeringBasket)
    {
        $session = new Session();
        $em    = $this->get('doctrine.orm.entity_manager');

        ($this->getUser() == $fingeringBasket->getCreatedBy())?
            $isOwner=true:$isOwner=false;

        if($isOwner == false){
            throw new NotFoundHttpException("Not your business...");
        }

        if($session->get('fingeringBasket/id') == $fingeringBasket->getId()){
            $session->set('fingeringBasket/id',null);
        }

        $em->remove($fingeringBasket);
        $em->flush();
        return $this->redirectToRoute('basket_fingering_user', array('username' => $this->getUser()->getUsername()));

    }

    public function newAction(Request $request)
    {
        $session = new Session();

        $em = $this->getDoctrine()->getManager();
        $newFingeringBasket = new FingeringBasket();
        $newFingeringBasket->setCreatedBy($this->getUser());
        $em->persist($newFingeringBasket);
        $em->flush();

        $session->set('fingeringBasket/id',$newFingeringBasket->getId());

        return $this->redirectToRoute('basket_fingering_user', array('username' => $this->getUser()->getUsername()));

    }
    /**
     * @ParamConverter("fingeringBasket", class="BasketBundle\Entity\FingeringBasket",options={"mapping": {"fBasketId": "id"  }})
     */
    public function showAction(FingeringBasket $fingeringBasket)
    {
        $session = new Session();

        ($session->get('fingeringBasket/id') ==  $fingeringBasket->getId())?
            $isCurrent=true:$isCurrent=false;
        ($this->getUser() == $fingeringBasket->getCreatedBy())?
            $isOwner=true:$isOwner=false;



        return $this->render('BasketBundle:Default:show.html.twig',array(
            "fingeringBasket"=>$fingeringBasket,
            "isOwner"=>$isOwner,
            "isCurrent"=>$isCurrent,
        ));
    }

    /**
     * @ParamConverter("fingeringBasket", class="BasketBundle\Entity\FingeringBasket",options={"mapping": {"fBasketId": "id"  }})
     */
    public function duplicateAction(Request $request,FingeringBasket $fingeringBasket)
    {
        ($this->getUser() == $fingeringBasket->getCreatedBy())?
            $isOwner=true:$isOwner=false;

        /*
         * Check if duplicate is allowed
         */
        if($isOwner ||  $fingeringBasket->getPrivate() != 1){
            $em = $this->getDoctrine()->getManager();

            $newFingeringBasket = new FingeringBasket();


            $newFingeringBasket->setCreatedBy($this->getUser());
            $newFingeringBasket->setCreatedAt(null);

            $newFingeringBasket->setTitle($fingeringBasket->getTitle());
            $newFingeringBasket->setDescription($fingeringBasket->getDescription());
            $newFingeringBasket->setInstrument($fingeringBasket->getInstrument());
            foreach($fingeringBasket->getFingeringbasketsFingerings() as $fingering){
                $newFingeringBasket->addFingeringbasketsFingering($fingering);
            }

            $em->persist($newFingeringBasket);
            $em->flush();
            return $this->redirectToRoute('basket_fingering_show', array('fBasketId' => $fingeringBasket->getId()));
        }

    }

    /**
     * @ParamConverter("fingeringBasket", class="BasketBundle\Entity\FingeringBasket",options={"mapping": {"fBasketId": "id"  }})
     */
    public function unselectAction(Request $request,FingeringBasket $fingeringBasket)
    {
        $session = new Session();
        $session->set('fingeringBasket/id',null);
        return $this->redirect($request->headers->get('referer'));
    }
    /**
     * @ParamConverter("fingeringBasket", class="BasketBundle\Entity\FingeringBasket",options={"mapping": {"fBasketId": "id"  }})
     */
    public function selectAction(Request $request,FingeringBasket $fingeringBasket)
    {
        $session = new Session();
        $session->set('fingeringBasket/id',$fingeringBasket->getId());
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @ParamConverter("fingeringBasket", class="BasketBundle\Entity\FingeringBasket",options={"mapping": {"fBasketId": "id"  }})
     * @ParamConverter("fingeringBasketFingering", class="BasketBundle\Entity\FingeringBasketsFingerings",options={"mapping": {"fingeringId": "id"  }})
     */
    public function deletefingeringAction(Request $request,FingeringBasket $fingeringBasket,FingeringBasketsFingerings $fingeringBasketFingering)
    {
        $session = new Session();
        $em    = $this->get('doctrine.orm.entity_manager');

        ($this->getUser() == $fingeringBasket->getCreatedBy())?
            $isOwner=true:$isOwner=false;

        if($isOwner == false){
            throw new NotFoundHttpException("Not your business...");
        }



        $em->remove($fingeringBasketFingering);
        $em->flush();
        return $this->redirectToRoute('basket_fingering_show', array('fBasketId' =>$fingeringBasket->getId()));

    }

    /**
     * @ParamConverter("instrument", class="AppBundle\Entity\Instrument",options={"mapping": {"instrumentId": "id"  }})
     * @ParamConverter("westernSystem", class="AppBundle\Entity\WesternSystem",options={"mapping": {"westernSystemId": "id"  }})

     * @ParamConverter("scale", class="AppBundle\Entity\Scale",options={"mapping": {"scaleId": "id"  }})
     */
    public function addAction(Request $request,Instrument $instrument , WesternSystem $westernSystem, $fingeringId = 0 , Scale $scale, $xList, $yList,$dList,$wList=null,$iList=null)
    {



        $em = $this->getDoctrine()->getManager();

        if($fingeringId > 0){
            $fingering = $em->getRepository('AppBundle:Fingering')->find($fingeringId);
        }else{
            //$fingering = new Fingering();
        }

        // first check if logged
        $securityContext = $this->container->get('security.authorization_checker');
        if (!$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            throw new NotFoundHttpException("Must be logged in...");
        }

        $session = new Session();

        // If no session open
        if( ! $session->get('fingeringBasket/id') ){
            $fingeringBasket = new FingeringBasket();
            $fingeringBasket->setCreatedBy($this->getUser());
            $fingeringBasket->setInstrument($instrument);
        }else{
            $fingeringBasket = $em->getRepository('BasketBundle:FingeringBasket')->find($session->get('fingeringBasket/id'));
        }
        $em->persist($fingeringBasket);

        $fingeringOffline = new FingeringOffline();
        $fingeringOffline->setScale($scale);
        $fingeringOffline->setRoot($westernSystem);
        $fingeringOffline->setInstrument($instrument);
        if(isset($fingering)){
            $fingeringOffline->setFingering($fingering);
        }
        $em->persist($fingeringOffline);

        if($iList)
            $iArray = explode(",",$iList) ;
        if($wList)
            $wArray = explode(",",$wList) ;
        $dArray = explode(",",$dList) ;
        $yArray = explode(",",$yList) ;
        $xArray = explode(",",$xList) ;
        foreach( $yArray  as $k=>$y ){
            $yx = new Yx();
            $yx->setX($xArray[$k]);
            $yx->setY($yArray[$k]);
            $yx->setDigitA($dArray[$k]);
            if(isset($iArray)){
                if($intervale = $em->getRepository('AppBundle:Intervale')->findOneByName($iArray[$k]))
                    $yx->setIntervale($intervale);
            }
            if(isset($wArray)){
                if($westernSystemForYx = $em->getRepository('AppBundle:WesternSystem')->findOneByName(array("name"=>$wArray[$k],"intervale"=>1)))
                    $yx->setWesternSystem($westernSystemForYx);
            }
            $em->persist($yx);
            $fingeringOffline->addYx($yx);
        }

        $fingeringBasketsFingerings = new FingeringBasketsFingerings();
        $fingeringBasketsFingerings->setFingeringbasket($fingeringBasket);
        $fingeringBasketsFingerings->setFingeringOffline($fingeringOffline);
        $em->persist($fingeringBasketsFingerings);


        $em->flush();

        $session->set('fingeringBasket/id',$fingeringBasket->getId());

        return $this->redirect($request->headers->get('referer'));
    }
    /**
     * @ParamConverter("fingeringBasket", class="BasketBundle\Entity\FingeringBasket",options={"mapping": {"fBasketId": "id"  }})
     */
    public function emptyAction(Request $request,FingeringBasket $fingeringBasket)
    {
        $em = $this->getDoctrine()->getEntityManager();

        /*
         * First check the owner
         */


        foreach($fingeringBasket->getFingeringbasketsFingerings() as $fingering){
            $em->remove($fingering);
        }
        $em->flush();
        return $this->redirect($request->headers->get('referer'));
    }
    public function menuAction()
    {
        return $this->render('BasketBundle:Default:navbar.html.twig',array());
    }
}
