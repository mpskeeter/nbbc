<?php
// src/Acme/UserBundle/Entity/Group.php

	namespace Acme\UserBundle\Entity;

	use FOS\UserBundle\Entity\Group as BaseGroup;
	use Doctrine\ORM\Mapping as ORM;

	/**
	 * @ORM\Entity
	 * @ORM\Table(name="poa_group")
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