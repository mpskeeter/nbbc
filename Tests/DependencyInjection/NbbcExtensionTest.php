<?php

namespace MPeters\NbbcBundle\Tests\DependencyInjection;

use MPeters\NbbcBundle\DependencyInjection\NbbcExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

class NbbcExtensionTest extends \PHPUnit_Framework_TestCase
{
	protected $configuration;

	/**
	 * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
	 */
	public function testUserLoadThrowsExceptionUnlessDatabaseDriverSet()
	{
		$loader = new NbbcExtension();
		$config = $this->getEmptyConfig();
		unset($config['db_driver']);
		$loader->load(array($config), new ContainerBuilder());
	}

	/**
	 * getEmptyConfig
	 *
	 * @return array
	 */
	protected function getEmptyConfig()
	{
		$yaml = <<<EOF
db_driver: orm
firewall_name: fos_user
user_class: Acme\MyBundle\Document\User
EOF;
		$parser = new Parser();

		return $parser->parse($yaml);
	}

	protected function getFullConfig()
	{
		$yaml = <<<EOF
nbbc:
    config:
        allow_ampersand: true
        detect_urls: true
        url_targetable: 'true'
        url_target:     '_blank'
        local_img_url:  '/bundles/poamain/images'
        local_img_dir:  '/var/www/frameworks/Symfony-2.2.0/web/bundles/poamain/images'
    smileys:
        dir: '/smileys'
    rules:
#        imgsize:
#            mode: 4
#            template: '<img width="{$width}" height="{$height}" src="{$_content}" />'
#            class: 'block'
#            allow:
#                width:  '/^[1-9][0-9]*$/'
#                height: '/^[1-9][0-9]*$/'
#            default:
#                width:  '501'
#                height: '291'
#            allow_in: [ 'listitem', 'block', 'columns', 'inline', 'link' ]
        anchor:
            mode: 4
            template: '<div id="{$name}">{$_content}</div>'
            class: 'block'
            allow:
                name: '/^[a-zA-Z0-9._ -]+$/'
            allow_in: [ 'listitem', 'block', 'columns', 'inline' ]
        goto:
            mode: 4
            template: '<a href="#{$name}">{$_content}</a>'
            class: 'block'
            allow:
                name: '/^[a-zA-Z0-9._ -]+$/'
            allow_in: [ 'listitem', 'block', 'columns', 'inline' ]
        line:
            mode: 4
            template: '<div style="width:90%;overflow:auto; zoom:1">{$_content}</div>'
            class: 'block'
            allow_in: [ 'list', 'listitem', 'block', 'columns', 'inline' ]
        lineleft:
            mode: 4
            template: '<div style="margin:0; text-align:left; float:left;">{$_content}</div>'
            class: 'block'
            allow_in: [ 'list', 'listitem', 'block', 'columns', 'inline' ]
        lineright:
            mode: 4
            template: '<div style="margin:0; text-align:right; float:left;">{$_content}</div>'
            class: 'block'
            allow_in: [ 'list', 'listitem', 'block', 'columns', 'inline' ]
EOF;
		$parser = new Parser();

		return  $parser->parse($yaml);
	}



	protected function tearDown()
	{
		unset($this->containerBuilder);
	}

}
