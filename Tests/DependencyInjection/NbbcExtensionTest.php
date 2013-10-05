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
	public function testThrowsExceptionUnlessClassSet()
	{
		$yaml = <<<EOF
models:
    test:
        controller: AcmeMyBundle:Test
EOF;
		$config = $this->getConfiguration($yaml);
	}


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
EOF;
		$parser = new Parser();

		return  $parser->parse($yaml);
	}

	protected function getConfiguration($yaml = null)
	{
		if (null === $yaml) {

			$yaml = <<<EOF
models:
    test:
        class: Lyra\AdminBundle\Tests\Fixture\Entity\Dummy
        controller: AcmeMyBundle:Test
EOF;
		}

		$parsed = $this->parseConfiguration($yaml);
		$loader = new NbbcExtension();
		$configuration = new ContainerBuilder();
		$loader->load(array($parsed), $configuration);
//		$loader->configureFromMetadata($configuration);

		return $configuration;
	}

	protected function parseConfiguration($yaml)
	{
		$parser = new Parser();

		return $parser->parse($yaml);
	}

	protected function tearDown()
	{
		unset($this->containerBuilder);
	}

}
