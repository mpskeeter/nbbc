<?php

	namespace Poa\ForumBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Validator\Constraints AS Assert;
	use Gedmo\Sluggable\Util\Urlizer;
	use Poa\UserBundle\Entity\User;

	/**
	 * Poa\ForumBundle\Entity\Content
	 *
	 * @ORM\Table(name="forum_topic")
	 * @ORM\Entity(repositoryClass="Poa\ForumBundle\Entity\TopicRepository")
	 * @ORM\HasLifecycleCallbacks()
	 */
	class Topic
	{
		/**
		 * @ORM\Id
		 * @ORM\Column(name="id", type="integer")
		 * @ORM\GeneratedValue(strategy="AUTO")
		 */
		protected $id;

		/**
		 * @ORM\Column(name="subject", type="string")
		 * @Assert\NotBlank()
		 * @Assert\MinLength(limit=4, message="Just a little too short.")
		 */
		protected $subject;

		/**
		 * @var string $slug
		 *
		 * @ORM\Column(name="slug", type="text")
		 */
		protected $slug;

		/**
		 * @var integer $numViews
		 *
		 * @ORM\Column(name="number_views", type="integer")
		 */
		protected $numViews;

		/**
		 * @var integer $numPosts
		 *
		 * @ORM\Column(name="number_posts", type="integer")
		 */
		protected $numPosts;

		/**
		 * @var boolean $isClosed
		 *
		 * @ORM\Column(name="closed", type="boolean")
		 */
		protected $isClosed;

		/**
		 * @var boolean $isPinned
		 *
		 * @ORM\Column(name="pinned", type="boolean")
		 */
		protected $isPinned;

		/**
		 * @var boolean $isBuried
		 *
		 * @ORM\Column(name="buried", type="boolean")
		 */
		protected $isBuried;

		/**
		 * @var \Datetime $createAt
		 *
		 * @ORM\Column(name="created_at", type="datetime")
		 */
		protected $createdAt;

		/**
		 * @var \Datetime $pulledAt
		 *
		 * @ORM\Column(name="pulled_at", type="datetime")
		 */
		protected $pulledAt;

		/**
		 * @ORM\ManyToOne(targetEntity="Category")
		 * @ORM\JoinColumn(name="category", referencedColumnName="id")
		 */
		protected $category;

		/**
		 * @ORM\OneToOne(targetEntity="Post")
		 * @ORM\JoinColumn(name="firstPost", referencedColumnName="id")
		 */
		protected $firstPost;

		/**
		 * @ORM\OneToOne(targetEntity="Post")
		 * @ORM\JoinColumn(name="lastPost", referencedColumnName="id")
		 */
		protected $lastPost;

		/**
		 * @ORM\ManyToOne(targetEntity="Poa\UserBundle\Entity\User")
		 * @ORM\JoinColumn(name="User", referencedColumnName="id")
		 */
		protected $author;

		public function __construct()
		{
			$this->createdAt = new \DateTime();
			$this->numViews = $this->numPosts = 0;
			$this->isClosed = $this->isPinned = $this->isBuried = false;
		}

		/**
		 * Gets the id
		 *
		 * @return integer
		 */
		public function getId()
		{
			return $this->id;
		}

		/**
		 * Sets the subject
		 *
		 * @param string $subject
		 */
		public function setSubject($subject)
		{
			$this->subject = $subject;
			$this->setSlug(Urlizer::urlize($this->getSubject()));
		}

		/**
		 * Gets the subject
		 *
		 * @return string
		 */
		public function getSubject()
		{
			return $this->subject;
		}

		/**
		 * Sets the slug
		 *
		 * @param string $slug
		 */
		public function setSlug($slug)
		{
			$this->slug = $slug;
		}

		/**
		 * Retrieves the slug
		 *
		 * @return string
		 */
		public function getSlug()
		{
			return $this->slug;
		}

		/**
		 * Gets the number of views
		 *
		 * @return integer
		 */
		public function getNumViews()
		{
			return $this->numViews;
		}

		/**
		 * Sets the number of views
		 *
		 * @param string $numViews
		 */
		public function setNumViews($numViews)
		{
			$this->numViews = \intval($numViews);
		}

		/**
		 * Increments the number of views
		 */
		public function incrementNumViews()
		{
			$this->numViews++;
		}

		/**
		 * Decrement the number of views
		 */
		public function decrementNumViews()
		{
			$this->numViews--;
		}

		/**
		 * Sets the number of posts
		 *
		 * @param integer $numPosts
		 */
		public function setNumPosts($numPosts)
		{
			$this->numPosts = \intval($numPosts);
		}

		/**
		 * Gets the number of posts
		 *
		 * @return integer
		 */
		public function getNumPosts()
		{
			return $this->numPosts;
		}

		/**
		 * Increments the number of posts
		 */
		public function incrementNumPosts()
		{
			$this->numPosts++;
		}

		/**
		 * Decrements the number of posts
		 */
		public function decrementNumPosts()
		{
			$this->numPosts--;
		}

		/**
		 * Defines whether the topic is closed or not
		 *
		 * @param boolean $isClosed
		 */
		public function setIsClosed($isClosed)
		{
			$this->isClosed = (bool) $isClosed;
		}

		/**
		 * Indicates whether the topic is closed or not
		 *
		 * @return boolean
		 */
		public function getIsClosed()
		{
			return $this->isClosed;
		}

		/**
		 * Defines whether the topic is pinned or not
		 *
		 * @param boolean $isPinned
		 */
		public function setIsPinned($isPinned)
		{
			$this->isPinned = (bool) $isPinned;
		}

		/**
		 * Indicates whether the topic is pinned or not
		 *
		 * @return boolean
		 */
		public function getIsPinned()
		{
			return $this->isPinned;
		}

		/**
		 * Defines whether the topic is buried or not
		 *
		 * @param boolean $isBuried
		 */
		public function setIsBuried($isBuried)
		{
			$this->isBuried = (bool) $isBuried;
		}

		/**
		 * Indicates whether the topic is buried or not
		 *
		 * @return boolean
		 */
		public function getIsBuried()
		{
			return $this->isBuried;
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
		 * @param \DateTime $created_at
		 */
		public function setCreatedAt(\DateTime $created_at)
		{
			$this->createdAt = $created_at;
		}

		/**
		 * Updates the pull timestamp to the latest post creation date
		 *
		 * @param \DateTime $pulled_at
		 */
		public function setPulledAt(\DateTime $pulled_at)
		{
			$this->pulledAt = $pulled_at;
		}

		/**
		 * Gets the pull timestamp
		 *
		 * @return \DateTime
		 */
		public function getPulledAt()
		{
			return $this->pulledAt;
		}

		/**
		 * Gets the first post
		 *
		 * @return Post
		 */
		public function getFirstPost()
		{
			return $this->firstPost;
		}

		/**
		 * Sets the first post
		 *
		 * @param Post
		 * @return null
		 */
		public function setFirstPost(Post $post)
		{
			$post->setTopic($this);
			$this->firstPost = $post;
		}

		/**
		 * Gets the last post
		 *
		 * @return Post
		 */
		public function getLastPost()
		{
			return $this->lastPost;
		}

		/**
		 * Sets the last post
		 *
		 * @param Post
		 * @return null
		 */
		public function setLastPost(Post $post)
		{
			$this->lastPost = $post;
		}

		/**
		 * Sets the category
		 *
		 * @param Category $category
		 */
		public function setCategory(Category $category = null)
		{
			$this->category = $category;
		}

		/**
		 * Gets the category
		 *
		 * @return Category
		 */
		public function getCategory()
		{
			return $this->category;
		}

		/**
		 * {@inheritDoc}
		 */
		public function getAuthorName()
		{
			return $this->author->getFullName();
		}

		/**
		 * set author
		 *
		 * @param $user User
		 */
		public function setAuthor(User $user)
		{
			$this->author = $user;
		}

		/**
		 * Get author
		 *
		 * @return User
		 */
		public function getAuthor()
		{
			return $this->author;
		}

		public function __toString()
		{
			return (string) $this->getSubject();
		}

		/**
		 * @ORM\PrePersist
		 */
		public function prePersist()
		{
			$this->setCreatedAt(new \DateTime());
			$this->setPulledAt(new \DateTime());
		}
	}