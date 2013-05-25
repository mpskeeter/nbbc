<?php

	namespace Poa\UserBundle\DependencyInjection;

	use Symfony\Component\DependencyInjection\ContainerBuilder;
	use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
	use Symfony\Component\HttpKernel\DependencyInjection\Extension;
	use Symfony\Component\Config\FileLocator;

	class PoaUserExtension extends Extension
	{
		public function load(array $configs, ContainerBuilder $container)
		{
			$processor = new Processor();
			$configuration = new Configuration();
			$config = $processor->processConfiguration($configuration, $configs);

			$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
			$loader->load('services.yml');
//			$loader->load('form.yml');
//
//			$container->setParameter('poa_user.profile.form.type', $config['profile']['form']['type']);
//
		}

		public function getAlias()
		{
			return 'poa_user';
		}
	}