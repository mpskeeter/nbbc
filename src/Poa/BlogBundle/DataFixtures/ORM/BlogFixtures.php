<?php
/**
 * Created by JetBrains PhpStorm.
 * User: skeeter
 * Date: 5/7/13
 * Time: 10:20 PM
 * To change this template use File | Settings | File Templates.
 */
// Poa/BlogBundle/DataFixtures/ORM/BlogFixtures

	namespace Poa\BlogBundle\DataFixtures\ORM;

	use Doctrine\Common\DataFixtures\FixtureInterface;
	use Doctrine\Common\Persistence\ObjectManager;
	use Poa\BlogBundle\Entity\Blog;

	class BlogFixtures implements FixtureInterface {

		public function load(ObjectManager $manager)
		{
			$blog1 = new Blog();
			$blog1->setTitle('2005 Fall Newsletter');
			$blog1->setBlog('We hope that you are enjoying living in Ballard Woods. We feel, as I hope you do that Ballard Woods is a premier community in the Harnett County region. That is only achieved by having homeowners such as you that take pride in their community.[br]
Please be aware of some issues that are coming up or have arisen regarding Ballard Woods.[br]
As you may have seen, Phase II is well under way with several new homeowners. Many houses are under construction in this area and there is much congestion. Please be careful as you travel through here. Please be aware that each house is a construction site and we must ask you and any children stay out of the houses. There are many dangers located within a construction site.[br]
We are also near completion of Phase III. We are waiting for the final soils to be surveyed. Once we have that report we will be able to turn the map in to Harnett County for final approval.[br]
We have received several complaints from some homeowners concerning the appearance of some of the lots. Please read your Restrictive Covenants and be aware of parking vehicles in and around your yard. There are also some concerns over fences and storage buildings. The Architectural Review Committee must approve these prior to construction.[br]
The recreational area has a new playground. We hope that you are enjoying the new equipment and that you will supervise your children and help patrol the area for trash and help keep the wash stone contained within the play area.[br]
The pool will be officially closing for swimming the end of September. The deck and pool areas are still available during the off-season for sunning, parties and gatherings.[br]
If you should have any questions or concerns, please feel free to call our office at 919.833.5526.
');
			$blog1->setImage(null);
			$blog1->setAuthor(2);
			$blog1->setTags('newsletter');
			$blog1->setCreatedAt(new \DateTime('2005-08-15 09:19:02'));
			$blog1->setUpdatedAt($blog1->getCreatedAt());
			$manager->persist($blog1);

			$manager->flush();
		}

		public function getOrder()
		{
			return 4; // the order in which fixtures will be loaded
		}

	}