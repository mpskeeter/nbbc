<?php

namespace Nbbc\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Registration of the extension via DI.
 *
 * @author Al Ganiev <helios.ag@gmail.com>
 * @copyright 2011 Al Ganiev
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */
class NbbcExtension extends Extension
{
    /**
     * @see Symfony\Component\DependencyInjection\Extension.ExtensionInterface::load()
     * @param array                                                   $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

		$loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
		$loader->load('nbbc.xml');

		$container->setParameter('nbbc.config.allow_ampersand', isset($config['config']['allow_ampersand']) ? $config['config']['allow_ampersand'] : null);
		$container->setParameter('nbbc.config.detect_urls'    , isset($config['config']['detect_urls'])     ? $config['config']['detect_urls']     : null);
		$container->setParameter('nbbc.config.url_targetable' , isset($config['config']['url_targetable'])  ? $config['config']['url_targetable']  : null);
		$container->setParameter('nbbc.config.set_url_target' , isset($config['config']['set_url_target'])  ? $config['config']['set_url_target']  : null);
		$container->setParameter('nbbc.config.local_img_url'  , isset($config['config']['local_img_url'])   ? $config['config']['local_img_url']   : null);
		$container->setParameter('nbbc.config.local_img_dir'  , isset($config['config']['local_img_dir'])   ? $config['config']['local_img_dir']   : null);
		$container->setParameter('nbbc.rules'                 , isset($config['rules'])                     ? $config['rules']                     : null);
		$container->setParameter('nbbc.smileys.path'          , isset($config['smileys']['path'])           ? $config['smileys']['path']           : null);
	}

    /**
     * Loads the emoticon configuration.
     *
     * @param array $config A router configuration array
     * @param ContainerBuilder $container A ContainerBuilder instance
     * @param XmlFileLoader $loader An XmlFileLoader instance
     */
//    private function registerEmoticonConfiguration(array $config, ContainerBuilder $container, XmlFileLoader $loader)
//    {
//        $container->setParameter('nbbc.smileys.cache_class_prefix', $container->getParameter('kernel.name').ucfirst($container->getParameter('kernel.environment')));
//
//		$hook = $container->findDefinition('nbbc.smileys');
//		$argument = $hook->getArgument(2);
//
//		if (isset($config['path'])) {
//            $argument['path'] = $config['path'];
//            $container->setParameter('nbbc.smileys.path', $config['path']);
//        }
//
//        if (isset($config['extension'])) {
//            $argument['extension'] = $config['extension'];
//        }
//
//        $hook->replaceArgument(2, $argument);
//    }
}
