<?php

namespace EHSBundle\Controller;

use EHSBundle\Entity\User;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * User controller.
 *
 * @Route("user")
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        if ($this->isGranted('ROLE_SUPER_ADMIN')){
            $em = $this->getDoctrine()->getManager();

            $users = $em->getRepository('EHSBundle:User')->findAll();

            return $this->render('user/index.html.twig', array(
                'users' => $users,
            ));
        }
        return $this->redirectToRoute('ehs_default_index');
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $dispatcher = $this->get('event_dispatcher');
        $form = $this->createForm('EHSBundle\Form\UserType', $user, array('action'=>$this->generateUrl('user_new')));

        $user->setEnabled(false);

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::REGISTRATION_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

        $form->setData($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUsername($user->getEmail());
            $user->setPlainPassword('P@ssword95+ùHsbT&');
            $user->setAccepted(false);

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_SUCCESS, $event);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('fos_user_registration_confirmed');
                $response = new RedirectResponse($url);
            }

//            $dispatcher->dispatch(FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
//
//            return $response;

//            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
            return $this->redirectToRoute('ehs_default_index');
        }

        return $this->render('user/new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Validation new account
     *
     * @Route("/accept/{id}", name="user_accept")
     * @Method("GET")
     *
     */
    public function acceptAction(User $user){
        $em=$this->getDoctrine()->getManager();

        if (null === $user->getConfirmationToken()) {
            /** @var $tokenGenerator \FOS\UserBundle\Util\TokenGeneratorInterface */
            $tokenGenerator = $this->get('fos_user.util.token_generator');
            $user->setConfirmationToken($tokenGenerator->generateToken());
        }

        $this->get('fos_user.mailer')->sendResettingEmailMessage($user);
        $user->setAccepted(true);
        $OneYear=new \DateTime();
        $OneYear=$OneYear->setTimestamp($OneYear->getTimestamp()+(3600*24*365));
        $user->setExpiresAt($OneYear);
        $user->setPasswordRequestedAt(new \DateTime());
        $em->persist($user);
        $em->flush($user);

        return $this->redirectToRoute('user_index');
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('user/show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        if ($this->isGranted('ROLE_SUPER_ADMIN') || $this->getUser()->getId()== $request->attributes->get('id')){
            $deleteForm = $this->createDeleteForm($user);
            $editForm = $this->createForm('EHSBundle\Form\UserType', $user, array('action'=>$this->generateUrl('user_edit',
                array('id'=>$user->getId()))));
            $editForm->handleRequest($request);

            if ($editForm->isSubmitted() && $editForm->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('ehs_default_index');
            }
        }else{
            return $this->redirectToRoute('ehs_default_index');
        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        if ($this->isGranted('ROLE_SUPER_ADMIN')){
            $form = $this->createDeleteForm($user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->remove($user);
                $em->flush($user);
            }
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
