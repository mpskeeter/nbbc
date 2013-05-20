<?php

namespace Poa\ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder->add('message',   'bbc_editor',          array('required'   => true,
																'label'      => 'Message',
																'attr'       => array(
																	'class'    => 'ktxtarea required',
																	'id'       => 'kbbcode-message',
																	'name'     => 'message',
																	'rows'     => '10',
																	'cols'     => '50',
																)));
//		$builder->add('message', 'textarea');
    }
    public function getName()
    {
        return 'Post';
    }
}
