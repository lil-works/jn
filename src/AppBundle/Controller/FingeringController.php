<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Fingering;
use AppBundle\Form\FingeringType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
/**
 * Fingering controller.
 *
 */
class FingeringController extends Controller
{
    /**
     * Lists all Fingering entities.
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dql   = "SELECT f FROM AppBundle:Fingering f";
        $query = $em->createQuery($dql);


        $fingering = new Fingering();
        $form = $this->createForm('AppBundle\Form\FingeringType', $fingering);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fingering);
            $em->flush();

            return $this->redirectToRoute('admin_fingering_show', array('id' => $fingering->getId()));
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('fingering/index.html.twig', array(
            'pagination' => $pagination,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new Fingering entity.
     *
     */
    public function newAction(Request $request)
    {
        $fingering = new Fingering();
        $form = $this->createForm('AppBundle\Form\FingeringType', $fingering);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fingering);
            $em->flush();

            return $this->redirectToRoute('admin_fingering');
        }

        return $this->render('fingering/new.html.twig', array(
            'fingering' => $fingering,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Fingering entity.
     * @ParamConverter("fingering", class="AppBundle\Entity\Fingering",options={"mapping": {"fingeringId": "id"  }})
     */
    public function showAction(Fingering $fingering)
    {
        $deleteForm = $this->createDeleteForm($fingering);

        return $this->render('fingering/show.html.twig', array(
            'fingering' => $fingering,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a Fingering entity.
     * @ParamConverter("fingering", class="AppBundle\Entity\Fingering",options={"mapping": {"fingeringId": "id"  }})
     */
    public function editAction(Request $request, Fingering $fingering)
    {
        $deleteForm = $this->createDeleteForm($fingering);
        $editForm = $this->createForm('AppBundle\Form\FingeringType', $fingering);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($fingering);
            $em->flush();

            return $this->redirectToRoute('admin_fingering_edit', array('id' => $fingering->getId()));
        }

        return $this->render('fingering/index.html.twig', array(
            'fingering' => $fingering,
            'fingerings' => null,
            'form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a Fingering entity.
     * @ParamConverter("fingering", class="AppBundle\Entity\Fingering",options={"mapping": {"fingeringId": "id"  }})
     */
    public function deleteAction(Request $request, Fingering $fingering)
    {
        $form = $this->createDeleteForm($fingering);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($fingering);
            $em->flush();
        }

        return $this->redirectToRoute('admin_fingering_index');
    }

    /**
     * Creates a form to delete a Fingering entity.
     *
     * @param Fingering $fingering The Fingering entity
     * @ParamConverter("fingering", class="AppBundle\Entity\Fingering",options={"mapping": {"fingeringId": "id"  }})
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Fingering $fingering)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_fingering_delete', array('fingeringId' => $fingering->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
