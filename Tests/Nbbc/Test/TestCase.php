<?php
/**
 * @copyright	Copyright 2013, Mark Peters - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://github.com/mpskeeter/Nbbc
 */

namespace MPeters\NbbcBundle\Tests\Nbbc\Test;

use MPeters\NbbcBundle\Manager\NbbcManager;
use MPeters\NbbcBundle\DependencyInjection\NbbcExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;

class TestCase extends \PHPUnit_Framework_TestCase {

	/**
	 * NbbcManager instance.
	 *
	 * @var \MPeters\NbbcBundle\Manager\NbbcManager
	 */
	protected $manager;

	/*
	 * @var \MPeters\NbbcBundle\src\bbcode
	 */
	protected $object;

	/*
	 * @var array
	 */
	protected $config;

	protected $newline;

	/**
	 * Set up bbcode.
	 */
	protected function setUp() {
		$loader = new NbbcExtension();
		$this->config = $this->getFullConfig();
		$this->manager = new NbbcManager($this->config['config']);

		$this->object = $this->manager->get(NbbcManager::NBBC_DEFAULT);

		echo "GetIgnoreNewLines: " . $this->object->GetIgnoreNewLines() . "\n";

		$this->newline = $this->object->GetPlainMode() ? "\n" : "<br />\n";
		echo 'Newline: ' . $this->newline . "\n";
	}

	/**
	 * Strip new lines and tabs to test template files easily.
	 *
	 * @param string $string
	 * @return string
	 */
	public function clean($string) {
		if ($this->object->GetIgnoreNewLines())  {
			return $this->object->nl2br($string);
		} else {
			return str_replace(array("\t", "\r", "\n"), '', $string);
		}
	}

	/**
	 * Convert newlines to \n.
	 *
	 * @param string $string
	 * @return string
	 */
	public function nl($string) {
		if ($this->object->GetIgnoreNewLines() === false)  {
			return $string;
		} else {
			return str_replace("\r", "", $string);
		}
	}

	protected function getFullConfig()
	{
		$yaml = <<<EOF
config:
    allow_ampersand: true
    ignore_new_lines: false
    detect_urls: true
    url_targetable: 'true'
    url_target:     '_blank'
    local_img_url:  '/bundles/poamain/images'
    local_img_dir:  '/var/www/frameworks/Symfony-2.2.0/web/bundles/poamain/images'
    smileys_dir:    '/smileys'
smileys:
    dir:    '/smileys'
EOF;
		$parser = new Parser();

		$config = $parser->parse($yaml);

//		if (isset($config['smileys'])) {
//			$config['config']['smileys_enable'] = true;
//			if (isset($config['smileys']['dir'])) {
//				$config['config']['smileys_dir'] = $config['smileys']['dir'];
//			}
//			if (isset($config['smileys']['url'])) {
//				$config['config']['smileys_url'] = $config['smileys']['url'];
//			}
//		}

		return  $config;
	}

	public function performTest($tests) {

		foreach ($tests as $test) {

			if (@$test['tag_marker'] == '<') {
				$this->object->SetTagMarker('<');
				$this->object->SetAllowAmpersand(true);
				$this->object->SetIgnoreNewlines($this->config['config']['ignore_new_lines']);
			} elseif (isset($test['tag_marker'])) {
				$this->object->SetTagMarker($test['tag_marker']);
				$this->object->SetAllowAmpersand($this->config['config']['allow_ampersand']);
				$this->object->SetIgnoreNewlines($this->config['config']['ignore_new_lines']);
			} elseif (isset($test['newline_ignore'])) {
				$this->object->SetTagMarker('[');
				$this->object->SetIgnoreNewlines($test['newline_ignore']);
				$this->object->SetAllowAmpersand($this->config['config']['allow_ampersand']);
			}
			else {
				$this->object->SetTagMarker('[');
				$this->object->SetAllowAmpersand($this->config['config']['allow_ampersand']);
				$this->object->SetIgnoreNewlines($this->config['config']['ignore_new_lines']);
			}

			echo "Description: " . $test['descr'] . "\n";

			$output = $this->clean($this->object->parse($test['bbcode']));

			if (isset($test['regex'])) {
				echo "Expected: " . $this->clean($test['regex']) . "\n";
				echo "Got: " . $output . "\n\n";
				$this->assertRegExp($this->clean($test['regex']),$output);
			} elseif (isset($test['html'])) {
				echo "Expected: " . $this->clean($test['html']) . "\n";
				echo "Got: " . $output . "\n\n";
				$this->assertEquals($this->clean($test['html']),$output);
			}
		}
	}

	protected  function check_target()
	{
		if ($this->config['config']['url_targetable'] == 'true') {
			return ' target="' . $this->config['config']['url_target'] .'"';
		}
		else {
			return '';
		}
	}

	protected  function allow_ampersand()
	{
		return '&' . ($this->object->GetAllowAmpersand() ? '' : 'amp;');
	}
}