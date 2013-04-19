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
				->add('text')
				->add('expires')
				->add('active')
			;
		}

		protected function configureListFields(ListMapper $listMapper)
		{
			$listMapper
				->addIdentifier('menu_item.name',  null, array('label' => 'Parent'))
				->add('sequence')
				->add('text')
				->add('expires')
				->add('active')
			;
		}

		protected function configureDatagridFilters(DatagridMapper $datagridMapper)
		{
			$datagridMapper
				->add('menu_item', 'doctrine_orm_class', array('label'     => 'Item Parent',
														 'required'  => true,
														 'class'     => 'Poa\MainBundle\Entity\Menu',
														 'property'  => 'name'))
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
													     'property'  => 'name'))
				->add('sequence',  null,           array('required' => false))
				->add('text',      'textarea',     array('required' => false))
				->add('expires',   'datetime')
				->add('active',    null)
			;
		}

	}