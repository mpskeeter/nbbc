<?php

namespace MPeters\NbbcBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle
 *
 * This information is solely responsible for how the different configuration
 * sections are normalized, and merged.
 * @author Al Ganiev <helios.ag@gmail.com>
 * @copyright 2013 Al Ganiev
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 */

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return \Symfony\Component\Config\Definition\Builder\TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nbbc', 'array');

        $rootNode
            ->children()
                ->arrayNode('config')
                ->canBeUnset()
                ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('allow_ampersand')
							->defaultValue(false)
						->end()
                        ->booleanNode('detect_urls')
							->defaultValue(false)
						->end()
						->scalarNode('url_targetable')
							->defaultValue('false')
						->end()
						->scalarNode('set_url_target')
							->defaultValue('false')
						->end()
						->scalarNode('local_img_url')
							->defaultValue('_blank')
						->end()
						->scalarNode('local_img_dir')->end()

						->arrayNode('smileys')
							->info('smiley configuration')
							->canBeUnset()
							->addDefaultsIfNotSet()
								->children()
									->scalarNode('path')
									->defaultValue('smileys/')
									->validate()
										->ifTrue(function($v) { return 0 !== strpos($v, '/'); })
										->then(function($v) {
											$message = sprintf(
												'The "nbbc.smileys.path" '.
												'configuration must be start with a '.
												'"/", "%s" given.',$v);

											throw new \RuntimeException($message);
										})
									->end()
								->end()
								->scalarNode('extension')->defaultValue('gif')->end()
							->end()
						->end()
                    ->end()
                ->end()

				->arrayNode('rules')
					->useAttributeAsKey('name')
					->prototype('array')
						->children()
							->integerNode('mode')->end()
							->scalarNode('template')->end()
							->scalarNode('class')->end()
							->arrayNode('allow')
								->useAttributeAsKey('name')
								->prototype('variable')->end()
							->end()
							->arrayNode('default')
								->useAttributeAsKey('name')
								->prototype('variable')->end()
							->end()
							->arrayNode('allow_in')
								->useAttributeAsKey('name')
								->prototype('variable')->end()
							->end()
						->end()
					->end()
				->end()
            ->end()
        ->end();

//        $this->addEmoticonSection($rootNode);

        return $treeBuilder;
    }

//    private function addEmoticonSection(ArrayNodeDefinition $rootNode)
//    {
//        $rootNode
//            ->children()
//                ->arrayNode('smileys')
//                    ->info('smiley configuration')
//                    ->canBeUnset()
//                    ->addDefaultsIfNotSet()
//                    ->children()
//                        ->scalarNode('path')
//                            ->defaultValue('/smileys/')
//                            ->validate()
//                                ->ifTrue(function($v) { return 0 !== strpos($v, '/'); })
//                                ->then(function($v) {
//                                    $message = sprintf(
//                                        'The "nbbcode.smileys.path" '.
//                                        'configuration must be start with a '.
//                                        '"/", "%s" given.',
//                                        $v
//                                    );
//
//                                    throw new \RuntimeException($message);
//                                })
//                            ->end()
//                        ->end()
//                        ->scalarNode('extension')->defaultValue('gif')->end()
//                    ->end()
//                ->end()
//            ->end()
//        ;
//    }
}
