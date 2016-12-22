<?php

namespace EHSBundle\Controller;

use EHSBundle\Entity\NewsletterReceiver;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Newsletterreceiver controller.
 *
 * @Route("newsletterreceiver")
 */
class NewsletterReceiverController extends Controller
{
//    /**
//     * Lists all newsletterReceiver entities.
//     *
//     * @Route("/", name="newsletterreceiver_index")
//     * @Method("GET")
//     */
//    public function indexAction()
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        $newsletterReceivers = $em->getRepository('EHSBundle:NewsletterReceiver')->findAll();
//
//        return $this->render('newsletterreceiver/index.html.twig', array(
//            'newsletterReceivers' => $newsletterReceivers,
//        ));
//    }

    /**
     * Creates a new newsletterReceiver entity.
     *
     * @Route("/new", name="newsletterreceiver_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $newsletterReceiver = new Newsletterreceiver();
        $form = $this->createForm('EHSBundle\Form\NewsletterReceiverType', $newsletterReceiver,
            array('action'=>$this->generateUrl('newsletterreceiver_new')));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $verif = $em->getRepository('EHSBundle:NewsletterReceiver')
                ->findOneBy(array('email'=>$newsletterReceiver->getEmail()));
            if ($verif){
                return $this->redirectToRoute('ehs_default_index');
            }
            $em->persist($newsletterReceiver);
            $em->flush($newsletterReceiver);

            return $this->redirectToRoute('ehs_default_index');
        }

        return $this->render('newsletterreceiver/new.html.twig', array(
            'newsletterReceiver' => $newsletterReceiver,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a new newsletterReceiver entity.
     *
     * @Route("/stop", name="newsletterreceiver_stop")
     * @Method({"GET", "POST"})
     */
    public function stopAction(Request $request)
    {
        $newsletterReceiver = new Newsletterreceiver();
        $form = $this->createForm('EHSBundle\Form\NewsletterReceiverType', $newsletterReceiver,
            array('action'=>$this->generateUrl('newsletterreceiver_stop')));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->getRepository('EHSBundle:NewsletterReceiver')->delInscription($newsletterReceiver->getEmail());

            return $this->redirectToRoute('ehs_default_index');
        }

        return $this->render('newsletterreceiver/stopNewsLetter.html.twig', array(
            'newsletterReceiver' => $newsletterReceiver,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a newsletterReceiver entity.
     *
     * @Route("/{id}", name="newsletterreceiver_show")
     * @Method("GET")
     */
    public function showAction(NewsletterReceiver $newsletterReceiver)
    {
        $deleteForm = $this->createDeleteForm($newsletterReceiver);

        return $this->render('newsletterreceiver/show.html.twig', array(
            'newsletterReceiver' => $newsletterReceiver,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing newsletterReceiver entity.
     *
     * @Route("/{id}/edit", name="newsletterreceiver_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, NewsletterReceiver $newsletterReceiver)
    {
        $deleteForm = $this->createDeleteForm($newsletterReceiver);
        $editForm = $this->createForm('EHSBundle\Form\NewsletterReceiverType', $newsletterReceiver);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('newsletterreceiver_edit', array('id' => $newsletterReceiver->getId()));
        }

        return $this->render('newsletterreceiver/edit.html.twig', array(
            'newsletterReceiver' => $newsletterReceiver,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a newsletterReceiver entity.
     *
     * @Route("/{id}", name="newsletterreceiver_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, NewsletterReceiver $newsletterReceiver)
    {
        $form = $this->createDeleteForm($newsletterReceiver);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($newsletterReceiver);
            $em->flush($newsletterReceiver);
        }

        return $this->redirectToRoute('newsletterreceiver_index');
    }

    /**
     * Creates a form to delete a newsletterReceiver entity.
     *
     * @param NewsletterReceiver $newsletterReceiver The newsletterReceiver entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(NewsletterReceiver $newsletterReceiver)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('newsletterreceiver_delete', array('id' => $newsletterReceiver->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
