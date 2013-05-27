<?php
// src/Poa/UserBundle/Entity/User.php

	namespace Poa\UserBundle\Entity;

	use Doctrine\ORM\Mapping as ORM;
	use Doctrine\Common\Collections\ArrayCollection;
	use Sonata\UserBundle\Entity\BaseUser as BaseUser;
	use Symfony\Component\Validator\Constraints as Assert;

	/**
	 * @ORM\Entity
	 * @ORM\Table(name="users")
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
		 * @ORM\ManyToMany(targetEntity="Poa\UserBundle\Entity\Group")
		 * @ORM\JoinTable(name="user_group",
		 *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
		 *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
		 * )
		 */
		protected $groups;

		/**
		 * @ORM\Column(name="address", type="text", nullable=true)
		 */
		private $address;

		/**
		 * @ORM\Column(name="facebookAccessToken", type="string", length=255, nullable=true)
		 */
		protected $facebookAccessToken;

		/**
\		 * @ORM\Column(name="gplusAccessToken", type="string", length=255, nullable=true)
		 */
		protected $gplusAccessToken;

		/**
		 * Inverse Side
		 *
		 * @ORM\ManyToMany(targetEntity="Pet", mappedBy="user")
		 */
		protected $pets;

		public function __construct()
		{
			parent::__construct();
			$this->pets = new ArrayCollection();
		}

		public function serialize()
		{
			return serialize(array($this->facebookUid, parent::serialize()));
		}

		public function unserialize($data)
		{
			list($this->facebookUid, $parentData) = unserialize($data);
			parent::unserialize($parentData);
		}

		/**
		 * Set address
		 *
		 * @param string $address
		 */
		public function setAddress($address)
		{
			$this->address = $address;
		}

		/**
		 * Get address
		 *
		 * @return string
		 */
		public function getAddress()
		{
			return $this->address;
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
		 * Get the full name of the user (first + last name)
		 * @return string
		 */
		public function getEmail()
		{
			return $this->email;
		}

		/**
		 * @param string $facebookUid
		 * @return void
		 */
		public function setFacebookId($facebookUid)
		{
			$this->setFacebookUid($facebookUid);
			$this->setUsername($facebookUid);
			$this->salt = '';
		}

		/**
		 * @return string
		 */
		public function getFacebookId()
		{
			return $this->getFacebookUid();
		}

		/**
		 * @param string $facebookAccessToken
		 * @return void
		 */
		public function setFacebookAccessToken($facebookAccessToken)
		{
			$this->facebookAccessToken = $facebookAccessToken;
		}

		/**
		 * @return string
		 */
		public function getFacebookAccessToken()
		{
			return $this->facebookAccessToken;
		}

		/**
		 * @param Array
		 */
		public function setFBData($fbdata)
		{
			if (isset($fbdata['id'])) {
				$this->setFacebookUid($fbdata['id']);
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
		 * @param string $gplusId
		 * @return void
		 */
		public function setGplusId($gplusId)
		{
			$this->setGplusUid($gplusId);
			$this->setUsername($gplusId);
			$this->salt = '';
		}

		/**
		 * @return string
		 */
		public function getGplusId()
		{
			return $this->getGplusUid();
		}

		/**
		 * @param string $gplusAccessToken
		 * @return void
		 */
		public function setGplusAccessToken($gplusAccessToken)
		{
			$this->gplusAccessToken = $gplusAccessToken;
		}

		/**
		 * @return string
		 */
		public function getGplusAccessToken()
		{
			return $this->gplusAccessToken;
		}

		/**
		 * @param Array
		 */
		public function setGplusData($gplusData)
		{
			parent::setGplusData($gplusData);

			if (isset($gplusData['id'])) {
				$this->setGplusId($gplusData['id']);
				$this->addRole('ROLE_GPLUS');
			}
			if (isset($gplusData['first_name'])) {
				$this->setFirstname($gplusData['first_name']);
			}
			if (isset($gplusData['last_name'])) {
				$this->setLastname($gplusData['last_name']);
			}
			if (isset($gplusData['email'])) {
				$this->setEmail($gplusData['email']);
			}
		}

		/**
		 * @return string
		 */
		public function getGoogleId()
		{
			return $this->getGplusId();
		}

		/**
		 * @param string $gplusId
		 * @return void
		 */
		public function setGoogleId($gplusId)
		{
			$this->setGplusId($gplusId);
		}

		/**
		 * @param string $gplusAccessToken
		 * @return void
		 */
		public function setGoogleAccessToken($gplusAccessToken)
		{
			$this->setGplusAccessToken($gplusAccessToken);
		}

		/**
		 * @return string
		 */
		public function getGoogleAccessToken()
		{
			return $this->getGplusAccessToken();
		}

		/**
		 * @param Array
		 */
		public function setGoogleData($gplusData)
		{
			$this->setGplusData($gplusData);
		}

		/**
		 * Get pets
		 *
		 * @return Pets[]|null
		 */
		public function getPets()
		{
			return $this->pets;
		}
	}