<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Poa\UserBundle\Form\Type;

//use Symfony\Component\Form\AbstractType;
use Sonata\UserBundle\Form\Type\ProfileType AS AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileType extends AbstractType
{
    private $class;

    /**
     * @param string $class The User class name
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender',      'gender',   array('required' => false,
												   'label'    => 'Gender',
												   'empty_value' => 'Choose a gender'))
            ->add('firstname',    null,      array('required' => false,
												   'label'    => 'Firstname'))
            ->add('lastname',     null,      array('required' => false,
												   'label'    => 'Lastname'))
//            ->add('dateOfBirth', 'birthday', array('required' => false))
			->add('dateOfBirth', 'date',     array('required' => false,
												   'label'    => 'Date of Birth',
												   'years'    => range(date('Y')-75, date('Y'))))
//            ->add('website',      null,      array('required' => false))
//            ->add('biography',   'textarea', array('required' => false))
            ->add('locale',      'locale',   array('required' => false))
            ->add('timezone',    'timezone', array('required' => false))
			->add('address',      null,      array('required' => false,
												   'label'    => 'Address'))
            ->add('phone',        null,      array('required' => false,
												   'label'    => 'Phone Number'))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'poa_user_profile';
    }
}
