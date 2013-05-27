<?php
/**
 * Created by JetBrains PhpStorm.
 * User: skeeter
 * Date: 5/25/13
 * Time: 7:11 AM
 * To change this template use File | Settings | File Templates.
 */

namespace Poa\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Poa\UserBundle\Entity\Pets
 *
 * @ORM\Table(name="pets")
 * @ORM\Entity(repositoryClass="Poa\UserBundle\Entity\PetRepository")
 */
class Pet {
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
	 * @var string $type
	 *
	 * @ORM\Column(name="type", type="string", length=80)
	 */
	private $type;

	/**
	 * @var string $breed
	 *
	 * @ORM\Column(name="breed", type="string", length=80, nullable=true)
	 */
	private $breed;

	/**
	 * @var boolean $active
	 *
	 * @ORM\Column(name="active", type="boolean", nullable=true)
	 */
	private $active;

	/**
	 * @Assert\File(
	 *     maxSize="1M",
	 *     mimeTypes={"image/png", "image/jpeg", "image/pjpeg", "image/gif"}
	 * )
	 * @Vich\UploadableField(mapping="user_pet_image", fileNameProperty="imageName")
	 *
	 * @var File $image
	 */
	protected $image;

	/**
	 * @ORM\Column(type="string", length=255, name="image_name")
	 *
	 * @var string $imageName
	 */
	protected $imageName;

	/**
	 * Owning Side
	 *
	 * @ORM\ManyToMany(targetEntity="User", inversedBy="pets")
	 * @ORM\JoinTable(name="users_pets",
	 *      joinColumns={@ORM\JoinColumn(name="pet_id", referencedColumnName="id")},
	 *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
	 *      )
	 */
	private $user;

	public function __construct()
	{
		$this->user = new ArrayCollection();
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
	 * Set type
	 *
	 * @param string $type
	 */
	public function setType($type)
	{
		$this->type = $type;
	}

	/**
	 * Get type
	 *
	 * @return string
	 */
	public function getType()
	{
		return $this->type;
	}

	/**
	 * Set breed
	 *
	 * @param string $breed
	 */
	public function setBreed($breed)
	{
		$this->breed = $breed;
	}

	/**
	 * Get breed
	 *
	 * @return string
	 */
	public function getBreed()
	{
		return $this->breed;
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