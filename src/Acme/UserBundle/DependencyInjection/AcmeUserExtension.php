<?php

	namespace Acme\UserBundle\DependencyInjection;

	use Symfony\Component\DependencyInjection\ContainerBuilder;
	use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
	use Symfony\Component\HttpKernel\DependencyInjection\Extension;
	use Symfony\Component\Config\FileLocator;
	use Symfony\Component\Config\Definition\Processor;

	class AcmeUserExtension extends Extension
	{
		public function load(array $configs, ContainerBuilder $container)
		{
//			$processor = new Processor();
//			$configuration = new Configuration();

//			$extension = new AcmeUserExtension();
//			$container->registerExtension($extension);
//			$container->loadFromExtension($extension->getAlias());

			$loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
			$loader->load('services.yml');

//			$config = $processor->processConfiguration($configuration, $configs);
//			$chainFactoryDef = $container->getDefinition($this->getAlias().'.registration.form.type');



//			$container->compile();
		}

		public function getAlias()
		{
			return 'acme_user';
		}
	}