<?php
/**
 * @copyright	Copyright 2013, Mark Peters - http://milesj.me
 * @license		http://opensource.org/licenses/mit-license.php - Licensed under the MIT License
 * @link		http://github.com/mpskeeter/Nbbc
 */

namespace MPeters\NbbcBundle\Tests\Test;

use MPeters\NbbcBundle\src\bbcode as bbcode;

class TestCase extends \PHPUnit_Framework_TestCase {

	/**
	 * bbcode instance.
	 *
	 * @var bbcode
	 */
	protected $object;

	/**
	 * Set up bbcode.
	 */
	protected function setUp() {
		$this->object = new bbcode();
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

}