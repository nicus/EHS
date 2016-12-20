<?php

namespace EHSBundle\Controller;

use EHSBundle\Entity\Event;
use EHSBundle\Entity\EventInscription;
use EHSBundle\Entity\Image;
use EHSBundle\Entity\Program;
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
        if ($this->isGranted('ROLE_ADMIN')){
            $em = $this->getDoctrine()->getManager();

            $events = $em->getRepository('EHSBundle:Event')->findAll();

            return $this->render('event/index.html.twig', array(
                'events' => $events,
            ));
        }
        return $this->redirectToRoute('ehs_default_index');
    }

    /**
     * @Route("/listEvent", name="event_frontShow")
     * @Method("GET")
     */
    public function frontShowAction(){
        $em= $this->getDoctrine()->getManager();
        $listEvents = $em->getRepository('EHSBundle:Event')->getNoArchivedEvents();

        return $this->render('event/frontShow.html.twig', array('events'=>$listEvents));
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
            $program = new Program();
            $event->setProgram($program);
            $event->setArchived(false);
            $em = $this->getDoctrine()->getManager();
            $em->persist($event->getProgram());
            $this->addImages($event);
            if ($event->getNewAdress()){
                $em->persist(($event->getNewAdress()));
                $event->setAppointment($event->getNewAdress());
            }
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
     * Get the next event
     *
     * @Route("/next", name="event_next")
     * @Method("GET")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function nextEventAction(){
        $em = $this->getDoctrine()->getManager();
        $nextEvent=$em->getRepository('EHSBundle:Event')->getNextEvent();

        return $this->render(':event:nextEvent.html.twig', array('nextEvent'=>$nextEvent));
    }

    /**
     *
     * @Route("/registeredList/{id}", name="event_registered_list")
     */
    public function registeredListAction(Event $event){
        $registeredList= $event->getInscriptions();

        return $this->render('event/registeredList.html.twig', array(
            'registeredList'=> $registeredList,
            'event'=>$event));
    }

    /**
     * for delete one inscription in EventInscription
     * @Route("/delinscription/{event}/{eventInscription}", name="event_delInscription")
     * @Method("GET")
     */
    public function delEventInscriptionAction(Event $event, EventInscription $eventInscription){
        $listInscriptions = $event->getInscriptions();
        $listInscriptions->removeElement($eventInscription);
        $em=$this->getDoctrine()->getManager();
        $em->persist($event);
        $em->flush();
        $em->getRepository('EHSBundle:EventInscription')->delInscription($eventInscription->getId());

        return $this->redirectToRoute('event_registered_list', array('id'=>$event->getId()));
    }

    /**
     * Finds and displays a event entity.
     *
     * @Route("/{id}", name="event_show")
     * @Method("GET")
     */
    public function showAction(Event $event)
    {
        if  (!$event->getArchived() ||($event->getArchived()&& $this->isGranted('ROLE_PRESS'))){
            $deleteForm = $this->createDeleteForm($event);
            $url = $this->container->get('request')->headers->get('referer');
            if (empty($url)) {
                $url = $this->container->get('router')->generate('event_frontShow');
            }

            return $this->render('event/show.html.twig', array(
                'bachUrl'=>$url,
                'event' => $event,
                'delete_form' => $deleteForm->createView(),
            ));
        }
        return $this->redirectToRoute('event_frontShow');
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
