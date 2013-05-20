<?php

	namespace Poa\ForumBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Validator\Constraints AS Assert;
	use Poa\UserBundle\Entity\User;
//	use Poa\ForumBundle\Entity\Topic;

	/**
	 * @ORM\Table(name="forum_post")
	 * @ORM\Entity(repositoryClass="Poa\ForumBundle\Entity\PostRepository")
	 * @ORM\HasLifecycleCallbacks()
	 */
	class Post
	{
		/**
		 * @ORM\Id
		 * @ORM\Column(name="id", type="integer")
		 * @ORM\GeneratedValue(strategy="AUTO")
		 */
		protected $id;

		/**
		 * @ORM\ManyToOne(targetEntity="Topic")
		 * @ORM\JoinColumn(name="Topic", referencedColumnName="id")
		 */
		protected $topic;

		/**
		 * @var string
		 *
		 * @ORM\Column(name="message", type="text")
		 * @Assert\NotBlank(message="Please write a message")
		 * @Assert\MinLength(limit=4, message="Just a little too short.")
		 */
		protected $message;

		/**
		 * @var integer $number
		 *
		 * @ORM\Column(name="number", type="integer")
		 */
		protected $number;

		/**
		 * @var \Datetime $createAt
		 *
		 * @ORM\Column(name="created_at", type="datetime")
		 */
		protected $createdAt;

		/**
		 * @var \Datetime $updatedAt
		 *
		 * @ORM\Column(name="updated_at", type="datetime")
		 */
		protected $updatedAt;

		/**
		 * @ORM\ManyToOne(targetEntity="Poa\UserBundle\Entity\User")
		 * @ORM\JoinColumn(name="User", referencedColumnName="id")
		 */
		protected $author;

		/**
		 * @ORM\ManyToOne(targetEntity="Poa\UserBundle\Entity\User")
		 * @ORM\JoinColumn(name="modified_by", referencedColumnName="id", nullable=true)
		 */
		protected $modifiedAuthor;

		/**
		 * @var string
		 *
		 * @ORM\Column(name="modified_reason", type="text", nullable=true)
		 */
		protected $modifiedReason;

		public function __construct()
		{
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
		 * Gets the topic
		 *
		 * @return Topic
		 */
		public function getTopic()
		{
			return $this->topic;
		}

		/**
		 * Sets the topic
		 *
		 * @param Topic $topic
		 **/
		public function setTopic($topic)
		{
			$this->topic = $topic;
		}

		/**
		 * Gets the message
		 *
		 * @return string $message
		 */
		public function getMessage()
		{
			return $this->message;
		}

		/**
		 * Sets the message
		 *
		 * @param string $message
		 **/
		public function setMessage($message)
		{
			$this->message = $message;
		}

		/**
		 * Gets the number
		 *
		 * @return integer
		 */
		public function getNumber()
		{
			return $this->number;
		}

		/**
		 * Sets the number
		 *
		 * @param integer $number
		 */
		public function setNumber($number)
		{
			$this->number = $number;
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
		public function setCreatedAt(\Datetime $created_at)
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
		public function setUpdatedAt(\Datetime $updated_at)
		{
			$this->updatedAt = $updated_at;
		}

		public function getAuthorName()
		{
			return $this->author->getFullName();
		}

		public function getAuthorEmail()
		{
			return $this->author->getEmail();
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


		public function getModifiedAuthorName()
		{
			return $this->modifiedAuthor->getFullName();
		}

		/**
		 * set modifiedAuthor
		 *
		 * @param $user User
		 */
		public function setModifiedAuthor(User $user)
		{
			$this->modifiedAuthor = $user;
		}

		/**
		 * Get modifiedAuthor
		 *
		 * @return User
		 */
		public function getModifiedAuthor()
		{
			return $this->modifiedAuthor;
		}
		/**
		 * @ORM\PrePersist
		 */
		public function prePersist()
		{
			$this->setCreatedAt(new \DateTime());
			$this->setUpdatedAt(new \DateTime());
		}

		/**
		 * @ORM\PreUpdate
		 */
		public function preUpdate()
		{
			$this->setUpdatedAt(new \DateTime());
		}
	}