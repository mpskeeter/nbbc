<?php
	namespace Poa\AdminBundle\Admin;

	use Sonata\AdminBundle\Admin\Admin;
	use Sonata\AdminBundle\Datagrid\ListMapper;
	use Sonata\AdminBundle\Datagrid\DatagridMapper;
	use Sonata\AdminBundle\Form\FormMapper;
	use Sonata\AdminBundle\Show\ShowMapper;

	class MenuAdmin extends Admin
	{
		/**
		 * {@inheritdoc}
		 */
		protected function configureShowFields(ShowMapper $showMapper)
		{
			$showMapper
				->add('name')
				->add('slug')
				->add('created_at')
				->add('updated_at')
//				->add('status')
//				->add('parent')
//				->add('menu_order')
//				->add('menu_depth')
//				->add('menu_active')
//				->add('layout')
			;
		}

		protected function configureListFields(ListMapper $listMapper)
		{
			$listMapper
				->addIdentifier('name')
				->add('slug',         null, array('label' => 'Route'))
//				->add('created_at',   null, array('label'=>'Create Date'))
//				->add('updated_at',   null, array('label'=>'Update Date'))
//				->add('status')
				->add('parent.name',  null, array('label' => 'Parent'))
				->add('menu_order',   null, array('label' => 'Order'))
				->add('menu_depth',   null, array('label' => 'Depth'))
				->add('menu_active',  null, array('label' => 'Active'))
//				->add('layout')
			;
		}

		protected function configureDatagridFilters(DatagridMapper $datagridMapper)
		{
			$datagridMapper
				->add('name')
				->add('slug',         null, array('label '=> 'Route'))
//				->add('created_at')
//				->add('updated_at')
//				->add('status')
//				->add('parent', 'targetEntity')
				->add('menu_order',   null, array('label' => 'Order'))
				->add('menu_depth',   null, array('label' => 'Depth'))
				->add('menu_active',  null, array('label' => 'Active'))
//				->add('layout')
			;
		}

		protected function configureFormFields(FormMapper $formMapper)
		{
			$formMapper
				->add('name',        null,       array('required' => true))
				->add('slug',        null,       array('required' => false,
													   'label'    => 'Route'))
				->add('status',      null,       array('required' => false))
				->add('parent',      'entity',   array('label'     => 'Parent',
													   'by_reference' => true,
													   'required'  => false,
													   'class'     => 'Poa\MainBundle\Entity\Menu',
													   'property'  => 'name'))
//													   'property'  => 'hierarchy_name'))
				->add('menu_order',  null,       array('required' => false,
													   'label'    => 'Order'))
				->add('menu_depth',  null,       array('required' => false,
													   'label'    => 'Depth'))
				->add('menu_active', 'checkbox', array('required' => false,
													   'label'    => 'Active'))
				->add('layout',      null,       array('required' => false))
			;
		}
	}