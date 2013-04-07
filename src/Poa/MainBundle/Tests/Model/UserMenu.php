<?php

namespace Poa\MainBundle\Tests\Model;

use Poa\MainBundle\Entity\Menu;

class UserMenu extends \PHPUnit_Framework_TestCase
{
	public function testMenuName()
	{
		$menu = $this->getMenu();
		$this->assertNull($menu->getName());
		$this->assertNull($menu->getSlug());

		$menu->setName('tony');
		$this->assertEquals('tony', $menu->getName());

		$menu->setSlug($menu->getName());
		$this->assertEquals($menu->getName(), $menu->getSlug());
	}

	public function testMenuChildren()
	{
		$menu = $this->getMenu();
		$menu->setName('tony');

		$menu1 = $this->getMenu();
		$menu1->setName('child');

		$menu->addChildren($menu1);

		$this->assertEquals('tony', $menu->getName());

	}

	protected function getMenu()
	{
		return $this->getMockForAbstractClass('Poa\MainBundle\Entity\Menu');
	}

}
