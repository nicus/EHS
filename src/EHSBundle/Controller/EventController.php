<?php

namespace EHSBundle\Controller;

use EHSBundle\Entity\Event;
use EHSBundle\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Event controller.
 *
 * @Route("event")
 */
class EventController extends Controller
{
    /**
     * Lists all event entities.
     *
     * @Route("/", name="event_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $events = $em->getRepository('EHSBundle:Event')->findAll();

        return $this->render('event/index.html.twig', array(
            'events' => $events,
        ));
    }

    /**
     * Creates a new event entity.
     *
     * @Route("/new", name="event_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $event = new Event();
        $form = $this->createForm('EHSBundle\Form\EventType', $event, array(
            'action'=>$this->generateUrl('event_new')
        ));
        $form->add('startDate', DateTimeType::class, array('label'=> 'Date et heure de début',
        'data'=> new \DateTime()
    ))
        ->add('endDate', DateTimeType::class, array('label'=> 'Date et heure de Fin',
            'data'=> new \DateTime()
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event->setArchived(false);
            $em = $this->getDoctrine()->getManager();
            $this->addImages($event);
            $em->persist($event);
            $em->flush($event);

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/new.html.twig', array(
            'event' => $event,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a event entity.
     *
     * @Route("/{id}", name="event_show")
     * @Method("GET")
     */
    public function showAction(Event $event)
    {
        $deleteForm = $this->createDeleteForm($event);

        return $this->render('event/show.html.twig', array(
            'event' => $event,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing event entity.
     *
     * @Route("/{id}/edit", name="event_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Event $event)
    {
        $deleteForm = $this->createDeleteForm($event);
        $editForm = $this->createForm('EHSBundle\Form\EventType', $event, array(
            'action'=>$this->generateUrl('event_edit', array('id'=>$event->getId()))));
        $editForm->add('startDate', DateTimeType::class, array('label'=> 'Date et heure de début' ))
            ->add('endDate', DateTimeType::class, array('label'=> 'Date et heure de Fin' ))
            ->add('archived', CheckboxType::class, array('label'=>'Archivé l\'évènement', 'required'=>false));
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->addImages($event);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/edit.html.twig', array(
            'event' => $event,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a event entity.
     *
     * @Route("/{id}", name="event_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Event $event)
    {
        $form = $this->createDeleteForm($event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($event);
            $em->flush($event);
        }

        return $this->redirectToRoute('event_index');
    }

    /**
     * Creates a form to delete a event entity.
     *
     * @param Event $event The event entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Event $event)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('event_delete', array('id' => $event->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    /**
     * for Add images in new and edit Form
     *
     * @param Event $event
     */
    private function addImages(Event $event){
        if ($event->getNewImages()->getFile()[0]){
            $em = $this->getDoctrine()->getManager();
            foreach ($event->getNewImages()->getFile() as $item){
                $image= new Image();
                $image->setFile($item);
                $em->persist($image);
                $listImages=$event->getImages();
                $listImages[]=$image;
                $event->setImages($listImages);
            }

        }
    }
}
