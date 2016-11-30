<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RhythmMaterial;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Rhythmmaterial controller.
 *
 */
class RhythmMaterialController extends Controller
{
    /**
     * Lists all rhythmMaterial entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $rhythmMaterials = $em->getRepository('AppBundle:RhythmMaterial')->findAll();

        return $this->render('rhythmmaterial/index.html.twig', array(
            'rhythmMaterials' => $rhythmMaterials,
        ));
    }

    /**
     * Creates a new rhythmMaterial entity.
     *
     */
    public function newAction(Request $request)
    {
        $rhythmMaterial = new Rhythmmaterial();
        $form = $this->createForm('AppBundle\Form\RhythmMaterialType', $rhythmMaterial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($rhythmMaterial);
            $em->flush($rhythmMaterial);

            return $this->redirectToRoute('rhythmmaterial_show', array('id' => $rhythmMaterial->getId()));
        }

        return $this->render('rhythmmaterial/new.html.twig', array(
            'rhythmMaterial' => $rhythmMaterial,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a rhythmMaterial entity.
     *
     */
    public function showAction(RhythmMaterial $rhythmMaterial)
    {
        $deleteForm = $this->createDeleteForm($rhythmMaterial);

        return $this->render('rhythmmaterial/show.html.twig', array(
            'rhythmMaterial' => $rhythmMaterial,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing rhythmMaterial entity.
     *
     */
    public function editAction(Request $request, RhythmMaterial $rhythmMaterial)
    {
        $deleteForm = $this->createDeleteForm($rhythmMaterial);
        $editForm = $this->createForm('AppBundle\Form\RhythmMaterialType', $rhythmMaterial);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rhythmmaterial_edit', array('id' => $rhythmMaterial->getId()));
        }

        return $this->render('rhythmmaterial/edit.html.twig', array(
            'rhythmMaterial' => $rhythmMaterial,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a rhythmMaterial entity.
     *
     */
    public function deleteAction(Request $request, RhythmMaterial $rhythmMaterial)
    {
        $form = $this->createDeleteForm($rhythmMaterial);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($rhythmMaterial);
            $em->flush($rhythmMaterial);
        }

        return $this->redirectToRoute('rhythmmaterial_index');
    }

    /**
     * Creates a form to delete a rhythmMaterial entity.
     *
     * @param RhythmMaterial $rhythmMaterial The rhythmMaterial entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(RhythmMaterial $rhythmMaterial)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rhythmmaterial_delete', array('id' => $rhythmMaterial->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
