<?php
// src/Poa/MenuBundle/Content/ContentBuilder.php

	namespace Poa\MainBundle\Content;

	use Doctrine\ORM\EntityManager;
	use Symfony\Component\HttpFoundation\Request;

	class ContentBuilder
	{

		/** @var \Doctrine\ORM\EntityManager */
		private $entityManager;

		/**
		 * @param \Doctrine\ORM\EntityManager $entityManager
		 */
		public function __construct(EntityManager $entityManager)
		{
			$this->entityManager = $entityManager;
		}

		/**
		 * @param \Symfony\Component\HttpFoundation\Request $request
		 * @return array
		 */
		public function createContent(Request $request)
		{
			$routeName = $request->get('_route');

			$routerRepository = $this
				->getManager()
				->getRepository('PoaMainBundle:Menu');

			$routeId = $routerRepository->getIdForRoute($routeName);

			/** @var $em \Poa\MainBundle\Entity\ContentRepository */
			$em = $this->entityManager->getRepository('PoaMainBundle:Content');
			return $em->getMenuForParent($routeId);
		}
	}