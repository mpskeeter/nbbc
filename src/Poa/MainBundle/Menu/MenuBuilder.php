<?php
// src/Poa/MainBundle/Menu/MenuBuilder.php

	namespace Poa\MainBundle\Menu;

	use Knp\Menu\FactoryInterface;
	use Symfony\Component\DependencyInjection\ContainerAware;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Routing\Router;

	class MenuBuilder extends ContainerAware
	{
		private $factory;

		/**
		 * @param FactoryInterface $factory
		 */
		public function __construct(FactoryInterface $factory)
		{
			$this->factory = $factory;
		}

		public function createMainMenu(Request $request)
		{
//			$pages = $this->container->get('green_frog_cms.page_manager')->getRepository()->getNavMenu();
			$menu = $this->factory->createItem('root');

			$menu->addChild('Home', array('route' => '_welcome'));
			// ... add more children

			return $menu;
		}
	}