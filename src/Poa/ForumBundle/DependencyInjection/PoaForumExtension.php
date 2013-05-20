<?php

namespace Poa\ForumBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PoaForumExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader_xml = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
		$loader_xml->load('model.xml');
		$loader_xml->load('controller.xml');
		$loader_xml->load('form.xml');
		$loader_xml->load('blamer.xml');
		$loader_xml->load('creator.xml');
		$loader_xml->load('updater.xml');
		$loader_xml->load('remover.xml');
		$loader_xml->load('twig.xml');
		$loader_xml->load('router.xml');
//		$loader_xml->load('services.xml');

		$loader_xml->load(sprintf('%s.xml', $config['db_driver']));

		$this->loadParameters($config, $container);
	}

	private function loadParameters(array $config, ContainerBuilder $container)
	{
		foreach ($config['class'] as $groupName => $group) {
			foreach ($group as $name => $value) {
				$container->setParameter(sprintf('forum.%s.%s.class', $groupName, $name), $value);
			}
		}

		foreach ($config['form_name'] as $name => $value) {
			$container->setParameter(sprintf('forum.form.%s.name', $name), $value);
		}

		unset($config['class'], $config['form_name']);

		foreach ($config as $groupName => $group) {
			if (is_array($group)) {
				foreach ($group as $name => $value) {
					if (is_array($value)) {
						foreach ($value as $subname => $subvalue) {
							$container->setParameter(sprintf('forum.%s.%s.%s', $groupName, $name, $subname), $subvalue);
						}
					}else{
						$container->setParameter(sprintf('forum.%s.%s', $groupName, $name), $value);
					}
				}
			} else {
				$container->setParameter(sprintf('forum.%s', $groupName), $group);
			}
		}
	}
}
