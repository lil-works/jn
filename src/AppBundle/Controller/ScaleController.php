<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use AppBundle\Entity\Scale;
use AppBundle\Form\ScaleType;

/**
 * Scale controller.
 *
 */
class ScaleController extends Controller
{
    /**
     * Lists all Scale entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $scales = $em->getRepository('AppBundle:Scale')->findAll() ;

        return $this->render('scale/index.html.twig', array(
            'scales' => $scales,
        ));
    }

    /**
     * Creates a new Scale entity.
     *
     */
    public function newAction(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $scale = new Scale();
        $form = $this->createForm('AppBundle\Form\ScaleType', $scale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($scale);
            $em->flush();

            return $this->redirectToRoute('scale_show', array('id' => $scale->getId()));
        }

        return $this->render('scale/new.html.twig', array(
            'scale' => $scale,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Scale entity.
     * @ParamConverter("scale", class="AppBundle\Entity\Scale",options={"mapping": {"id": "id"  }})
     */
    public function showAction(Scale $scale)
    {
        $deleteForm = $this->createDeleteForm($scale);

        return $this->render('scale/show.html.twig', array(
            'scale' => $scale,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Scale entity.
     * @ParamConverter("scale", class="AppBundle\Entity\Scale",options={"mapping": {"id": "id"  }})
     */
    public function editAction(Request $request, Scale $scale)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $deleteForm = $this->createDeleteForm($scale);
        $editForm = $this->createForm('AppBundle\Form\ScaleType', $scale);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($scale);
            $em->flush();

            return $this->redirectToRoute('scale_edit', array('id' => $scale->getId()));
        }

        return $this->render('scale/edit.html.twig', array(
            'scale' => $scale,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Scale entity.
     * @ParamConverter("scale", class="AppBundle\Entity\Scale",options={"mapping": {"id": "id"  }})
     */
    public function deleteAction(Request $request, Scale $scale)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createDeleteForm($scale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($scale);
            $em->flush();
        }

        return $this->redirectToRoute('scale_index');
    }

    /**
     * Creates a form to delete a Scale entity.
     *
     * @param Scale $scale The Scale entity
     * @ParamConverter("scale", class="AppBundle\Entity\Scale",options={"mapping": {"id": "id"  }})
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Scale $scale)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('scale_delete', array('id' => $scale->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
