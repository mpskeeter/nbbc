<?php

	namespace Poa\UserBundle\Form\Type;

	use Symfony\Component\Form\FormBuilderInterface;
	use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

	class RegistrationFormType extends BaseType
	{
		public function buildForm(FormBuilderInterface $builder, array $options)
		{
			// add your custom field
			$builder
				->add('firstname', null,  array( 'label' => 'form.firstname', 'translation_domain' => 'FOSUserBundle' ))
				->add('lastname',  null,  array( 'label' => 'form.lastname',  'translation_domain' => 'FOSUserBundle' ))
				->add('address',  'text', array( 'label' => 'form.address',   'translation_domain' => 'FOSUserBundle', 'invalid_message' => 'register.empty.field' ));

			parent::buildForm($builder, $options);
		}

		public function getName()
		{
			return 'poa_user_registration';
		}
	}