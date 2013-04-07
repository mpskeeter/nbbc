<?php
// src/Poa/MenuBundle/Menu/MenuBuilder.php

	namespace Poa\MainBundle\Menu;

	use Doctrine\ORM\EntityManager;
	use Knp\Menu\FactoryInterface;
	use Symfony\Component\HttpFoundation\Request;

	class MenuBuilder
	{

		/** @var \Knp\Menu\FactoryInterface */
		private $factory;

		/** @var \Doctrine\ORM\EntityManager */
		private $entityManager;

		/**
		 * @param \Knp\Menu\FactoryInterface  $factory
		 * @param \Doctrine\ORM\EntityManager $entityManager
		 */
		public function __construct(FactoryInterface $factory, EntityManager $entityManager)
		{
			$this->factory = $factory;
			$this->entityManager = $entityManager;
		}

		/**
		 * @param \Symfony\Component\HttpFoundation\Request $request
		 * @return array
		 */
		public function createLoginMenu(Request $request)
		{
			$menu = $this->factory->createItem('login');

			$menu->addChild('Login',    array('route' => 'fos_user_security_login'));			// fos_user_security_login
			$menu->addChild('Register', array('route' => 'fos_user_registration_register'));	// fos_user_registration_register
			return $menu;
		}

		/**
		 * @param \Poa\MainBundle\Entity\Menu $menu
		 * @return array
		 */
		public function getChildrenMenu($menu) {
			$nav = array();
			/** @var \Poa\MainBundle\Entity\Menu $menu */
			foreach($menu->getChildren() as $c) {
				$nav[] = array(
					'id'       => $c->getId(),
					'name'     => $c->getName(),
					'slug'     => $c->getSlug(),
					'children' => $this->getChildrenMenu($c)
				);
			}
			return $nav;
		}

		/**
		 * @param integer $id
		 * @return array
		 */
		public function getNavMenu($id) {
			$nav = array();

			/** @var $em \Poa\MainBundle\Entity\MenuRepository */
			$em = $this->entityManager->getRepository('PoaMainBundle:Menu');
			foreach ($em->getMenuID($id) as $menu) {
				/** @var $menu \Poa\MainBundle\Entity\Menu */
				foreach($menu->getChildren() as $c) {
					/** @var $c \Poa\MainBundle\Entity\Menu */
					$nav[] = array(
						'id'       => $c->getId(),
						'name'     => $c->getName(),
						'slug'     => $c->getSlug(),
//						'children' => $this->getChildrenMenu($c)
					);
				}
			}

			return $nav;
		}

		/**
		 * @param integer $id
		 * @param $menu_name string
		 * @return \Knp\Menu\ItemInterface
		 */
		private function buildMenu($id,$menu_name)
		{
			/** @var $menu \Knp\Menu\ItemInterface */
			$menu = $this->factory->createItem($menu_name);
			foreach ($this->getNavMenu($id) as $menuItem) {
				$menu->addChild($menuItem['name'], array('route' => $menuItem['slug']));		// _welcome
			}
			return $menu;
		}

		/**
		 * @param \Symfony\Component\HttpFoundation\Request $request
		 * @return \Knp\Menu\ItemInterface
		 */
		public function createWelcomeMenu(Request $request)
		{
			return $this->buildMenu(1,'welcome');
		}

		/**
		 * @param \Symfony\Component\HttpFoundation\Request $request
		 * @return \Knp\Menu\ItemInterface
		*/
		public function createResidentsMenu(Request $request)
		{
			return $this->buildMenu(2,'residents');
		}
	}