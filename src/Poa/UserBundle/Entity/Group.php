<?php
// src/Poa/UserBundle/Entity/Group.php

	namespace Poa\UserBundle\Entity;

	use Sonata\UserBundle\Entity\BaseGroup as BaseGroup;
	use Doctrine\ORM\Mapping as ORM;

	/**
	 * @ORM\Entity
	 * @ORM\Table(name="groups")
	 */
	class Group extends BaseGroup
	{
		/**
		 * @ORM\Id
		 * @ORM\Column(name="id", type="integer")
		 * @ORM\GeneratedValue(strategy="AUTO")
		 */
		protected $id;
	}