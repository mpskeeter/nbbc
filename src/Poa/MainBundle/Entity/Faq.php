<?php

	namespace Poa\MainBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;

	/**
	 * Poa\MainBundle\Entity\Content
	 *
	 * @ORM\Table(name="poa_faq")
	 * @ORM\Entity(repositoryClass="Poa\MainBundle\Entity\FaqRepository")
	 * @ORM\HasLifecycleCallbacks()
	 */
	class Faq
	{
		/**
		 * @ORM\Id
		 * @ORM\Column(name="id", type="integer")
		 * @ORM\GeneratedValue(strategy="AUTO")
		 */
		private $id;

		/**
		 * @var string $sequence
		 *
		 * @ORM\Column(name="sequence", type="integer")
		 */
		private $sequence;

		/**
		 * @var string $question
		 *
		 * @ORM\Column(name="question", type="text")
		 */
		private $question;

		/**
		 * @var string $answer
		 *
		 * @ORM\Column(name="answer", type="text")
		 */
		private $answer;

		/**
		 * @var \Datetime $created_at
		 *
		 * @ORM\Column(name="created_at", type="datetime")
		 */
		private $created_at;

		/**
		 * @var \Datetime $updated_at
		 *
		 * @ORM\Column(name="updated_at", type="datetime", nullable=true)
		 */
		private $updated_at;

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
		 * Set question
		 *
		 * @param string $question
		 */
		public function setQuestion($question)
		{
			$this->question = $question;
		}

		/**
		 * Get question
		 *
		 * @return string
		 */
		public function getQuestion()
		{
			return $this->question;
		}

		/**
		 * Set answer
		 *
		 * @param string $answer
		 */
		public function setAnswer($answer)
		{
			$this->answer = $answer;
		}

		/**
		 * Get answer
		 *
		 * @return string
		 */
		public function getAnswer()
		{
			return $this->answer;
		}

		/**
		 * Set created_at
		 *
		 * @param \Datetime $created_at
		 */
		public function setCreatedAt($created_at)
		{
			$this->created_at = $created_at;
		}

		/**
		 * Get created_at
		 *
		 * @return \Datetime
		 */
		public function getCreatedAt()
		{
			return $this->created_at;
		}

		/**
		 * Set updated_at
		 *
		 * @param \Datetime $updated_at
		 */
		public function setUpdatedAt($updated_at)
		{
			$this->updated_at = $updated_at;
		}

		/**
		 * Get updated_at
		 *
		 * @return \Datetime
		 */
		public function getUpdateAt()
		{
			return $this->updated_at;
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

		/**
		 * @ORM\PrePersist
		 */
		public function prePersist()
		{
			$this->created_at = new \DateTime('now');
		}

		/**
		 * @ORM\PreUpdate
		 */
		public function preUpdate()
		{
			$this->updated_at = new \DateTime('now');
		}

	}
