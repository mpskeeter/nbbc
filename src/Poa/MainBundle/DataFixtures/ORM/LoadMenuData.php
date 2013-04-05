<?php

// src/Poa/MainBundle/DataFixtures/ORM/LoadMenuData.php

namespace Poa\MainBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Poa\MainBundle\Entity\Menu;

class LoadMenuData extends AbstractFixture implements OrderedFixtureInterface
{

	public function build($name,$slug,$menu_order,$menu_active,$menu_depth)
	{
		$menuData = new Menu();
		$menuData->setName($name);
		$menuData->setSlug($slug);
		$menuData->setMenuOrder($menu_order);
		$menuData->setMenuActive($menu_active);
//		$menuData->setLayout();
		$menuData->setMenuDepth($menu_depth);

		return $menuData;
	}

	/**
	 * {@inheritDoc}
	 */
	public function load(ObjectManager $manager)
	{
		$menuOrder = 0;

		$topLevel = $this->build('Welcome','',$menuOrder,true,0);
		$manager->persist($topLevel);
		$manager->flush();
		$parentId = $topLevel;
//		$this->addReference('Welcome-Menu-Main', $topLevel);

		$menuOrder += 1;
		$subLevel = $this->build('Welcome','_welcome',$menuOrder,true,1);
		$subLevel->setParent($parentId);
		$manager->persist($subLevel);

		$menuOrder += 1;
		$subLevel = $this->build('About Us','_about_us',$menuOrder,true,1);
		$subLevel->setParent($parentId);
		$manager->persist($subLevel);

		$menuOrder += 1;
		$subLevel = $this->build('Amenities','_amenities',$menuOrder,true,1);
		$subLevel->setParent($parentId);
		$manager->persist($subLevel);

		$menuOrder += 1;
		$subLevel = $this->build('Contact Us','_contact_us',$menuOrder,true,1);
		$subLevel->setParent($parentId);
		$manager->persist($subLevel);

		$manager->flush();

		$topLevel = $this->build('Residents','',0,true,0);
		$manager->persist($topLevel);
		$manager->flush();
		$parentId = $topLevel;
//		$this->addReference('Residents-Menu-Main', $topLevel);

		$menuOrder += 1;
		$subLevel = $this->build('Board Members','_board_members',$menuOrder,true,1);
		$subLevel->setParent($parentId);
		$manager->persist($subLevel);

		$menuOrder += 1;
		$subLevel = $this->build('Management','_management',$menuOrder,true,1);
		$subLevel->setParent($parentId);
		$manager->persist($subLevel);

		$menuOrder += 1;
		$subLevel = $this->build('Committees','_committees',$menuOrder,true,1);
		$subLevel->setParent($parentId);
		$manager->persist($subLevel);

		$menuOrder += 1;
		$subLevel = $this->build('Pool/Recreation','_recreation',$menuOrder,true,1);
		$subLevel->setParent($parentId);
		$manager->persist($subLevel);

		$menuOrder += 1;
		$subLevel = $this->build('Documents','_documents',$menuOrder,true,1);
		$subLevel->setParent($parentId);
		$manager->persist($subLevel);

		$menuOrder += 1;
		$subLevel = $this->build('Newsletters','_newsletters',$menuOrder,true,1);
		$subLevel->setParent($parentId);
		$manager->persist($subLevel);

		$menuOrder += 1;
		$subLevel = $this->build('FAQS','_faqs',$menuOrder,true,1);
		$subLevel->setParent($parentId);
		$manager->persist($subLevel);

		$menuOrder += 1;
		$subLevel = $this->build('Community Links','_community',$menuOrder,true,1);
		$subLevel->setParent($parentId);
		$manager->persist($subLevel);

		$menuOrder += 1;
		$subLevel = $this->build('Utility Services','_utilities',$menuOrder,true,1);
		$subLevel->setParent($parentId);
		$manager->persist($subLevel);

		$menuOrder += 1;
		$subLevel = $this->build('FAQS','_faqs',$menuOrder,true,1);
		$subLevel->setParent($parentId);
		$manager->persist($subLevel);

		$menuOrder += 1;
		$subLevel = $this->build('Facebook Posts','_facebook',$menuOrder,true,1);
		$subLevel->setParent($parentId);
		$manager->persist($subLevel);

		$menuOrder += 1;
		$subLevel = $this->build('Resident Directory','_residents',$menuOrder,true,1);
		$subLevel->setParent($parentId);
		$manager->persist($subLevel);

		$menuOrder += 1;
		$subLevel = $this->build('Forums','_forum',$menuOrder,true,1);
		$subLevel->setParent($parentId);
		$manager->persist($subLevel);

		$menuOrder += 1;
		$subLevel = $this->build('Board Only','_board',$menuOrder,true,1);
		$subLevel->setParent($parentId);
		$manager->persist($subLevel);

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