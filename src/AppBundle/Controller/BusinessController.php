<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Business;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Business controller.
 *
 * @Route("business")
 */
class BusinessController extends Controller
{
    /**
     * Lists all business entities.
     *
     * @Route("/", name="business_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $businesses = $em->getRepository('AppBundle:Business')->findAll();

        return $this->render('business/index.html.twig', array(
            'businesses' => $businesses,
        ));
    }

    /**
     * Creates a new business entity.
     *
     * @Route("/new", name="business_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $business = new Business();
        $form = $this->createForm('AppBundle\Form\BusinessType', $business);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($business);
            $em->flush();

            return $this->redirectToRoute('business_show', array('id' => $business->getId()));
        }

        return $this->render('business/new.html.twig', array(
            'business' => $business,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a business entity.
     *
     * @Route("/{id}", name="business_show")
     * @Method("GET")
     */
    public function showAction(Business $business)
    {
        $deleteForm = $this->createDeleteForm($business);

        return $this->render('business/show.html.twig', array(
            'business' => $business,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing business entity.
     *
     * @Route("/{id}/edit", name="business_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Business $business)
    {
        $deleteForm = $this->createDeleteForm($business);
        $editForm = $this->createForm('AppBundle\Form\BusinessType', $business);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('business_edit', array('id' => $business->getId()));
        }

        return $this->render('business/edit.html.twig', array(
            'business' => $business,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a business entity.
     *
     * @Route("/{id}", name="business_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Business $business)
    {
        $form = $this->createDeleteForm($business);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($business);
            $em->flush();
        }

        return $this->redirectToRoute('business_index');
    }

    /**
     * Creates a form to delete a business entity.
     *
     * @param Business $business The business entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Business $business)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('business_delete', array('id' => $business->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
