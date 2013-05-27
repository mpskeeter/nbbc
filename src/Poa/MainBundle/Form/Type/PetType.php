<?php
// src/Poa/MainBundle/Form/Type/GenderType.php
namespace Poa\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PetType extends AbstractType
{
	private $petChoices;

	public function __construct(array $petChoices)
	{
		$this->petChoices = $petChoices;
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'choices' => $this->petChoices,
		));
	}

	public function getParent()
	{
		return 'choice';
	}

	public function getName()
	{
		return 'pet';
	}
}