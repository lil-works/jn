<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Instrument;
use AppBundle\Form\InstrumentType;

/**
 * Instrument controller.
 *
 */
class InstrumentController extends Controller
{
    /**
     * Lists all Instrument entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $instruments = $em->getRepository('AppBundle:Instrument')->findAll();

        return $this->render('instrument/index.html.twig', array(
            'instruments' => $instruments,
        ));
    }

    /**
     * Creates a new Instrument entity.
     *
     */
    public function newAction(Request $request)
    {
        $instrument = new Instrument();
        $form = $this->createForm('AppBundle\Form\InstrumentType', $instrument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($instrument);
            $em->flush();

            return $this->redirectToRoute('instrument_show', array('id' => $instrument->getId()));
        }

        return $this->render('instrument/new.html.twig', array(
            'instrument' => $instrument,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Instrument entity.
     *
     */
    public function showAction(Instrument $instrument)
    {
        $deleteForm = $this->createDeleteForm($instrument);

        return $this->render('instrument/show.html.twig', array(
            'instrument' => $instrument,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Instrument entity.
     *
     */
    public function editAction(Request $request, Instrument $instrument)
    {
        $deleteForm = $this->createDeleteForm($instrument);
        $editForm = $this->createForm('AppBundle\Form\InstrumentType', $instrument);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($instrument);
            $em->flush();

            return $this->redirectToRoute('instrument_edit', array('id' => $instrument->getId()));
        }

        return $this->render('instrument/edit.html.twig', array(
            'instrument' => $instrument,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Instrument entity.
     *
     */
    public function deleteAction(Request $request, Instrument $instrument)
    {
        $form = $this->createDeleteForm($instrument);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($instrument);
            $em->flush();
        }

        return $this->redirectToRoute('instrument_index');
    }

    /**
     * Creates a form to delete a Instrument entity.
     *
     * @param Instrument $instrument The Instrument entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Instrument $instrument)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('instrument_delete', array('id' => $instrument->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
