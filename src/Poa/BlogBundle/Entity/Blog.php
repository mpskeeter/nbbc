<?php
// Poa/BlogBundle/Entity/Blog.php

	namespace Poa\BlogBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;

	/**
	 * @ORM\Entity
	 * @ORM\Table(name="blog")
	 * @ORM\Entity(repositoryClass="Poa\BlogBundle\Entity\BlogRepository")
	 * @ORM\HasLifecycleCallbacks()
	 */
	class Blog
	{
		/**
		 * @ORM\Id
		 * @ORM\Column(type="integer")
		 * @ORM\GeneratedValue(strategy="AUTO")
		 */
		protected $id;

		/**
		 * @ORM\Column(type="string")
		 */
		protected $title;


		/**
		 * @ORM\Column(type="string")
		 */
		protected $slug;

		/**
		 * @ORM\OneToOne(targetEntity="Poa\UserBundle\Entity\User")
		 * @ORM\JoinColumn(name="User", referencedColumnName="id")
		 */
		protected $author;

		/**
		 * @ORM\Column(type="text")
		 */
		protected $blog;

		/**
		 * @ORM\Column(type="string", length=20)
		 */
		protected $image;

		/**
		 * @ORM\Column(type="text")
		 */
		protected $tags;

//		protected $comments;

		/**
		 * @ORM\Column(name="created_at", type="datetime")
		 */
		protected $createdAt;

		/**
		 * @ORM\Column(name="updated_at", type="datetime")
		 */
		protected $updatedAt;

		public function __construct()
		{
//			$this->comments = new ArrayCollection();
//
//			$this->setCreated(new \DateTime());
//			$this->setUpdated(new \DateTime());
		}

		public function slugify($text)
		{
			// replace non letter or digits by -
			$text = preg_replace('#[^\\pL\d]+#u', '-', $text);

			// trim
			$text = trim($text, '-');

			// transliterate
			if (function_exists('iconv'))
			{
				$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
			}

			// lowercase
			$text = strtolower($text);

			// remove unwanted characters
			$text = preg_replace('#[^-\w]+#', '', $text);

			if (empty($text))
			{
				return 'n-a';
			}

			return $text;
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
		 * Set title
		 *
		 * @param string $title
		 */
		public function setTitle($title)
		{
			$this->title = $title;

			$this->setSlug($this->title);
		}

		/**
		 * Get title
		 *
		 * @return string
		 */
		public function getTitle()
		{
			return $this->title;
		}

		/**
		 * Set slug
		 *
		 * @param string $slug
		 */
		public function setSlug($slug)
		{
			$this->slug = $this->slugify($slug);
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
		 * Set author
		 *
		 * @param \Poa\UserBundle\Entity\User $author
		 */
		public function setAuthor($author)
		{
			$this->author = $author;
		}

		/**
		 * Get author
		 *
		 * @return \Poa\UserBundle\Entity\User
		 */
		public function getAuthor()
		{
			return $this->author;
		}

		/**
		 * Set blog
		 *
		 * @param string $blog
		 */
		public function setBlog($blog)
		{
			$this->blog = $blog;
		}

		/**
		 * Get blog
		 *
		 * @param null $length
		 *
		 * @return string
		 */
		public function getBlog($length = null)
		{
			if (false === is_null($length) && $length > 0)
				return substr($this->blog, 0, $length);
			else
				return $this->blog;
		}

		/**
		 * Set image
		 *
		 * @param string $image
		 */
		public function setImage($image)
		{
			$this->image = $image;
		}

		/**
		 * Get image
		 *
		 * @return string
		 */
		public function getImage()
		{
			return $this->image;
		}

		/**
		 * Set tags
		 *
		 * @param string $tags
		 */
		public function setTags($tags)
		{
			$this->tags = $tags;
		}

		/**
		 * Get tags
		 *
		 * @return string
		 */
		public function getTags()
		{
			return $this->tags;
		}

		/**
		 * Gets the creation timestamp
		 *
		 * @return \DateTime
		 */
		public function getCreatedAt()
		{
			return $this->createdAt;
		}

		/**
		 * Sets the creation timestamp
		 *
		 * @param \Datetime $created_at
		 */
		public function setCreatedAt($created_at)
		{
			$this->createdAt = $created_at;
		}

		/**
		 * Gets the updated timestamp
		 *
		 * @return \DateTime
		 */
		public function getUpdatedAt()
		{
			return $this->updatedAt;
		}

		/**
		 * Sets the updated timestamp
		 *
		 * @param \Datetime $updated_at
		 */
		public function setUpdatedAt($updated_at)
		{
			$this->updatedAt = $updated_at;
		}


		/**
		 * @ORM\PrePersist
		 */
		public function prePersist()
		{
			if (is_null($this->getCreatedAt())) {
				$this->setCreatedAt(new \DateTime());
			}
		}

		/**
		 * @ORM\PreUpdate
		 */
		public function preUpdate()
		{
			$this->setUpdatedAt(new \DateTime());
		}
	}