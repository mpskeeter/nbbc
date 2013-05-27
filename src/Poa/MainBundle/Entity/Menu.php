<?php

	namespace Poa\MainBundle\Entity;

	use Doctrine\Common\Collections\ArrayCollection;
	use Doctrine\ORM\Mapping as ORM;

	/**
	 * Poa\MainBundle\Entity\Menu
	 *
	 * @ORM\Table(name="menu")
	 * @ORM\Entity(repositoryClass="Poa\MainBundle\Entity\MenuRepository")
	 * @ORM\HasLifecycleCallbacks()
	 */
	class Menu
	{
		/**
		 * @ORM\Id
		 * @ORM\Column(name="id", type="integer")
		 * @ORM\GeneratedValue(strategy="AUTO")
		 */
		private $id;

		/**
		 * @var string $name
		 *
		 * @ORM\Column(name="name", type="string", length=80)
		 */
		private $name;

		/**
		 * @var string $slug
		 *
		 * @ORM\Column(name="slug", type="string", length=80)
		 * Column(name="slug", type="string", length=80, unique=true)
		 */
		private $slug;

		/**
		 * @var \DateTime $created_at
		 *
		 * @ORM\Column(name="created_at", type="datetime")
		 */
		private $created_at;

		/**
		 * @var \DateTime $updated_at
		 *
		 * @ORM\Column(name="updated_at", type="datetime")
		 */
		private $updated_at;

		/**
		 * @var string $status
		 *
		 * @ORM\Column(name="status", type="string", length=20, nullable=true)
		 */
		private $status;

		/**
		 * @ORM\OneToMany(targetEntity="Menu", mappedBy="parent")
		 **/
		private $children;

		/**
		 * @ORM\ManyToOne(targetEntity="Menu", inversedBy="children")
		 * @ORM\JoinColumn(name="parent", referencedColumnName="id")
		 */
		private $parent;

		/**
		 * @var integer $menu_order
		 *
		 * @ORM\Column(name="menu_order", type="integer")
		 */
		private $menu_order;

		/**
		 * @var integer $menu_depth
		 *
		 * @ORM\Column(name="menu_depth", type="integer")
		 */
		private $menu_depth;

		/**
		 * @var boolean $menu_active
		 *
		 * @ORM\Column(name="menu_active", type="boolean")
		 */
		private $menu_active;

		/**
		 * @var string $layout
		 *
		 * @ORM\Column(name="layout", type="string", length=80, nullable=true)
		 */
		private $layout;

		/**
		 * @var string $role
		 *
		 * @ORM\Column(name="role", type="string", length=120, nullable=true)
		 */
		private $role;

		/**
		 * @ORM\OneToMany(targetEntity="Content", mappedBy="menu_item")
		 **/
		private $content_items;

		public function __construct() {
			$this->menu_depth = 0;
			$this->menu_active = 0;
			$this->children = new ArrayCollection();
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
		 * Set name
		 *
		 * @param string $name
		 */
		public function setName($name)
		{
			$this->name = $name;
		}

		/**
		 * Get name
		 *
		 * @return string
		 */
		public function getName()
		{
			return $this->name;
		}

		/**
		 * Set slug
		 *
		 * @param string $slug
		 */
		public function setSlug($slug)
		{
			$this->slug = $slug;
		}

		/**
		 * Get slug
		 *
		 * @return string
		 */
		public function getSlug()
		{
			return $this->slug;
		}

		/**
		 * Set created_at
		 *
		 * @param \DateTime $createdAt
		 */
		public function setCreatedAt($createdAt)
		{
			$this->created_at = $createdAt;
		}

		/**
		 * Get created_at
		 *
		 * @return \DateTime
		 */
		public function getCreatedAt()
		{
			return $this->created_at;
		}

		/**
		 * Set updated_at
		 *
		 * @param \DateTime $updatedAt
		 */
		public function setUpdatedAt($updatedAt)
		{
			$this->updated_at = $updatedAt;
		}

		/**
		 * Get updated_at
		 *
		 * @return \DateTime
		 */
		public function getUpdatedAt()
		{
			return $this->updated_at;
		}

		/**
		 * Set status
		 *
		 * @param string $status
		 */
		public function setStatus($status)
		{
			$this->status = $status;
		}

		/**
		 * Get status
		 *
		 * @return string
		 */
		public function getStatus()
		{
			return $this->status;
		}

		/**
		 * Add children
		 *
		 * @param Menu $children
		 */
		public function addChildren(Menu $children)
		{
			$this->children[] = $children;
			$children->setParent($this);
		}

		/**
		 * Get children
		 *
		 * @return Menu[]|null
		 */
		public function getChildren()
		{
			return $this->children;
		}

		/**
		 * Set parent
		 *
		 * @param Menu $parent
		 */
		public function setParent(Menu $parent)
		{
			$this->parent = $parent;
			$parent->addChildren($this);
		}

		/**
		 * Get parent
		 *
		 * @return integer
		 */
		public function getParent()
		{
			return $this->parent;
		}

		/**
		 * Set menu_order
		 *
		 * @param integer $menuOrder
		 */
		public function setMenuOrder($menuOrder)
		{
			$this->menu_order = $menuOrder;
		}

		/**
		 * Get menu_order
		 *
		 * @return integer
		 */
		public function getMenuOrder()
		{
			return $this->menu_order;
		}

		/**
		 * Set menu_active
		 *
		 * @param boolean $menuActive
		 */
		public function setMenuActive($menuActive)
		{
			$this->menu_active = $menuActive;
		}

		/**
		 * Get menu_active
		 *
		 * @return boolean
		 */
		public function getMenuActive()
		{
			return $this->menu_active;
		}

		/**
		 * Set layout
		 *
		 * @param string $layout
		 */
		public function setLayout($layout)
		{
			$this->layout = $layout;
		}

		/**
		 * Get layout
		 *
		 * @return string
		 */
		public function getLayout()
		{
			return $this->layout;
		}

		/**
		 * Set role
		 *
		 * @param string $role
		 */
		public function setRole($role)
		{
			$this->role = $role;
		}

		/**
		 * Get role
		 *
		 * @return string
		 */
		public function getRole()
		{
			return $this->role;
		}

		/**
		 * Set menu_depth
		 *
		 * @param integer $menuDepth
		 */
		public function setMenuDepth($menuDepth)
		{
			$this->menu_depth = $menuDepth;
		}

		/**
		 * Get menu_depth
		 *
		 * @return integer
		 */
		public function getMenuDepth()
		{
			return $this->menu_depth;
		}

		/**
		 * Set content_items
		 *
		 * @param Content $content_items
		 */
		public function setContentItems(Content $content_items)
		{
			$this->content_items = $content_items;
			$content_items->setMenuItem($this);
		}

		/**
		 * Get $content_items
		 *
		 * @return integer
		 */
		public function getContent()
		{
			return $this->content_items;
		}

		public function getSpacedName()
		{
			$level = '';
			for ($i=0; $i<$this->getMenuDepth(); $i++)
			{
				$level .= '-';
			}
			return ($level <> '' ? ' '.$level.' ' : '').$this->getName();
		}

		/**
		 * @ORM\PrePersist
		 */
		public function prePersist()
		{
			$this->created_at = new \DateTime('now');
//			$this->preUpdate();
		}

		/**
		 * @ORM\PreUpdate
		 */
		public function preUpdate()
		{
			$this->updated_at = new \DateTime('now');
		}
	}