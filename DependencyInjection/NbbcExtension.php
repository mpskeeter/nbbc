<?php

namespace MPeters\NbbcBundle\DependencyInjection;

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

		$container->setParameter('nbbc.config.debug'            , isset($config['config']['debug'])            ? $config['config']['debug']            : null);
		$container->setParameter('nbbc.config.tag_marker'       , isset($config['config']['tag_marker'])       ? $config['config']['tag_marker']       : null);
		$container->setParameter('nbbc.config.allow_ampersand'  , isset($config['config']['allow_ampersand'])  ? $config['config']['allow_ampersand']  : null);
		$container->setParameter('nbbc.config.ignore_new_lines' , isset($config['config']['ignore_new_lines']) ? $config['config']['ignore_new_lines'] : null);
		$container->setParameter('nbbc.config.plain_mode'       , isset($config['config']['plain_mode'])       ? $config['config']['plain_mode']       : null);
		$container->setParameter('nbbc.config.limit'            , isset($config['config']['limit'])            ? $config['config']['limit']            : null);
		$container->setParameter('nbbc.config.limit_precision'  , isset($config['config']['limit_precision'])  ? $config['config']['limit_precision']  : null);
		$container->setParameter('nbbc.config.limit_tail'       , isset($config['config']['limit_tail'])       ? $config['config']['limit_tail']       : null);
		$container->setParameter('nbbc.config.pre_trim'         , isset($config['config']['pre_trim'])         ? $config['config']['pre_trim']         : null);
		$container->setParameter('nbbc.config.post_trim'        , isset($config['config']['post_trim'])        ? $config['config']['post_trim']        : null);
		$container->setParameter('nbbc.config.wiki_url'         , isset($config['config']['wiki_url'])         ? $config['config']['wiki_url']         : null);
		$container->setParameter('nbbc.config.rule_html'        , isset($config['config']['rule_html'])        ? $config['config']['rule_html']        : null);
		$container->setParameter('nbbc.config.detect_urls'      , isset($config['config']['detect_urls'])      ? $config['config']['detect_urls']      : null);
		$container->setParameter('nbbc.config.url_targetable'   , isset($config['config']['url_targetable'])   ? $config['config']['url_targetable']   : null);
		$container->setParameter('nbbc.config.url_target'       , isset($config['config']['url_target'])       ? $config['config']['url_target']       : null);
		$container->setParameter('nbbc.config.local_img_url'    , isset($config['config']['local_img_url'])    ? $config['config']['local_img_url']    : null);
		$container->setParameter('nbbc.config.local_img_dir'    , isset($config['config']['local_img_dir'])    ? $config['config']['local_img_dir']    : null);
		$container->setParameter('nbbc.rules'                   , isset($config['rules'])                      ? $config['rules']                      : null);
		$container->setParameter('nbbc.smileys.enable'          , isset($config['smileys']['enable'])          ? $config['smileys']['enable']          : null);
		$container->setParameter('nbbc.smileys.url'             , isset($config['smileys']['url'])             ? $config['smileys']['url']             : null);
		$container->setParameter('nbbc.smileys.dir'             , isset($config['smileys']['dir'])             ? $config['smileys']['dir']             : null);
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
