<?php

namespace Poa\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FaqController extends Controller
{
    public function indexAction()
    {
        /*
         * The action's view can be rendered using render() method
         * or @Template annotation as demonstrated in MainController.
         *
         */
		/** @var $em \Poa\MainBundle\Entity\FaqRepository */
		$em = $this->getDoctrine()->getRepository('PoaMainBundle:Faq');
        return $this->render('PoaMainBundle:Faq:index.html.twig',array('content' => $em->findActive()));
    }
}
