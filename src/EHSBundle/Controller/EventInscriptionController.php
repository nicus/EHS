<?php

namespace EHSBundle\Controller;

use EHSBundle\Entity\Event;
use EHSBundle\Entity\EventInscription;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Eventinscription controller.
 *
 * @Route("eventinscription")
 */
class EventInscriptionController extends Controller
{
    /**
     * Lists all eventInscription entities.
     *
     * @Route("/", name="eventinscription_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $eventInscriptions = $em->getRepository('EHSBundle:EventInscription')->findAll();

        return $this->render('eventinscription/index.html.twig', array(
            'eventInscriptions' => $eventInscriptions,
        ));
    }

    /**
     * Accept inscription
     *
     * @Route("/validated", name="eventInscription_validated")
     * @Method("POST")
     */
    public function validatedAction(){

            $em= $this->getDoctrine()->getManager();
            $eventInscription=$em->getRepository('EHSBundle:EventInscription')->find($_POST['id']);
            $value = ($_POST['value'] =='true')? 1 : 0;
            $eventInscription->setValidated($value);
            $em->persist($eventInscription);
            $em->flush();

        return $this->redirectToRoute('event_index');
    }

    /**
     * Creates a new eventInscription entity.
     *
     * @Route("/new/{event}", name="eventinscription_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Event $event)
    {
        $eventInscription = new Eventinscription();
        $form = $this->createForm('EHSBundle\Form\EventInscriptionType', $eventInscription,
            array(
            'action'=>$this->generateUrl('eventinscription_new', array('event'=> $event->getId()) )
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $url = $this->container->get('request')->headers->get('referer').'?message=1';
            if (empty($url)) {
                $url = $this->container->get('router')->generate('event_index');
            }
            $eventInscription->setValidated(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($eventInscription);
            $em->flush($eventInscription);
            $participate=$event->getInscriptions();
            $participate[]=$eventInscription;
            $event->setInscriptions($participate);
            $em->persist($event);
            $em->flush();

            return new RedirectResponse($url);
        }

        return $this->render('eventinscription/new.html.twig', array(
            'eventInscription' => $eventInscription,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a eventInscription entity.
     *
     * @Route("/{id}", name="eventinscription_show")
     * @Method("GET")
     */
    public function showAction(EventInscription $eventInscription)
    {
        $deleteForm = $this->createDeleteForm($eventInscription);

        return $this->render('eventinscription/show.html.twig', array(
            'eventInscription' => $eventInscription,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing eventInscription entity.
     *
     * @Route("/{id}/edit", name="eventinscription_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, EventInscription $eventInscription)
    {
        $deleteForm = $this->createDeleteForm($eventInscription);
        $editForm = $this->createForm('EHSBundle\Form\EventInscriptionType', $eventInscription);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('eventinscription_edit', array('id' => $eventInscription->getId()));
        }

        return $this->render('eventinscription/edit.html.twig', array(
            'eventInscription' => $eventInscription,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a eventInscription entity.
     *
     * @Route("/{id}", name="eventinscription_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, EventInscription $eventInscription)
    {
        $form = $this->createDeleteForm($eventInscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($eventInscription);
            $em->flush($eventInscription);
        }

        return $this->redirectToRoute('eventinscription_index');
    }

    /**
     * Creates a form to delete a eventInscription entity.
     *
     * @param EventInscription $eventInscription The eventInscription entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(EventInscription $eventInscription)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('eventinscription_delete', array('id' => $eventInscription->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
