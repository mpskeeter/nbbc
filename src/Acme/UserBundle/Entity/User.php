<?php
// src/Acme/UserBundle/Entity/User.php

	namespace Acme\UserBundle\Entity;

	use FOS\UserBundle\Entity\User as BaseUser;
	use Doctrine\ORM\Mapping as ORM;
	use Symfony\Component\Validator\Constraints as Assert;

	/**
	 * @ORM\Entity
	 * @ORM\Table(name="poa_user")
	 * @ORM\HasLifecycleCallbacks()
	 */
	class User extends BaseUser
	{
		/**
		 * @ORM\Id
		 * @ORM\Column(name="id", type="integer")
		 * @ORM\GeneratedValue(strategy="AUTO")
		 */
		protected $id;

		/**
		 * @ORM\ManyToMany(targetEntity="Acme\UserBundle\Entity\Group")
		 * @ORM\JoinTable(name="poa_user_group",
		 *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
		 *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
		 * )
		 */
		protected $groups;

		/**
		 * @var string
		 *
		 * @ORM\Column(name="firstname", type="string", length=255)
		 * @Assert\NotBlank(message="Please enter your first name.", groups={"Registration", "Profile"})
		 * @Assert\MinLength(limit="3", message="The name is too short.", groups={"Registration", "Profile"})
		 * @Assert\MaxLength(limit="255", message="The name is too long.", groups={"Registration", "Profile"})
		 */
		protected $firstname;

		/**
		 * @var string
		 *
		 * @ORM\Column(name="lastname", type="string", length=255)
		 * @Assert\NotBlank(message="Please enter your last name.", groups={"Registration", "Profile"})
		 * @Assert\MinLength(limit="3", message="The name is too short.", groups={"Registration", "Profile"})
		 * @Assert\MaxLength(limit="255", message="The name is too long.", groups={"Registration", "Profile"})
		 */
		protected $lastname;

		/**
		 * @var text
		 *
		 * @ORM\Column(name="address", type="text", nullable=false)
		 * @Assert\NotBlank(message="Please enter your address.", groups={"Registration", "Profile"})
		 * @Assert\MinLength(limit="10", message="The address is too short.", groups={"Registration", "Profile"})
		 * @Assert\MaxLength(limit="255", message="The address is too long.", groups={"Registration", "Profile"})
		 */
		private $address;

		/**
		 * @var datetime $date_registered
		 *
		 * @ORM\Column(name="date_registered", type="datetime", nullable=false)
		 */
		private $date_registered;

		/**
		 * @var string
		 *
		 * @ORM\Column(name="facebookId", type="string", length=255)
		 */
		protected $facebookId;

		public function __construct()
		{
			parent::__construct();
			// your own logic
		}

		public function serialize()
		{
			return serialize(array($this->facebookId, parent::serialize()));
		}

		public function unserialize($data)
		{
			list($this->facebookId, $parentData) = unserialize($data);
			parent::unserialize($parentData);
		}

		/**
		 * @return string
		 */
		public function getFirstname()
		{
			return $this->firstname;
		}

		/**
		 * @param string $firstname
		 */
		public function setFirstname($firstname)
		{
			$this->firstname = $firstname;
		}

		/**
		 * @return string
		 */
		public function getLastname()
		{
			return $this->lastname;
		}

		/**
		 * @param string $lastname
		 */
		public function setLastname($lastname)
		{
			$this->lastname = $lastname;
		}

		/**
		 * Set address
		 *
		 * @param text $address
		 */
		public function setAddress($address)
		{
			$this->address = $address;
		}

		/**
		 * Get address
		 *
		 * @return text
		 */
		public function getAddress()
		{
			return $this->address;
		}

		/**
		 * Set date_registered
		 *
		 * @param datetime $dateRegistered
		 */
		public function setDateRegistered($dateRegistered)
		{
			$this->date_registered = $dateRegistered;
		}

		/**
		 * Get date_registered
		 *
		 * @return datetime
		 */
		public function getDateRegistered()
		{
			return $this->date_registered;
		}

		/**
		 * Get the full name of the user (first + last name)
		 * @return string
		 */
		public function getFullName()
		{
			return $this->getFirstname() . ' ' . $this->getLastname();
		}

		/**
		 * @param string $facebookId
		 * @return void
		 */
		public function setFacebookId($facebookId)
		{
			$this->facebookId = $facebookId;
			$this->setUsername($facebookId);
			$this->salt = '';
		}

		/**
		 * @return string
		 */
		public function getFacebookId()
		{
			return $this->facebookId;
		}

		/**
		 * @param Array
		 */
		public function setFBData($fbdata)
		{
			if (isset($fbdata['id'])) {
				$this->setFacebookId($fbdata['id']);
				$this->addRole('ROLE_FACEBOOK');
			}
			if (isset($fbdata['first_name'])) {
				$this->setFirstname($fbdata['first_name']);
			}
			if (isset($fbdata['last_name'])) {
				$this->setLastname($fbdata['last_name']);
			}
			if (isset($fbdata['email'])) {
				$this->setEmail($fbdata['email']);
			}
		}

		/**
		 * @ORM\PrePersist
		 */
		public function prePersist()
		{
			$this->date_registered = new \DateTime();
		}
	}