<?php

// src/Poa/MainBundle/DataFixtures/ORM/LoadMenuData.php

namespace Poa\MainBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Poa\MainBundle\Entity\Menu;

class LoadMenuData extends AbstractFixture implements OrderedFixtureInterface
{
	protected $menu_order;

	public function __construct()
	{
		$this->menu_order = 0;
	}

	public function build($name,$slug,$menu_active,$menu_depth,$role_required=null)
	{
		$menuData = new Menu();
		$menuData->setName($name);
		$menuData->setSlug($slug);
		$menuData->setMenuOrder(0);
		$menuData->setMenuActive($menu_active);
//		$menuData->setLayout();
		$menuData->setMenuDepth($menu_depth);
		if(!is_null($role_required)) {
			$menuData->setRole($role_required);
		}

		return $menuData;
	}

	/*
	 * @param $menu     Poa\MainBundle\Entity\Menu
	 * @param $parentId Poa\MainBundle\Entity\Menu
	 * @param $manager  Doctrine\Common\Persistence\ObjectManager
	 */
	public function setParentPersist(Menu $menu, Menu $parentId, ObjectManager &$manager)
	{
		$this->menu_order++;
		$menu->setMenuOrder($this->menu_order);
		$menu->setParent($parentId);
		$manager->persist($menu);
		$manager->flush();
		$this->addReference($menu->getSlug(), $menu);
	}

	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{
		$topLevel = $this->build('Welcome','',true,0);
		$manager->persist($topLevel);
		$manager->flush();
		$parentId = $topLevel;

		$subLevel = $this->build('Welcome','_welcome',true,1);
		$this->setParentPersist($subLevel,$parentId,$manager);

		$subLevel = $this->build('About Us','_about_us',true,1);
		$this->setParentPersist($subLevel,$parentId,$manager);

		$subLevel = $this->build('Amenities','_amenities',true,1);
		$this->setParentPersist($subLevel,$parentId,$manager);

		$subLevel = $this->build('Contact Us','_contact_us',true,1);
		$this->setParentPersist($subLevel,$parentId,$manager);

		$topLevel = $this->build('Residents','',true,0);
		$manager->persist($topLevel);
		$manager->flush();
		$parentId = $topLevel;

		$subLevel = $this->build('Board Members','_board_members',true,1);
		$this->setParentPersist($subLevel,$parentId,$manager);

		$subLevel = $this->build('Management','_management',true,1);
		$this->setParentPersist($subLevel,$parentId,$manager);

		$subLevel = $this->build('Committees','_committees',true,1);
		$this->setParentPersist($subLevel,$parentId,$manager);

		$subLevel = $this->build('Pool/Recreation','_recreation',true,1);
		$this->setParentPersist($subLevel,$parentId,$manager);

		$subLevel = $this->build('Documents','_documents',true,1);
		$this->setParentPersist($subLevel,$parentId,$manager);

		$subLevel = $this->build('Newsletters','_newsletters',true,1);
		$this->setParentPersist($subLevel,$parentId,$manager);

		$subLevel = $this->build('FAQS','_faqs',true,1);
		$this->setParentPersist($subLevel,$parentId,$manager);

		$subLevel = $this->build('Community Links','_community',true,1);
		$this->setParentPersist($subLevel,$parentId,$manager);

		$subLevel = $this->build('Utility Services','_utilities',true,1);
		$this->setParentPersist($subLevel,$parentId,$manager);

		$subLevel = $this->build('Facebook Posts','_facebook',true,1,'ROLE_MEMBER');
		$this->setParentPersist($subLevel,$parentId,$manager);

		$subLevel = $this->build('Resident Directory','_directory',true,1,'ROLE_MEMBER');
		$this->setParentPersist($subLevel,$parentId,$manager);

		$subLevel = $this->build('Forums','_forum',true,1);
		$this->setParentPersist($subLevel,$parentId,$manager);

		$subLevel = $this->build('Board Only','_board',true,1,'ROLE_BOARD');
		$this->setParentPersist($subLevel,$parentId,$manager);

		$manager->flush();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getOrder()
	{
		return 1; // the order in which fixtures will be loaded
	}
}