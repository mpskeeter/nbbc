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

/**
 * Poa\UserBundle\Entity\Pets
 *
 * @ORM\Table(name="users_pets")
 * @ORM\Entity(repositoryClass="Poa\UserBundle\Entity\PetsRepository")
 */
class Pets {
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
	 * @ORM\ManyToOne(targetEntity="Poa\MainBundle\Entity\Document")
	 * @ORM\JoinColumn(name="image", referencedColumnName="id")
	 */
	private $image_id;

	/**
	 * @ORM\ManyToOne(targetEntity="User", inversedBy="pets")
	 * @ORM\JoinColumn(name="owner", referencedColumnName="id")
	 */
	private $owner;

}