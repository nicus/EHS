<?php

namespace EHSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render(':default:index.html.twig');
    }

    /**
     * @Route("/association", name="index_asso")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function assoAction(){

        return $this->render('default/asso.html.twig');
    }
}
