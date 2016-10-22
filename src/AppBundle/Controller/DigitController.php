<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use AppBundle\Entity\Digit;
use AppBundle\Form\DigitType;

/**
 * Digit controller.
 *
 */
class DigitController extends Controller
{
    /**
     * Lists all Digit entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $digits = $em->getRepository('AppBundle:Digit')->findAll();

        return $this->render('digit/index.html.twig', array(
            'digits' => $digits,
        ));
    }

    /**
     * Creates a new Digit entity.
     *
     */
    public function newAction(Request $request)
    {
        $digit = new Digit();
        $form = $this->createForm('AppBundle\Form\DigitType', $digit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($digit);
            $em->flush();

            return $this->redirectToRoute('digit_show', array('id' => $digit->getId()));
        }

        return $this->render('digit/new.html.twig', array(
            'digit' => $digit,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Digit entity.
     * @ParamConverter("digit", class="AppBundle\Entity\Digit",options={"mapping": {"id": "id"  }})
     */
    public function showAction(Digit $digit)
    {
        $deleteForm = $this->createDeleteForm($digit);

        return $this->render('digit/show.html.twig', array(
            'digit' => $digit,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Digit entity.
     * @ParamConverter("digit", class="AppBundle\Entity\Digit",options={"mapping": {"id": "id"  }})
     */
    public function editAction(Request $request, Digit $digit)
    {
        $deleteForm = $this->createDeleteForm($digit);
        $editForm = $this->createForm('AppBundle\Form\DigitType', $digit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($digit);
            $em->flush();

            return $this->redirectToRoute('digit_edit', array('id' => $digit->getId()));
        }

        return $this->render('digit/edit.html.twig', array(
            'digit' => $digit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Digit entity.
     * @ParamConverter("digit", class="AppBundle\Entity\Digit",options={"mapping": {"id": "id"  }})
     */
    public function deleteAction(Request $request, Digit $digit)
    {
        $form = $this->createDeleteForm($digit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($digit);
            $em->flush();
        }

        return $this->redirectToRoute('digit_index');
    }

    /**
     * Creates a form to delete a Digit entity.
     *
     * @param Digit $digit The Digit entity
     * @ParamConverter("digit", class="AppBundle\Entity\Digit",options={"mapping": {"id": "id"  }})
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Digit $digit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('digit_delete', array('id' => $digit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
