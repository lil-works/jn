<?php

namespace AppBundle\Controller;

use AppBundle\Entity\InstrumentFamily;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Instrumentfamily controller.
 *
 */
class InstrumentFamilyController extends Controller
{
    /**
     * Lists all instrumentFamily entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $instrumentFamilies = $em->getRepository('AppBundle:InstrumentFamily')->findAll();

        return $this->render('instrumentfamily/index.html.twig', array(
            'instrumentFamilies' => $instrumentFamilies,
        ));
    }

    /**
     * Creates a new instrumentFamily entity.
     *
     */
    public function newAction(Request $request)
    {
        $instrumentFamily = new Instrumentfamily();
        $form = $this->createForm('AppBundle\Form\InstrumentFamilyType', $instrumentFamily);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($instrumentFamily);
            $em->flush($instrumentFamily);

            return $this->redirectToRoute('instrumentfamily_show', array('id' => $instrumentFamily->getId()));
        }

        return $this->render('instrumentfamily/new.html.twig', array(
            'instrumentFamily' => $instrumentFamily,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a instrumentFamily entity.
     *
     */
    public function showAction(InstrumentFamily $instrumentFamily)
    {
        $deleteForm = $this->createDeleteForm($instrumentFamily);

        return $this->render('instrumentfamily/show.html.twig', array(
            'instrumentFamily' => $instrumentFamily,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing instrumentFamily entity.
     *
     */
    public function editAction(Request $request, InstrumentFamily $instrumentFamily)
    {
        $deleteForm = $this->createDeleteForm($instrumentFamily);
        $editForm = $this->createForm('AppBundle\Form\InstrumentFamilyType', $instrumentFamily);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('instrumentfamily_edit', array('id' => $instrumentFamily->getId()));
        }

        return $this->render('instrumentfamily/edit.html.twig', array(
            'instrumentFamily' => $instrumentFamily,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a instrumentFamily entity.
     *
     */
    public function deleteAction(Request $request, InstrumentFamily $instrumentFamily)
    {
        $form = $this->createDeleteForm($instrumentFamily);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($instrumentFamily);
            $em->flush($instrumentFamily);
        }

        return $this->redirectToRoute('instrumentfamily_index');
    }

    /**
     * Creates a form to delete a instrumentFamily entity.
     *
     * @param InstrumentFamily $instrumentFamily The instrumentFamily entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(InstrumentFamily $instrumentFamily)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('instrumentfamily_delete', array('id' => $instrumentFamily->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
