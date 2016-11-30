<?php

namespace AppBundle\Controller;

use AppBundle\Entity\RhythmSequence;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Rhythmsequence controller.
 *
 */
class RhythmSequenceController extends Controller
{
    /**
     * Lists all rhythmSequence entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $rhythmSequences = $em->getRepository('AppBundle:RhythmSequence')->findAll();

        return $this->render('rhythmsequence/index.html.twig', array(
            'rhythmSequences' => $rhythmSequences,
        ));
    }

    /**
     * Creates a new rhythmSequence entity.
     *
     */
    public function newAction(Request $request)
    {
        $rhythmSequence = new Rhythmsequence();
        $form = $this->createForm('AppBundle\Form\RhythmSequenceType', $rhythmSequence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($rhythmSequence);
            $em->flush($rhythmSequence);

            return $this->redirectToRoute('rhythmsequence_show', array('id' => $rhythmSequence->getId()));
        }

        return $this->render('rhythmsequence/new.html.twig', array(
            'rhythmSequence' => $rhythmSequence,
            'form' => $form->createView(),
        ));
    }

    /**
     * Displays an existing rhythmSequence entity.
     * @ParamConverter("rhythmSequence", class="AppBundle\Entity\RhythmSequence",options={"mapping": {"id": "id"  }})
     */
    public function showAction(RhythmSequence $rhythmSequence)
    {
        $deleteForm = $this->createDeleteForm($rhythmSequence);

        return $this->render('rhythmsequence/show.html.twig', array(
            'rhythmSequence' => $rhythmSequence,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing rhythmSequence entity.
     *
     */
    public function editAction(Request $request, RhythmSequence $rhythmSequence)
    {
        $deleteForm = $this->createDeleteForm($rhythmSequence);
        $editForm = $this->createForm('AppBundle\Form\RhythmSequenceType', $rhythmSequence);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('rhythmsequence_edit', array('id' => $rhythmSequence->getId()));
        }

        return $this->render('rhythmsequence/edit.html.twig', array(
            'rhythmSequence' => $rhythmSequence,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a rhythmSequence entity.
     *
     */
    public function deleteAction(Request $request, RhythmSequence $rhythmSequence)
    {
        $form = $this->createDeleteForm($rhythmSequence);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($rhythmSequence);
            $em->flush($rhythmSequence);
        }

        return $this->redirectToRoute('rhythmsequence_index');
    }

    /**
     * Creates a form to delete a rhythmSequence entity.
     *
     * @param RhythmSequence $rhythmSequence The rhythmSequence entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(RhythmSequence $rhythmSequence)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('rhythmsequence_delete', array('id' => $rhythmSequence->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
