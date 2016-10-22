<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use AppBundle\Entity\Quote;
use AppBundle\Form\QuoteType;

/**
 * Quote controller.
 *
 */
class QuoteController extends Controller
{
    /**
     * Lists all Quote entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $quotes = $em->getRepository('AppBundle:Quote')->findAll();

        return $this->render('quote/index.html.twig', array(
            'quotes' => $quotes,
        ));
    }

    /**
     * Creates a new Quote entity.
     *
     */
    public function newAction(Request $request)
    {
        $quote = new Quote();
        $form = $this->createForm('AppBundle\Form\QuoteType', $quote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($quote);
            $em->flush();

            return $this->redirectToRoute('quote_show', array('id' => $quote->getId()));
        }

        return $this->render('quote/new.html.twig', array(
            'quote' => $quote,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Quote entity.
     * @ParamConverter("quote", class="AppBundle\Entity\Quote",options={"mapping": {"id": "id"  }})
     */
    public function showAction(Quote $quote)
    {
        $deleteForm = $this->createDeleteForm($quote);

        return $this->render('quote/show.html.twig', array(
            'quote' => $quote,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Quote entity.
     * @ParamConverter("quote", class="AppBundle\Entity\Quote",options={"mapping": {"id": "id"  }})
     */
    public function editAction(Request $request, Quote $quote)
    {
        $deleteForm = $this->createDeleteForm($quote);
        $editForm = $this->createForm('AppBundle\Form\QuoteType', $quote);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($quote);
            $em->flush();

            return $this->redirectToRoute('quote_edit', array('id' => $quote->getId()));
        }

        return $this->render('quote/edit.html.twig', array(
            'quote' => $quote,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Quote entity.
     * @ParamConverter("quote", class="AppBundle\Entity\Quote",options={"mapping": {"id": "id"  }})
     */
    public function deleteAction(Request $request, Quote $quote)
    {
        $form = $this->createDeleteForm($quote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($quote);
            $em->flush();
        }

        return $this->redirectToRoute('quote_index');
    }

    /**
     * Creates a form to delete a Quote entity.
     *
     * @param Quote $quote The Quote entity
     * @ParamConverter("quote", class="AppBundle\Entity\Quote",options={"mapping": {"id": "id"  }})
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Quote $quote)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('quote_delete', array('id' => $quote->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
