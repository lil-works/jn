<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Descriptor;
use AppBundle\Form\DescriptorType;

/**
 * Descriptor controller.
 *
 */
class DescriptorController extends Controller
{
    /**
     * Lists all Descriptor entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $descriptors = $em->getRepository('AppBundle:Descriptor')->findAll();

        return $this->render('descriptor/index.html.twig', array(
            'descriptors' => $descriptors,
        ));
    }

    /**
     * Creates a new Descriptor entity.
     *
     */
    public function newAction(Request $request)
    {
        $descriptor = new Descriptor();
        $form = $this->createForm('AppBundle\Form\DescriptorType', $descriptor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($descriptor);
            $em->flush();

            return $this->redirectToRoute('descriptor_show', array('id' => $descriptor->getId()));
        }

        return $this->render('descriptor/new.html.twig', array(
            'descriptor' => $descriptor,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Descriptor entity.
     *
     */
    public function showAction(Descriptor $descriptor)
    {
        $deleteForm = $this->createDeleteForm($descriptor);

        return $this->render('descriptor/show.html.twig', array(
            'descriptor' => $descriptor,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Descriptor entity.
     *
     */
    public function editAction(Request $request, Descriptor $descriptor)
    {
        $deleteForm = $this->createDeleteForm($descriptor);
        $editForm = $this->createForm('AppBundle\Form\DescriptorType', $descriptor);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($descriptor);
            $em->flush();

            return $this->redirectToRoute('descriptor_edit', array('id' => $descriptor->getId()));
        }

        return $this->render('descriptor/edit.html.twig', array(
            'descriptor' => $descriptor,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Descriptor entity.
     *
     */
    public function deleteAction(Request $request, Descriptor $descriptor)
    {
        $form = $this->createDeleteForm($descriptor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($descriptor);
            $em->flush();
        }

        return $this->redirectToRoute('descriptor_index');
    }

    /**
     * Creates a form to delete a Descriptor entity.
     *
     * @param Descriptor $descriptor The Descriptor entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Descriptor $descriptor)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('descriptor_delete', array('id' => $descriptor->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
