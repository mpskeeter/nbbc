<?php
/**
 * Created by JetBrains PhpStorm.
 * User: skeeter
 * Date: 5/7/13
 * Time: 9:54 PM
 * To change this template use File | Settings | File Templates.
 */

	// src/Blogger/BlogBundle/Controller/BlogController.php

	namespace Poa\BlogBundle\Controller;

	use Symfony\Bundle\FrameworkBundle\Controller\Controller;

	/**
	 * Class BlogController
	 * @package Poa\BlogBundle\Controller
	 */
	class BlogController extends Controller {
		/**
		 * Show a blog entry
		 */
		public function showAllAction()
		{
			$em = $this->getDoctrine()->getManager();

			$blog = $em->getRepository('PoaBlogBundle:Blog')->find(1);

			if (!$blog) {
				throw $this->createNotFoundException('Unable to find Blog post.');
			}

			return $this->render('PoaBlogBundle:Blog:show_all.html.twig', array(
				'blog'      => $blog,
			));
		}

		/**
		 * Show a blog entry
		 */
		public function showAction($id)
		{
			$em = $this->getDoctrine()->getManager();

			$blog = $em->getRepository('PoaBlogBundle:Blog')->find($id);

			if (!$blog) {
				throw $this->createNotFoundException('Unable to find Blog post.');
			}

			return $this->render('PoaBlogBundle:Blog:show.html.twig', array(
				'blog'      => $blog,
			));
		}
	}