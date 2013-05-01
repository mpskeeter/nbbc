<?php

	namespace Poa\MainBundle\Entity;

	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\ORM\Mapping as ORM;
	use FM\BbcodeBundle\Decoda\Decoda;

	/**
	 * Poa\MainBundle\Entity\Content
	 *
	 * @ORM\Table(name="poa_menu_content")
	 * @ORM\Entity(repositoryClass="Poa\MainBundle\Entity\ContentRepository")
	 */
	class Content
	{
		/**
		 * @ORM\Id
		 * @ORM\Column(name="id", type="integer")
		 * @ORM\GeneratedValue(strategy="AUTO")
		 */
		private $id;

		/**
		 * @ORM\ManyToOne(targetEntity="Menu", inversedBy="content_items")
		 * @ORM\JoinColumn(name="menu_item", referencedColumnName="id")
		 */
		private $menu_item;

		/**
		 * @var integer $sequence
		 *
		 * @ORM\Column(name="sequence", type="integer")
		 */
		private $sequence;

		/**
		 * @var string $content_text
		 *
		 * @ORM\Column(name="content_text", type="text")
		 */
		private $text;

		/**
		 * @var \Datetime $active
		 *
		 * @ORM\Column(name="expires", type="datetime", nullable=true)
		 */
		private $expires;

		/**
		 * @var boolean $active
		 *
		 * @ORM\Column(name="active", type="boolean", nullable=true)
		 */
		private $active;

		public function __construct() {
		}

		/**
		 * Get id
		 *
		 * @return integer
		 */
		public function getId()
		{
			return $this->id;
		}

		/**
		 * Set menu_item
		 *
		 * @param Menu $menuItem
		 */
		public function setMenuItem($menuItem)
		{
			$this->menu_item = $menuItem;
		}

		/**
		 * Get menu_item
		 *
		 * @return Menu
		 */
		public function getMenuItem()
		{
			return $this->menu_item;
		}

		/**
		 * Set sequence
		 *
		 * @param integer $sequence
		 */
		public function setSequence($sequence)
		{
			$this->sequence = $sequence;
		}

		/**
		 * Get sequence
		 *
		 * @return integer
		 */
		public function getSequence()
		{
			return $this->sequence;
		}

		/**
		 * set content_text
		 *
		 * @param string $text
		 */
		public function setText($text)
		{
			$this->text = $text;
		}

		/**
		 * Get content_text
		 *
		 * @return string
		 */
		public function getText()
		{
			return $this->text;
		}

		/**
		 * Set expires
		 *
		 * @param $expires
		 */
		public function setExpires($expires)
		{
			$this->expires = $expires;
		}

		/**
		 * Get expires
		 *
		 * @return \Datetime
		 */
		public function getExpires()
		{
			return $this->expires;
		}

		/**
		 * Set active
		 *
		 * @param $active
		 */
		public function setActive($active)
		{
			$this->active = $active;
		}

		/**
		 * Get active
		 *
		 * @return boolean
		 */
		public function getActive()
		{
			return $this->active;
		}
	}
