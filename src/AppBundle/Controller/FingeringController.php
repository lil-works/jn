<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Fingering;
use AppBundle\Form\FingeringType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
/**
 * Fingering controller.
 *
 */
class FingeringController extends Controller
{
    /**
     * Lists all Fingering entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $fingerings = $em->getRepository('AppBundle:Fingering')->findAll();

        return $this->render('fingering/index.html.twig', array(
            'fingerings' => $fingerings,
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

            return $this->redirectToRoute('admin_fingering_show', array('id' => $fingering->getId()));
        }

        return $this->render('fingering/new.html.twig', array(
            'fingering' => $fingering,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Fingering entity.
     * @ParamConverter("fingering", class="AppBundle\Entity\Fingering",options={"mapping": {"id": "id"  }})
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
     * @ParamConverter("fingering", class="AppBundle\Entity\Fingering",options={"mapping": {"id": "id"  }})
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

        return $this->render('fingering/edit.html.twig', array(
            'fingering' => $fingering,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Finds and displays a Fingering entity.
     * @ParamConverter("fingering", class="AppBundle\Entity\Fingering",options={"mapping": {"id": "id"  }})
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
     * @ParamConverter("fingering", class="AppBundle\Entity\Fingering",options={"mapping": {"id": "id"  }})
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Fingering $fingering)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_fingering_delete', array('id' => $fingering->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
