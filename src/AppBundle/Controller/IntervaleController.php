<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use AppBundle\Entity\Intervale;
use AppBundle\Form\IntervaleType;

/**
 * Intervale controller.
 *
 */
class IntervaleController extends Controller
{
    /**
     * Lists all Intervale entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $intervales = $em->getRepository('AppBundle:Intervale')->findAll();

        return $this->render('intervale/index.html.twig', array(
            'intervales' => $intervales,
        ));
    }

    /**
     * Creates a new Intervale entity.
     *
     */
    public function newAction(Request $request)
    {
        $intervale = new Intervale();
        $form = $this->createForm('AppBundle\Form\IntervaleType', $intervale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($intervale);
            $em->flush();

            return $this->redirectToRoute('intervale_show', array('id' => $intervale->getId()));
        }

        return $this->render('intervale/new.html.twig', array(
            'intervale' => $intervale,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Intervale entity.
     * @ParamConverter("intervale", class="AppBundle\Entity\Intervale",options={"mapping": {"id": "id"  }})
     */
    public function showAction(Intervale $intervale)
    {
        $deleteForm = $this->createDeleteForm($intervale);

        return $this->render('intervale/show.html.twig', array(
            'intervale' => $intervale,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Intervale entity.
     * @ParamConverter("intervale", class="AppBundle\Entity\Intervale",options={"mapping": {"id": "id"  }})
     */
    public function editAction(Request $request, Intervale $intervale)
    {
        $deleteForm = $this->createDeleteForm($intervale);
        $editForm = $this->createForm('AppBundle\Form\IntervaleType', $intervale);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($intervale);
            $em->flush();

            return $this->redirectToRoute('intervale_edit', array('id' => $intervale->getId()));
        }

        return $this->render('intervale/edit.html.twig', array(
            'intervale' => $intervale,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Intervale entity.
     * @ParamConverter("intervale", class="AppBundle\Entity\Intervale",options={"mapping": {"id": "id"  }})
     *
     */
    public function deleteAction(Request $request, Intervale $intervale)
    {
        $form = $this->createDeleteForm($intervale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($intervale);
            $em->flush();
        }

        return $this->redirectToRoute('intervale_index');
    }

    /**
     * Creates a form to delete a Intervale entity.
     *
     * @param Intervale $intervale The Intervale entity
     * @ParamConverter("intervale", class="AppBundle\Entity\Intervale",options={"mapping": {"id": "id"  }})
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Intervale $intervale)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('intervale_delete', array('id' => $intervale->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
