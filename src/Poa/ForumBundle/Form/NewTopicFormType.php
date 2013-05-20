<?php

namespace Poa\ForumBundle\Form;

use Poa\ForumBundle\Entity\CategoryRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class NewTopicFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('subject',    null,                 array('required'    => true,
																'label'       => 'Subject',
																'attr'        => array(
																	'class'      => 'kinputbox postinput required invalid',
																	'name'       => 'subject',
																	'id'         => 'subject',
																	'size'       => '35',
																	'maxlength'  => '50',
																	'tabindex'   => '1',
																	'required'   => 'required'
																)));
        $builder->add('category');
        $builder->add('firstPost', $options['post_form'], array('data_class' => $options['post_class']));
    }

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
			'post_class'    => '',
			'post_form'     => '',
			'data_class'    => '',
		));
	}

	public function getName()
    {
        return 'NewTopic';
    }
}
