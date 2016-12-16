<?php

namespace EHSBundle\Controller;

use EHSBundle\Entity\Appointment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Appointment controller.
 *
 * @Route("appointment")
 */
class AppointmentController extends Controller
{
    /**
     * Lists all appointment entities.
     *
     * @Route("/", name="appointment_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $appointments = $em->getRepository('EHSBundle:Appointment')->findAll();

        return $this->render('appointment/index.html.twig', array(
            'appointments' => $appointments,
        ));
    }

    /**
     * Creates a new appointment entity.
     *
     * @Route("/new", name="appointment_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $appointment = new Appointment();
        $form = $this->createForm('EHSBundle\Form\AppointmentType', $appointment
            , array(
                'action'=>$this->generateUrl('appointment_new')
            ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($appointment);
            $em->flush($appointment);

            return $this->redirectToRoute('appointment_show', array('id' => $appointment->getId()));
        }

        return $this->render('appointment/new.html.twig', array(
            'appointment' => $appointment,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a appointment entity.
     *
     * @Route("/{id}", name="appointment_show")
     * @Method("GET")
     */
    public function showAction(Appointment $appointment)
    {
        $deleteForm = $this->createDeleteForm($appointment);

        return $this->render('appointment/show.html.twig', array(
            'appointment' => $appointment,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing appointment entity.
     *
     * @Route("/{id}/edit", name="appointment_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Appointment $appointment)
    {
        $deleteForm = $this->createDeleteForm($appointment);
        $editForm = $this->createForm('EHSBundle\Form\AppointmentType', $appointment
            , array(
                'action'=>$this->generateUrl('appointment_edit', array('id'=>$appointment->getId()))));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('appointment_index');
        }

        return $this->render('appointment/edit.html.twig', array(
            'appointment' => $appointment,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a appointment entity.
     *
     * @Route("/{id}", name="appointment_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Appointment $appointment)
    {
        $form = $this->createDeleteForm($appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($appointment);
            $em->flush($appointment);
        }

        return $this->redirectToRoute('appointment_index');
    }

    /**
     * Creates a form to delete a appointment entity.
     *
     * @param Appointment $appointment The appointment entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Appointment $appointment)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('appointment_delete', array('id' => $appointment->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
