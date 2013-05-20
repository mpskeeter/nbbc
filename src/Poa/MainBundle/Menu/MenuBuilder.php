<?php
// src/Poa/MenuBundle/Menu/MenuBuilder.php

	namespace Poa\MainBundle\Menu;

	use Doctrine\ORM\EntityManager;
	use Knp\Menu\FactoryInterface;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Security\Core\SecurityContextInterface;

	class MenuBuilder
	{

		/** @var \Knp\Menu\FactoryInterface */
		private $factory;

		/** @var \Doctrine\ORM\EntityManager */
		private $entityManager;

		/** @var \Symfony\Component\Security\Core\SecurityContextInterface  */
		private $securityContext;

		/**
		 * @param \Knp\Menu\FactoryInterface  $factory
		 * @param \Doctrine\ORM\EntityManager $entityManager
		 * @param \Symfony\Component\Security\Core\SecurityContextInterface $securityContext
		 */
		public function __construct(FactoryInterface $factory, EntityManager $entityManager, SecurityContextInterface $securityContext)
		{
			$this->factory = $factory;
			$this->entityManager = $entityManager;
			$this->securityContext = $securityContext;
		}

		/**
		 * @param \Symfony\Component\HttpFoundation\Request $request
		 * @return \Knp\Menu\ItemInterface
		 */
		public function createLoginMenu(Request $request)
		{
			$menu = $this->factory->createItem('login');

			if( $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY') ) {
				if( $this->securityContext->isGranted('ROLE_SONATA_ADMIN') ) {
					$menu->addChild('Dashboard', array('route' => 'sonata_admin_dashboard'));
				}
				$menu->addChild('Logout',    array('route' => 'fos_user_security_logout'));
			}
			else
			{
				$menu->addChild('Login',    array('route' => 'fos_user_security_login'));
				$menu->addChild('Register', array('route' => 'fos_user_registration_register'));
			}
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
			/** @var $menu \Poa\MainBundle\Entity\Menu */
			foreach ($em->getMenuID($id) as $menu) {
				/** @var $c \Poa\MainBundle\Entity\Menu */
				foreach($menu->getChildren() as $c) {
					$role_required = $c->getRole();
					if (is_null($role_required) || $this->securityContext->isGranted($role_required) !== false) {

						$nav[] = array(
							'id'       => $c->getId(),
							'name'     => $c->getName(),
							'slug'     => $c->getSlug(),
//							'children' => $this->getChildrenMenu($c)
						);
					}
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
				$menu->addChild($menuItem['name'], array('route' => $menuItem['slug']));
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