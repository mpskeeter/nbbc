<?php
// src/Poa/MainBundle/Menu/MenuBuilder.php

	namespace Poa\MainBundle\Menu;

	use Doctrine\ORM\EntityManager;
	use Knp\Menu\FactoryInterface;
	use Symfony\Component\HttpFoundation\Request;

	class MenuBuilder
	{
		private $factory;
		private $entityManager;

		/**
		 * @param FactoryInterface $factory
		 */
		public function __construct(FactoryInterface $factory, EntityManager $entityManager)
		{
			$this->factory = $factory;
			$this->entityManager = $entityManager;
		}

		public function createLoginMenu(Request $request)
		{
			$menu = $this->factory->createItem('login');

			$menu->addChild('Login',    array('route' => 'fos_user_security_login'));			// fos_user_security_login
			$menu->addChild('Register', array('route' => 'fos_user_registration_register'));	// fos_user_registration_register
			// ... add more children

			return $menu;
		}

		private function buildMenu($menu_name)
		{
			$em = $this->entityManager->getRepository('PoaMainBundle:Menu');
			$menus = $em->getNavMenu(ucwords($menu_name));

			$menu = $this->factory->createItem($menu_name);
			foreach ($menus as $menuItem) {
				$menu->addChild($menuItem['name'], array('route' => $menuItem['slug']));		// _welcome
			}
			return $menu;
		}

		public function createWelcomeMenu(Request $request)
		{
			return $this->buildMenu('welcome');

//			$menu = $this->factory->createItem('welcome');
//			$menu->addChild('Home',       array('route' => '_welcome'));		// _welcome
//			$menu->addChild('About Us',   array('route' => '_welcome'));		// _about_us
//			$menu->addChild('Amenities',  array('route' => '_welcome'));		// _amenities
//			$menu->addChild('Contact Us', array('route' => '_welcome'));		// _contact_us
//
//			return $menu;
		}

		public function createResidentsMenu(Request $request)
		{
			return $this->buildMenu('residents');

//			$menu = $this->factory->createItem('residents');
//			$menu->addChild('Board Members',      array('route' => '_welcome'));	// _board_members
//			$menu->addChild('Management',         array('route' => '_welcome'));	// _management
//			$menu->addChild('Committees',         array('route' => '_welcome'));	// _committees
//			$menu->addChild('Pool/Recreation',    array('route' => '_welcome'));	// _pool
//			$menu->addChild('Documents',          array('route' => '_welcome'));	// _documents
//			$menu->addChild('Newsletters',        array('route' => '_welcome'));	// _newsletters
//			$menu->addChild('FAQS',               array('route' => '_welcome'));	// _faqs
//			$menu->addChild('Community Links',    array('route' => '_welcome'));	// _community
//			$menu->addChild('Utility Services',   array('route' => '_welcome'));	// _utilities
//			$menu->addChild('Newsletters',        array('route' => '_welcome'));	// _newsletters
//
//			$menu->addChild('Facebook Posts',     array('route' => '_welcome'));	// (Group) _facebook
//			$menu->addChild('Resident Directory', array('route' => '_welcome'));	// (Group) _residents
//			$menu->addChild('Forums',             array('route' => '_welcome'));	// (Group) _forums
//			$menu->addChild('Board Only',         array('route' => '_welcome'));	// (Group) _board
//
//			return $menu;
		}
	}