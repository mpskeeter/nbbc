<?php
/**
 * @copyright	Copyright 2013, Mark Peters - http://milesj.me
 * @license		http://opensource.org/licenses/bsd-license.php - Licensed under the BSD License
 * @link		http://milesj.me/code/php/decoda
 */

namespace MPeters\NbbcBundle\Tests\Nbbc\Test;

use MPeters\NbbcBundle\Tests\Nbbc\Test\TestCase;
use \Exception;

class NbbcTest extends TestCase {

	public function testValidation() {

		$BBCodeTestSuite = Array(
			Array(
				'descr' => "Unknown tags like [foo] get ignored.",
				'bbcode' => "This is [foo]a tag[/foo].",
				'html' => "This is [foo]a tag[/foo].",
			),
			Array(
				'descr' => "Broken tags like [foo get ignored.",
				'bbcode' => "This is [foo a tag.",
				'html' => "This is [foo a tag.",
			),
			Array(
				'descr' => "Broken tags like [/foo get ignored.",
				'bbcode' => "This is [/foo a tag.",
				'html' => "This is [/foo a tag.",
			),
			Array(
				'descr' => "Broken tags like [] get ignored.",
				'bbcode' => "This is [] a tag.",
				'html' => "This is [] a tag.",
			),
			Array(
				'descr' => "Broken tags like [/  ] get ignored.",
				'bbcode' => "This is [/  ] a tag.",
				'html' => "This is [/  ] a tag.",
			),
			Array(
				'descr' => "Broken tags like [/ get ignored.",
				'bbcode' => "This is [/ a tag.",
				'html' => "This is [/ a tag.",
			),
			Array(
				'descr' => "Broken [ tags before [b]real tags[/b] don't break the real tags.",
				'bbcode' => "Broken [ tags before [b]real tags[/b] don't break the real tags.",
				'html' => "Broken [ tags before <b>real tags</b> don't break the real tags.",
			),
			Array(
				'descr' => "Broken [tags before [b]real tags[/b] don't break the real tags.",
				'bbcode' => "Broken [tags before [b]real tags[/b] don't break the real tags.",
				'html' => "Broken [tags before <b>real tags</b> don't break the real tags.",
			),
			Array(
				'descr' => "[i][b]Mis-ordered nesting[/i][/b] gets fixed.",
				'bbcode' => "[i][b]Mis-ordered nesting[/i][/b] gets fixed.",
				'html' => "<i><b>Mis-ordered nesting</b></i> gets fixed.",
			),
			Array(
				'descr' => "[url=][b]Mis-ordered nesting[/url][/b] gets fixed.",
				'bbcode' => "[url=http://www.google.com][b]Mis-ordered nesting[/url][/b] gets fixed.",
				'html' => '<a href="http://www.google.com" class="bbcode_url"' . $this->check_target() . '><b>Mis-ordered nesting</b></a> gets fixed.',
			),
			Array(
				'descr' => "[i]Unended blocks are automatically ended.",
				'bbcode' => "[i]Unended blocks are automatically ended.",
				'html' => "<i>Unended blocks are automatically ended.</i>",
			),
			Array(
				'descr' => "Unstarted blocks[/i] have their end tags ignored.",
				'bbcode' => "Unstarted blocks[/i] have their end tags ignored.",
				'html' => "Unstarted blocks[/i] have their end tags ignored.",
			),
			Array(
				'descr' => "[b]Mismatched tags[/i] are not matched to each other.",
				'bbcode' => "[b]Mismatched tags[/i] are not matched to each other.",
				'html' => "<b>Mismatched tags[/i] are not matched to each other.</b>",
			),
			Array(
				'descr' => "[center]Inlines and [b]blocks get[/b] nested correctly[/center].",
				'bbcode' => "[center]Inlines and [b]blocks get[/b] nested correctly[/center].",
				'html' => "\n<div class=\"bbcode_center\" style=\"text-align:center\">\nInlines and <b>blocks get</b> nested correctly\n</div>\n.",
			),
			Array(
				'descr' => "[b]Inlines and [center]blocks get[/center] nested correctly[/b].",
				'bbcode' => "[b]Inlines and [center]blocks get[/center] nested correctly[/b].",
				'html' => "<b>Inlines and </b>\n<div class=\"bbcode_center\" style=\"text-align:center\">\nblocks get\n</div>\nnested correctly.",
			),
			Array(
				'descr' => "BBCode is [B]case-insensitive[/b].",
				'bbcode' => "[cEnTeR][b]This[/B] is a [I]test[/i].[/CeNteR]",
				'html' => "\n<div class=\"bbcode_center\" style=\"text-align:center\">\n<b>This</b> is a <i>test</i>.\n</div>\n",
			),
			Array(
				'descr' => "Plain text gets passed through unchanged.",
				'bbcode' => "Plain text gets passed through unchanged.  b is not a tag and i is not a tag and neither is /i and neither is (b).",
				'html' => "Plain text gets passed through unchanged.  b is not a tag and i is not a tag and neither is /i and neither is (b).",
			)
		);

		$this->performTest($BBCodeTestSuite);
	}

	public function testSpecialCharacters() {

		$BBCodeTestSuite = Array(
			Array(
				'descr' => "& and < and > and \" get replaced with HTML-safe equivalents.",
				'bbcode' => "This <woo!> &\"yeah!\" 'sizzle'",
//				'html' => "This &lt;woo!&gt; &amp;&quot;yeah!&quot; 'sizzle'",
				'html' => 'This &lt;woo!&gt; ' . $this->allow_ampersand() . "&quot;yeah!&quot; 'sizzle'",
			),
//			Array(
//				'descr' => ":-) produces a smiley <img> element.",
//				'bbcode' => "This is a test of the emergency broadcasting system :-)",
//				'regex' => "/This is a test of the emergency broadcasting system <img src=\\\"smileys\\/smile.gif\\\" width=\\\"[0-9]*\\\" height=\\\"[0-9]*\\\" alt=\\\":-\\)\\\" title=\\\":-\\)\\\" class=\\\"bbcode_smiley\\\" \\/>/",
//			),
			Array(
				'descr' => "--- does *not* produce a [rule] tag.",
				'bbcode' => "This is a test of the --- emergency broadcasting system.",
				'html' => "This is a test of the --- emergency broadcasting system.",
			),
			Array(
				'descr' => "---- does *not* produce a [rule] tag.",
				'bbcode' => "This is a test of the ---- emergency broadcasting system.",
				'html' => "This is a test of the ---- emergency broadcasting system.",
			),
			Array(
				'descr' => "----- produces a [rule] tag.",
				'bbcode' => "This is a test of the ----- emergency broadcasting system.",
//				'html' => "This is a test of the\n<hr class=\"bbcode_rule\" />\nemergency broadcasting system.",
//				'html' => "This is a test of the<br />\n<hr /><br />\nemergency broadcasting system.",
				'html' => "This is a test of the<hr />emergency broadcasting system.",
			),
			Array(
				'descr' => "--------- produces a [rule] tag.",
				'bbcode' => "This is a test of the --------- emergency broadcasting system.",
//				'html' => "This is a test of the\n<hr class=\"bbcode_rule\" />\nemergency broadcasting system.",
				'html' => "This is a test of the\n<hr />\nemergency broadcasting system.",
			),
			Array(
				'descr' => "[-] does *not* produce a comment.",
				'bbcode' => "This is a test of the [- emergency broadcasting] system.",
				'html' => "This is a test of the [- emergency broadcasting] system.",
			),
			Array(
				'descr' => "[--] produces a comment.",
				'bbcode' => "This is a test of the [-- emergency broadcasting] system.",
				'html' => "This is a test of the  system.",
			),
			Array(
				'descr' => "[----] produces a comment.",
				'bbcode' => "This is a test of the [---- emergency broadcasting] system.",
				'html' => "This is a test of the  system.",
			),
			Array(
				'descr' => "[--] comments may contain - and [ and \" and ' characters.",
				'bbcode' => "This is a test of the [-- emergency - [ \" ' broadcasting] system.",
				'html' => "This is a test of the  system.",
			),
			Array(
				'descr' => "[--] comments may *not* contain newlines.",
				'bbcode' => "This is a test of the [-- emergency\n\rbroadcasting] system.",
				'html' => "This is a test of the [-- emergency<br />\nbroadcasting] system.",
			),
			Array(
				'descr' => "['] produces a comment.",
				'bbcode' => "This is a test of the ['emergency broadcasting] system.",
				'html' => "This is a test of the  system.",
			),
			Array(
				'descr' => "['] comments may contain [ and \" and ' characters.",
				'bbcode' => "This is a test of the ['emergency [ \" ' broadcasting] system.",
				'html' => "This is a test of the  system.",
			),
			Array(
				'descr' => "['] comments may *not* contain newlines.",
				'bbcode' => "This is a test of the [' emergency\n\rbroadcasting] system.",
				'html' => "This is a test of the [' emergency<br />\nbroadcasting] system.",
			),
			Array(
				'descr' => "[!-- --] produces a comment.",
				'bbcode' => "This is a test of the [!-- emergency broadcasting --] system.",
				'html' => "This is a test of the  system.",
			),
			Array(
				'descr' => "[!-- ] does *not* produce a viable comment.",
				'bbcode' => "This is a test of the [!-- emergency broadcasting ] system.",
				'html' => "This is a test of the [!-- emergency broadcasting ] system.",
			),
			Array(
				'descr' => "[!-- - -- ] [ --] produces a comment.",
				'bbcode' => "This is a test of the [!-- emergency - broadcasting -- system ] thingy --].",
				'html' => "This is a test of the .",
			),
			Array(
				'descr' => "[!-- - -- ] [ --] --] produces a comment with a --] left over.",
				'bbcode' => "This is a test of the [!-- emergency - broadcasting -- system ] thingy --] and other --] stuff.",
				'html' => "This is a test of the  and other --] stuff.",
			),
			Array(
				'descr' => "[!-- --] does not break any following tags outside it.",
				'bbcode' => "The [!-- quick brown --]fox jumps over the [b]lazy[/b] [i]dog[/i].",
				'html' => "The fox jumps over the <b>lazy</b> <i>dog</i>.",
			),
			Array(
				'descr' => "Tag marker mode '<' works correctly.",
				'bbcode' => "This is <b>a <i>test</b></i>.",
				'html' => "This is <b>a <i>test</i></b>.",
				'tag_marker' => '<',
			),
			Array(
				'descr' => "Tag marker mode '{' works correctly.",
				'bbcode' => "This is {b}a {i}test{/b}{/i}.",
				'html' => "This is <b>a <i>test</i></b>.",
				'tag_marker' => '{',
			),
			Array(
				'descr' => "Tag marker mode '(' works correctly.",
				'bbcode' => "This is (b)a (i)test(/b)(/i).",
				'html' => "This is <b>a <i>test</i></b>.",
				'tag_marker' => '(',
			),
			Array(
				'descr' => "Ampersand pass-through mode works correctly.",
				'bbcode' => "This is <b>a <i>test</b></i> &amp; some junk.",
				'html' => "This is <b>a <i>test</i></b> &amp; some junk.",
				'tag_marker' => '<',
			)
		);

		$this->performTest($BBCodeTestSuite);
	}


	public function testWhitespace() {

		$BBCodeTestSuite = Array(
			Array(
				'descr' => "Newlines get replaced with <br /> tags.",
				'bbcode' => "This\nis\r\na\n\rtest.",
				'html' => "This<br />\nis<br />\na<br />\ntest.",
			),
			Array(
				'descr' => "Newlines *don't* get replaced with <br /> tags in ignore-newline mode.",
				'bbcode' => "This\nis\r\na\n\rtest.",
				'html' => "This\nis\na\ntest.",
				'newline_ignore' => true,
			),
			Array(
				'descr' => "Space before and after newlines gets removed.",
				'bbcode' => "This \n \t is \na\n \x08test.",
				'html' => "This<br />\nis<br />\na<br />\ntest.",
			),
			Array(
				'descr' => "Whitespace doesn't matter inside tags after the tag name.",
				'bbcode' => "This [size = 4  ]is a test[/size ].",
				'html' => "This <span style=\"font-size:1.17em\">is a test</span>.",
			),
//			Array(
//				'descr' => 'Whitespace does matter inside "quotes" in tags.',
//				'bbcode' => 'This [wstest="  Courier   New  "]is a test[/wstest].',
//				'html' => 'This <span style="wstest:  Courier   New  ">is a test</span>.',
//			),
//			Array(
//				'descr' => "Whitespace does matter inside 'quotes' in tags.",
//				'bbcode' => "This [wstest='  Courier   New  ']is a test[/wstest].",
//				'html' => "This <span style=\"wstest:  Courier   New  \">is a test</span>.",
//			),
//			Array(
//				'descr' => "Whitespace is properly collapsed near block tags like [center].",
//				'bbcode' => "Not centered.    \n    \n    [center]    \n    \n    A bold stone gathers no italics.    \n    \n    [/center]    \n    \n    Not centered.",
//				'html' => "Not centered.<br />\n"
//				. "\n<div class=\"bbcode_center\" style=\"text-align:center\">\n"
//				. "<br />\n"
//				. "A bold stone gathers no italics.<br />\n"
//				. "\n</div>\n"
//				. "<br />\n"
//				. "Not centered.",
//			),
//			Array(
//				'descr' => "[code]...[/code] should strip whitespace outside it but not inside it.",
//				'bbcode' => "Not\ncode.\n"
//				. "[code]    \n\n    This is a test.    \n\n    [/code]\n"
//				. "Also not code.\n",
//				'html' => "Not<br />\ncode.\n"
//				. "<div class=\"bbcode_code\">\n"
//				. "<div class=\"bbcode_code_head\">Code:</div>\n"
//				. "<div class=\"bbcode_code_body\" style=\"white-space:pre\">\n    This is a test.    \n</div>\n"
//				. "</div>\n"
//				. "Also not code.<br />\n",
//			),
//			Array(
//				'descr' => "[list] and [*] must consume correct quantities of whitespace.",
//				'bbcode' => "[list]\n\n\t[*] One Box\n\n\t[*] Two Boxes\n\t[*] \n Three Boxes\n\n[/list]\n",
//				'html' => "\n<ul class=\"bbcode_list\">\n<br />\n<li>One Box<br />\n</li>\n<li>Two Boxes</li>\n<li><br />\nThree Boxes<br />\n</li>\n</ul>\n",
//			)
		);

		$this->performTest($BBCodeTestSuite);
	}

	public function testParse() {
		$this->assertEquals('<br />',$this->clean($this->object->parse('[br]')));
	}
}
