<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use AppBundle\Entity\Author;
use AppBundle\Form\AuthorType;

/**
 * Author controller.
 *
 */
class FBasketController extends Controller
{
    /**
     * Lists all fBasket entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em    = $this->get('doctrine.orm.entity_manager');

        $dql   = "SELECT fb FROM BasketBundle:FingeringBasket fb";
        $query = $em->createQuery($dql);

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('fbasket/index.html.twig', array(
            'pagination' => $pagination,
        ));
    }

}
