<?php
/**
 * @copyright	Copyright 2013, Mark Peters - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://github.com/mpskeeter/Nbbc
 */

namespace MPeters\NbbcBundle\Tests\Nbbc\Test;

use MPeters\NbbcBundle\Manager\NbbcManager;

class TestCase extends \PHPUnit_Framework_TestCase {

	/**
	 * NbbcManager instance.
	 *
	 * @var NbbcManager
	 */
	protected $manager;

	/*
	 * @var MPeters\NbbcBundle\Manager\NbbcManager
	 */
	protected $object;

	/**
	 * Set up bbcode.
	 */
	protected function setUp() {
		$this->manager = new NbbcManager(array());

		$this->object = $this->manager->get(NbbcManager::NBBC_DEFAULT);
	}

	/**
	 * Strip new lines and tabs to test template files easily.
	 *
	 * @param string $string
	 * @return string
	 */
	public function clean($string) {
		return str_replace(array("\t", "\r", "\n"), '', $string);
	}

	/**
	 * Convert newlines to \n.
	 *
	 * @param string $string
	 * @return string
	 */
	public function nl($string) {
		return str_replace("\r", "", $string);
	}

	public function performTest($tests) {

		foreach ($tests as $test) {

			if (@$test['tag_marker'] == '<') {
				$this->object->SetTagMarker('<');
				$this->object->SetAllowAmpersand(true);
			} elseif (isset($test['tag_marker'])) {
				$this->object->SetTagMarker($test['tag_marker']);
			}
			else {
				$this->object->SetTagMarker('[');
			}

			if (isset($test['regex'])) {
				$this->assertRegExp($this->clean($test['regex']),$this->clean($this->object->parse($test['bbcode'])));
			} elseif (isset($test['html'])) {
				$this->assertEquals($this->clean($test['html']),$this->clean($this->object->parse($test['bbcode'])));
			}
		}
	}
}