<?php
	namespace Poa\AdminBundle\Admin;

	use Sonata\AdminBundle\Admin\Admin;
	use Sonata\AdminBundle\Datagrid\ListMapper;
	use Sonata\AdminBundle\Datagrid\DatagridMapper;
	use Sonata\AdminBundle\Form\FormMapper;
	use Sonata\AdminBundle\Show\ShowMapper;

	class ContentAdmin extends Admin
	{
		/**
		 * {@inheritdoc}
		 */
		protected function configureShowFields(ShowMapper $showMapper)
		{
			$showMapper
				->add('sequence')
				->add('text', 'bbcode')
				->add('expires')
				->add('active')
			;
		}

		protected function configureListFields(ListMapper $listMapper)
		{
			$listMapper
				->addIdentifier('menu_item.name',  null, array('label' => 'Parent'))
				->add('sequence')
				->add('text', NULL, array('template' => 'PoaAdminBundle:CRUD:bbcode.html.twig'))
				->add('expires')
				->add('active')
			;
		}

		protected function configureDatagridFilters(DatagridMapper $datagridMapper)
		{
			$datagridMapper
//				->add('menu_item', 'entity', array('label'     => 'Item Parent',
//				                                   'required'  => true,
//					                               'class'     => 'Poa\MainBundle\Entity\Menu',
//					                               'property'  => 'name'))
				->add('sequence')
				->add('text')
				->add('expires')
				->add('active')
			;
		}

		protected function configureFormFields(FormMapper $formMapper)
		{
			$formMapper
				->add('menu_item', 'entity',       array('label'     => 'Item Parent',
														 'required'  => true,
														 'class'     => 'Poa\MainBundle\Entity\Menu',
														 'property'  => 'spacedName'))
				->add('sequence',  null,           array('required'  => false))
				->add('text',      'bbc_editor',   array('required'  => false,
														 'label'     => 'Content',
														 'attr'      => array(
															 'class'    => 'txtarea required',
															 'name'     => 'message',
															 'id'       => 'bbcode-message',
															 'rows'     => '10',
															 'cols'     => '50',
															 'tabindex' => '2'
														 )))
				->add('expires',   'datetime',      array('required'  => false))
				->add('active',    null,            array('required'  => false))
			;
		}
	}