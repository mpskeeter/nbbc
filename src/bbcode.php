<?php
/*
This is a compressed copy of NBBC. Do not edit!

Copyright (c) 2008-9, the Phantom Inker.  All rights reserved.
Portions Copyright (c) 2004-2008 AddedBytes.com

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions
are met:

* Redistributions of source code must retain the above copyright
  notice, this list of conditions and the following disclaimer.

* Redistributions in binary form must reproduce the above copyright
  notice, this list of conditions and the following disclaimer in
  the documentation and/or other materials provided with the
  distribution.

THIS SOFTWARE IS PROVIDED BY THE PHANTOM INKER "AS IS" AND ANY EXPRESS
OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE
LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR
BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE
OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN
IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

	namespace MPeters\NbbcBundle\src;

	define("BBCODE_VERSION", "1.4.5");
	define("BBCODE_RELEASE", "2010-09-17");
	define("BBCODE_VERBATIM", 2);
	define("BBCODE_REQUIRED", 1);
	define("BBCODE_OPTIONAL", 0);
	define("BBCODE_PROHIBIT", -1);
	define("BBCODE_CHECK", 1);
	define("BBCODE_OUTPUT", 2);
	define("BBCODE_ENDTAG", 5);
	define("BBCODE_TAG", 4);
	define("BBCODE_TEXT", 3);
	define("BBCODE_NL", 2);
	define("BBCODE_WS", 1);
	define("BBCODE_EOI", 0);
	define("BBCODE_LEXSTATE_TEXT", 0);
	define("BBCODE_LEXSTATE_TAG", 1);
	define("BBCODE_MODE_SIMPLE", 0);
	define("BBCODE_MODE_CALLBACK", 1);
	define("BBCODE_MODE_INTERNAL", 2);
	define("BBCODE_MODE_LIBRARY", 3);
	define("BBCODE_MODE_ENHANCED", 4);
	define("BBCODE_STACK_TOKEN", 0);
	define("BBCODE_STACK_TEXT", 1);
	define("BBCODE_STACK_TAG", 2);
	define("BBCODE_STACK_CLASS", 3);

	if (!function_exists('str_split')) {
		function str_split($string, $split_length = 1)
		{
			$array = explode("\r\n", chunk_split($string, $split_length));
			array_pop($array);
			return $array;
		}
	}

	use MPeters\NbbcBundle\src\BBCodeLexer;
	use MPeters\NbbcBundle\src\BBCodeLibrary;
	use MPeters\NbbcBundle\src\BBCodeEmailAddressValidator;

	class bbcode
	{
		var $tag_rules;
		var $defaults;
		var $current_class;
		var $root_class;
		var $lost_start_tags;
		var $start_tags;
		var $allow_ampersand;
		var $tag_marker;
		var $ignore_newlines;
		var $plain_mode;
		var $detect_urls;
		var $url_pattern;
		var $output_limit;
		var $text_length;
		var $was_limited;
		var $limit_tail;
		var $limit_precision;
		var $smiley_dir;
		var $smiley_url;
		var $smileys;
		var $smiley_regex;
		var $enable_smileys;
		var $wiki_url;
		var $local_img_dir;
		var $local_img_url;
		var $url_targetable;
		var $url_target;
		var $rule_html;
		var $pre_trim;
		var $post_trim;
		var $debug;

		var $smiley_info;
		var $stack;
		var $lexer;

		function __construct()
		{
			$this->defaults         = new BBCodeLibrary;
			$this->tag_rules        = $this->defaults->default_tag_rules;
			$this->smileys          = $this->defaults->default_smileys;
			$this->enable_smileys   = true;
			$this->smiley_regex     = false;
			$this->smiley_dir       = $this->GetDefaultSmileyDir();
			$this->smiley_url       = $this->GetDefaultSmileyURL();
			$this->wiki_url         = $this->GetDefaultWikiURL();
			$this->local_img_dir    = $this->GetDefaultLocalImgDir();
			$this->local_img_url    = $this->GetDefaultLocalImgURL();
			$this->rule_html        = $this->GetDefaultRuleHTML();
			$this->pre_trim         = "";
			$this->post_trim        = "";
			$this->root_class       = 'block';
			$this->lost_start_tags  = Array();
			$this->start_tags       = Array();
			$this->tag_marker       = '[';
			$this->allow_ampersand  = false;
			$this->current_class    = $this->root_class;
			$this->debug            = false;
//			$this->debug            = true;
			$this->ignore_newlines  = false;
			$this->output_limit     = 0;
			$this->plain_mode       = false;
			$this->was_limited      = false;
			$this->limit_tail       = "...";
			$this->limit_precision  = 0.15;
			$this->detect_urls      = false;
			$this->url_pattern      = '<a href="{$url/h}">{$text/h}</a>';
			$this->url_targetable   = false;
			$this->url_target       = false;

			$this->smiley_info      = array();
			$this->stack            = array();
		}

		function SetPreTrim($trim = "a")
		{
			$this->pre_trim = $trim;
		}

		function GetPreTrim()
		{
			return $this->pre_trim;
		}

		function SetPostTrim($trim = "a")
		{
			$this->post_trim = $trim;
		}

		function GetPostTrim()
		{
			return $this->post_trim;
		}

		function SetRoot($class = 'block')
		{
			$this->root_class = $class;
		}

		function SetRootInline()
		{
			$this->root_class = 'inline';
		}

		function SetRootBlock()
		{
			$this->root_class = 'block';
		}

		function GetRoot()
		{
			return $this->root_class;
		}

		function SetDebug($enable = true)
		{
			$this->debug = $enable;
		}

		function GetDebug()
		{
			return $this->debug;
		}

		function SetAllowAmpersand($enable = true)
		{
			$this->allow_ampersand = $enable;
		}

		function GetAllowAmpersand()
		{
			return $this->allow_ampersand;
		}

		function SetTagMarker($marker = '[')
		{
			$this->tag_marker = $marker;
		}

		function GetTagMarker()
		{
			return $this->tag_marker;
		}

		function SetIgnoreNewlines($ignore = true)
		{
			$this->ignore_newlines = $ignore;
		}

		function GetIgnoreNewlines()
		{
			return $this->ignore_newlines;
		}

		function SetLimit($limit = 0)
		{
			$this->output_limit = $limit;
		}

		function GetLimit()
		{
			return $this->output_limit;
		}

		function SetLimitTail($tail = "...")
		{
			$this->limit_tail = $tail;
		}

		function GetLimitTail()
		{
			return $this->limit_tail;
		}

		function SetLimitPrecision($prec = 0.15)
		{
			$this->limit_precision = $prec;
		}

		function GetLimitPrecision()
		{
			return $this->limit_precision;
		}

		function WasLimited()
		{
			return $this->was_limited;
		}

		function SetPlainMode($enable = true)
		{
			$this->plain_mode = $enable;
		}

		function GetPlainMode()
		{
			return $this->plain_mode;
		}

		function SetDetectURLs($enable = true)
		{
			$this->detect_urls = $enable;
		}

		function GetDetectURLs()
		{
			return $this->detect_urls;
		}

		function SetURLPattern($pattern)
		{
			$this->url_pattern = $pattern;
		}

		function GetURLPattern()
		{
			return $this->url_pattern;
		}

		function SetURLTargetable($enable)
		{
			$this->url_targetable = $enable;
		}

		function GetURLTargetable()
		{
			return $this->url_targetable;
		}

		function SetURLTarget($target)
		{
			$this->url_target = $target;
		}

		function GetURLTarget()
		{
			return $this->url_target;
		}

		function AddRule($name, $rule)
		{
			$this->tag_rules[$name] = $rule;
		}

		function RemoveRule($name)
		{
			unset($this->tag_rules[$name]);
		}

		function GetRule($name)
		{
			return isset($this->tag_rules[$name])
				? $this->tag_rules[$name] : false;
		}

		function ClearRules()
		{
			$this->tag_rules = Array();
		}

		function GetDefaultRule($name)
		{
			return isset($this->defaults->default_tag_rules[$name])
				? $this->defaults->default_tag_rules[$name] : false;
		}

		function SetDefaultRule($name)
		{
			if (isset($this->defaults->default_tag_rules[$name]))
				$this->AddRule($name, $this->defaults->default_tag_rules[$name]);
			else $this->RemoveRule($name);
		}

		function GetDefaultRules()
		{
			return $this->defaults->default_tag_rules;
		}

		function SetDefaultRules()
		{
			$this->tag_rules = $this->defaults->default_tag_rules;
		}

		function SetWikiURL($url)
		{
			$this->wiki_url = $url;
		}

		function GetWikiURL($url)
		{
			return $this->wiki_url;
		}

		function GetDefaultWikiURL()
		{
			return '/?page=';
		}

		function SetLocalImgDir($path)
		{
			$this->local_img_dir = $path;
		}

		function GetLocalImgDir()
		{
			return $this->local_img_dir;
		}

		function GetDefaultLocalImgDir()
		{
			return "img";
		}

		function SetLocalImgURL($path)
		{
			$this->local_img_url = $path;
		}

		function GetLocalImgURL()
		{
			return $this->local_img_url;
		}

		function GetDefaultLocalImgURL()
		{
			return "img";
		}

		function SetRuleHTML($html)
		{
			$this->rule_html = $html;
		}

		function GetRuleHTML()
		{
			return $this->rule_html;
		}

		function GetDefaultRuleHTML()
		{
			return "\n<hr class=\"bbcode_rule\" />\n";
		}

		function AddSmiley($code, $image)
		{
			$this->smileys[$code] = $image;
			$this->smiley_regex   = false;
		}

		function RemoveSmiley($code)
		{
			unset($this->smileys[$code]);
			$this->smiley_regex = false;
		}

		function GetSmiley($code)
		{
			return isset($this->smileys[$code])
				? $this->smileys[$code] : false;
		}

		function ClearSmileys()
		{
			$this->smileys      = Array();
			$this->smiley_regex = false;
		}

		function GetDefaultSmiley($code)
		{
			return isset($this->defaults->default_smileys[$code])
				? $this->defaults->default_smileys[$code] : false;
		}

		function SetDefaultSmiley($code)
		{
			$this->smileys[$code] = @$this->defaults->default_smileys[$code];
			$this->smiley_regex   = false;
		}

		function GetDefaultSmileys()
		{
			return $this->defaults->default_smileys;
		}

		function SetDefaultSmileys()
		{
			$this->smileys      = $this->defaults->default_smileys;
			$this->smiley_regex = false;
		}

		function SetSmileyDir($path)
		{
			$this->smiley_dir = $path;
		}

		function GetSmileyDir()
		{
			return $this->smiley_dir;
		}

		function GetDefaultSmileyDir()
		{
			return "smileys";
		}

		function SetSmileyURL($path)
		{
			$this->smiley_url = $path;
		}

		function GetSmileyURL()
		{
			return $this->smiley_url;
		}

		function GetDefaultSmileyURL()
		{
			return "smileys";
		}

		function SetEnableSmileys($enable = true)
		{
			$this->enable_smileys = $enable;
		}

		function GetEnableSmileys()
		{
			return $this->enable_smileys;
		}

		function nl2br($string)
		{
			return preg_replace("/\\x0A|\\x0D|\\x0A\\x0D|\\x0D\\x0A/", "<br />\n", $string);
		}

		function UnHTMLEncode($string)
		{
			if (function_exists("html_entity_decode"))
				return html_entity_decode($string);
			$string    = preg_replace('~&#x([0-9a-f]+);~ei', 'chr(hexdec("\\1"))', $string);
			$string    = preg_replace('~&#([0-9]+);~e', 'chr("\\1")', $string);
			$trans_tbl = get_html_translation_table(HTML_ENTITIES);
			$trans_tbl = array_flip($trans_tbl);
			return strtr($string, $trans_tbl);
		}

		function Wikify($string)
		{
			return rawurlencode(str_replace(" ", "_",
				trim(preg_replace("/[!?;@#\$%\\^&*<>=+`~\\x00-\\x20_-]+/", " ", $string))));
		}

		function IsValidURL($string, $email_too = true)
		{
			if (preg_match("/^
	(?:https?|ftp):\\/\\/
	(?:
	(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\\.)+
	[a-zA-Z0-9]
	(?:[a-zA-Z0-9-]*[a-zA-Z0-9])?
	|
	\\[
	(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}
	(?:
	25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-zA-Z0-9-]*[a-zA-Z0-9]:
	(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21-\\x5A\\x53-\\x7F]
	|\\\\[\\x01-\\x09\\x0B\\x0C\\x0E-\\x7F])+
	)
	\\]
	)
	(?::[0-9]{1,5})?
	(?:[\\/\\?\\#][^\\n\\r]*)?
	$/Dx", $string)
			) return true;
			if (preg_match("/^[^:]+([\\/\\\\?#][^\\r\\n]*)?$/D", $string))
				return true;
			if ($email_too)
				if (substr($string, 0, 7) == "mailto:")
					return $this->IsValidEmail(substr($string, 7));
			return false;
		}

		function IsValidEmail($string)
		{
			$validator = new BBCodeEmailAddressValidator;
			return $validator->check_email_address($string);
			/*
			return preg_match("/^
			(?:
			[a-z0-9\\!\\#\\\$\\%\\&\\'\\*\\+\\/=\\?\\^_`\\{\\|\\}~-]+
			(?:\.[a-z0-9\\!\\#\\\$\\%\\&\\'\\*\\+\\/=\\?\\^_`\\{\\|\\}~-]+)*
			|
			\"(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]
			|\\\\[\\x01-\\x09\\x0B\\x0C\\x0E-\\x7F])*\"
			)
			@
			(?:
			(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+
			[a-z0-9]
			(?:[a-z0-9-]*[a-z0-9])?
			|
			\\[
			(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\.){3}
			(?:
			25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:
			(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21-\\x5A\\x53-\\x7F]
			|\\\\[\\x01-\\x09\\x0B\\x0C\\x0E-\\x7F])+
			)
			\\]
			)
			$/Dx", $string);
			*/
		}

		function HTMLEncode($string)
		{
			if (!$this->allow_ampersand)
				return htmlspecialchars($string);
			else return str_replace(Array( '<', '>', '"' ),
				Array( '&lt;', '&gt;', '&quot;' ), $string);
		}

		function FixupOutput($string)
		{
			if (!$this->detect_urls) {
				$output = $this->Internal_ProcessSmileys($string);
			} else {
				$chunks = $this->Internal_AutoDetectURLs($string);
				$output = Array();
				if (count($chunks)) {
					$is_a_url = false;
					foreach ($chunks as $index => $chunk) {
						if (!$is_a_url) {
							$chunk = $this->Internal_ProcessSmileys($chunk);
						}
						$output[] = $chunk;
						$is_a_url = !$is_a_url;
					}
				}
				$output = implode("", $output);
			}
			return $output;
		}

		function Internal_ProcessSmileys($string)
		{
			if (!$this->enable_smileys || $this->plain_mode) {
				$output = $this->HTMLEncode($string);
			} else {
				if ($this->smiley_regex === false) {
					$this->Internal_RebuildSmileys();
				}
				$tokens = preg_split($this->smiley_regex, $string, -1, PREG_SPLIT_DELIM_CAPTURE);
				if (count($tokens) <= 1) {
					$output = $this->HTMLEncode($string);
				} else {
					$output      = "";
					$is_a_smiley = false;
					foreach ($tokens as $token) {
						if (!$is_a_smiley) {
							$output .= $this->HTMLEncode($token);
						} else {
							if (isset($this->smiley_info[$token])) {
								$info = $this->smiley_info[$token];
							} else {
								$info                      = @getimagesize($this->smiley_dir . '/' . $this->smileys[$token]);
								$this->smiley_info[$token] = $info;
							}
							$alt = htmlspecialchars($token);
							$output .= "<img src=\"" . htmlspecialchars($this->smiley_url . '/' . $this->smileys[$token])
								. "\" width=\"{$info[0]}\" height=\"{$info[1]}\""
								. " alt=\"$alt\" title=\"$alt\" class=\"bbcode_smiley\" />";
						}
						$is_a_smiley = !$is_a_smiley;
					}
				}
			}
			return $output;
		}

		function Internal_RebuildSmileys()
		{
			$regex = Array( "/(?<![\\w])(" );
			$first = true;
			foreach ($this->smileys as $code => $filename) {
				if (!$first) $regex[] = "|";
				$regex[] = preg_quote("$code", '/');
				$first   = false;
			}
			$regex[]            = ")(?![\\w])/";
			$this->smiley_regex = implode("", $regex);
		}

		function Internal_AutoDetectURLs($string)
		{
			$output = preg_split("/( (?:
	(?:https?|ftp) : \\/*
	(?:
	(?: (?: [a-zA-Z0-9-]{2,} \\. )+
	(?: arpa | com | org | net | edu | gov | mil | int | [a-z]{2}
	| aero | biz | coop | info | museum | name | pro
	| example | invalid | localhost | test | local | onion | swift ) )
	| (?: [0-9]{1,3} \\. [0-9]{1,3} \\. [0-9]{1,3} \\. [0-9]{1,3} )
	| (?: [0-9A-Fa-f:]+ : [0-9A-Fa-f]{1,4} )
	)
	(?: : [0-9]+ )?
	(?! [a-zA-Z0-9.:-] )
	(?:
	\\/
	[^&?#\\(\\)\\[\\]\\{\\}<>\\'\\\"\\x00-\\x20\\x7F-\\xFF]*
	)?
	(?:
	[?#]
	[^\\(\\)\\[\\]\\{\\}<>\\'\\\"\\x00-\\x20\\x7F-\\xFF]+
	)?
	) | (?:
	(?:
	(?: (?: [a-zA-Z0-9-]{2,} \\. )+
	(?: arpa | com | org | net | edu | gov | mil | int | [a-z]{2}
	| aero | biz | coop | info | museum | name | pro
	| example | invalid | localhost | test | local | onion | swift ) )
	| (?: [0-9]{1,3} \\. [0-9]{1,3} \\. [0-9]{1,3} \\. [0-9]{1,3} )
	)
	(?: : [0-9]+ )?
	(?! [a-zA-Z0-9.:-] )
	(?:
	\\/
	[^&?#\\(\\)\\[\\]\\{\\}<>\\'\\\"\\x00-\\x20\\x7F-\\xFF]*
	)?
	(?:
	[?#]
	[^\\(\\)\\[\\]\\{\\}<>\\'\\\"\\x00-\\x20\\x7F-\\xFF]+
	)?
	) | (?:
	[a-zA-Z0-9._-]{2,} @
	(?:
	(?: (?: [a-zA-Z0-9-]{2,} \\. )+
	(?: arpa | com | org | net | edu | gov | mil | int | [a-z]{2}
	| aero | biz | coop | info | museum | name | pro
	| example | invalid | localhost | test | local | onion | swift ) )
	| (?: [0-9]{1,3} \\. [0-9]{1,3} \\. [0-9]{1,3} \\. [0-9]{1,3} )
	)
	) )/Dx", $string, -1, PREG_SPLIT_DELIM_CAPTURE);
			if (count($output) > 1) {
				$is_a_url = false;
				foreach ($output as $index => $token) {
					if ($is_a_url) {
						if (preg_match("/^[a-zA-Z0-9._-]{2,}@/", $token)) {
							$url = "mailto:" . $token;
						} else if (preg_match("/^(https?:|ftp:)\\/*([^\\/&?#]+)\\/*(.*)\$/", $token, $matches)) {
							$url = $matches[1] . '/' . '/' . $matches[2] . "/" . $matches[3];
						} else {
							preg_match("/^([^\\/&?#]+)\\/*(.*)\$/", $token, $matches);
							$url = "http:/" . "/" . $matches[1] . "/" . $matches[2];
						}
						$params = @parse_url($url);
						if (!is_array($params)) $params = Array();
						$params['url']  = $url;
						$params['link'] = $url;
						$params['text'] = $token;
						$output[$index] = $this->FillTemplate($this->url_pattern, $params);
					}
					$is_a_url = !$is_a_url;
				}
			}
			return $output;
		}

		function FillTemplate($template, $insert_array, $default_array = Array())
		{
			$pieces = preg_split('/(\{\$[a-zA-Z0-9_.:\/-]+\})/', $template,
				-1, PREG_SPLIT_DELIM_CAPTURE);
			if (count($pieces) <= 1)
				return $template;
			$result       = Array();
			$is_an_insert = false;
			foreach ($pieces as $piece) {
				if (!$is_an_insert) {
					$result[] = $piece;
				} else if (!preg_match('/\{\$([a-zA-Z0-9_:-]+)((?:\\.[a-zA-Z0-9_:-]+)*)(?:\/([a-zA-Z0-9_:-]+))?\}/', $piece, $matches)) {
					$result[] = $piece;
				} else {
					if (isset($insert_array[$matches[1]]))
						$value = @$insert_array[$matches[1]];
					else $value = @$default_array[$matches[1]];
					if (strlen(@$matches[2])) {
//						foreach (split(".", substr($matches[2], 1)) as $index) {
						foreach (preg_split(".", substr($matches[2], 1)) as $index) {
							if (is_array($value))
								$value = @$value[$index];
							else if (is_object($value)) {
								$value = (array)$value;
								$value = @$value[$index];
							} else $value = "";
						}
					}
					switch (gettype($value)) {
						case 'boolean':
							$value = $value ? "true" : "false";
							break;
						case 'integer':
							$value = (string)$value;
							break;
						case 'double':
							$value = (string)$value;
							break;
						case 'string':
							break;
						default:
							$value = "";
							break;
					}
					if (strlen(@$matches[3]))
						$flags = array_flip(str_split($matches[3]));
					else $flags = Array();
					if (!isset($flags['v'])) {
						if (isset($flags['w']))
							$value = preg_replace("/[\\x00-\\x09\\x0B-\x0C\x0E-\\x20]+/", " ", $value);
						if (isset($flags['t'])) $value = trim($value);
						if (isset($flags['b'])) $value = basename($value);
						if (isset($flags['e'])) $value = $this->HTMLEncode($value);
						else if (isset($flags['k'])) $value = $this->Wikify($value);
						else if (isset($flags['h'])) $value = htmlspecialchars($value);
						else if (isset($flags['u'])) $value = urlencode($value);
						if (isset($flags['n'])) $value = $this->nl2br($value);
					}
					$result[] = $value;
				}
				$is_an_insert = !$is_an_insert;
			}
			return implode("", $result);
		}

		function Internal_CollectText($array, $start = 0)
		{
			ob_start();
			for ($start = intval($start), $end = count($array); $start < $end; $start++)
				print $array[$start][BBCODE_STACK_TEXT];
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}

		function Internal_CollectTextReverse($array, $start = 0, $end = 0)
		{
			ob_start();
			for ($start = intval($start); $start >= $end; $start--)
				print $array[$start][BBCODE_STACK_TEXT];
			$output = ob_get_contents();
			ob_end_clean();
			return $output;
		}

		function Internal_GenerateOutput($pos)
		{
			$output = Array();
			while (count($this->stack) > $pos) {
				$token = array_pop($this->stack);
				if ($token[BBCODE_STACK_TOKEN] != BBCODE_TAG) {
					$output[] = $token;
				} else {
					$name    = @$token[BBCODE_STACK_TAG]['_name'];
					$rule    = @$this->tag_rules[$name];
					$end_tag = @$rule['end_tag'];
					if (!isset($rule['end_tag'])) $end_tag = BBCODE_REQUIRED;
					else $end_tag = $rule['end_tag'];
					array_pop($this->start_tags[$name]);
					if ($end_tag == BBCODE_PROHIBIT) {
						$output[] = Array(
							BBCODE_STACK_TOKEN => BBCODE_TEXT,
							BBCODE_STACK_TAG   => false,
							BBCODE_STACK_TEXT  => $token[BBCODE_STACK_TEXT],
							BBCODE_STACK_CLASS => $this->current_class,
						);
					} else {
						if ($end_tag == BBCODE_REQUIRED)
							@$this->lost_start_tags[$name] += 1;
						$end = $this->Internal_CleanupWSByIteratingPointer(@$rule['before_endtag'], 0, $output);
						$this->Internal_CleanupWSByPoppingStack(@$rule['after_tag'], $output);
						$tag_body = $this->Internal_CollectTextReverse($output, count($output) - 1, $end);
						$this->Internal_CleanupWSByPoppingStack(@$rule['before_tag'], $this->stack);
						$this->Internal_UpdateParamsForMissingEndTag($token[BBCODE_STACK_TAG]);
						$tag_output = $this->DoTag(BBCODE_OUTPUT, $name,
							@$token[BBCODE_STACK_TAG]['_default'], @$token[BBCODE_STACK_TAG], $tag_body);
						$output     = Array( Array(
							BBCODE_STACK_TOKEN => BBCODE_TEXT,
							BBCODE_STACK_TAG   => false,
							BBCODE_STACK_TEXT  => $tag_output,
							BBCODE_STACK_CLASS => $this->current_class
						) );
					}
				}
			}
			$this->Internal_ComputeCurrentClass();
			return $output;
		}

		function Internal_RewindToClass($class_list)
		{
			$pos = count($this->stack) - 1;
			while ($pos >= 0 && !in_array($this->stack[$pos][BBCODE_STACK_CLASS], $class_list))
				$pos--;
			if ($pos < 0) {
				if (!in_array($this->root_class, $class_list))
					return false;
			}
			$output = $this->Internal_GenerateOutput($pos + 1);
			while (count($output)) {
				$token                     = array_pop($output);
				$token[BBCODE_STACK_CLASS] = $this->current_class;
				$this->stack[]             = $token;
			}
			return true;
		}

		function Internal_FinishTag($tag_name)
		{
			if (strlen($tag_name) <= 0)
				return false;
			if (isset($this->start_tags[$tag_name])
				&& count($this->start_tags[$tag_name])
			)
				$pos = array_pop($this->start_tags[$tag_name]);
			else $pos = -1;
			if ($pos < 0) return false;
			$newpos = $this->Internal_CleanupWSByIteratingPointer(@$this->tag_rules[$tag_name]['after_tag'],
				$pos + 1, $this->stack);
			$delta  = $newpos - ($pos + 1);
			$output = $this->Internal_GenerateOutput($newpos);
			$newend = $this->Internal_CleanupWSByIteratingPointer(@$this->tag_rules[$tag_name]['before_endtag'],
				0, $output);
			$output = $this->Internal_CollectTextReverse($output, count($output) - 1, $newend);
			while ($delta-- > 0)
				array_pop($this->stack);
			$this->Internal_ComputeCurrentClass();
			return $output;
		}

		function Internal_ComputeCurrentClass()
		{
			if (count($this->stack) > 0)
				$this->current_class = $this->stack[count($this->stack) - 1][BBCODE_STACK_CLASS];
			else $this->current_class = $this->root_class;
		}

		/*
		 * @var $array array
		 * @var $raw
		 * @return
		 */
		function Internal_DumpStack($array = false, $raw = false)
		{
			if (!$raw) $string = "<span style='color: #00C;'>";
			else $string = "";
			if ($array === false)
				$array = $this->stack;
			foreach ($array as $item) {
				switch (@$item[BBCODE_STACK_TOKEN]) {
					case BBCODE_TEXT:
						$string .= "\"" . htmlspecialchars(@$item[BBCODE_STACK_TEXT]) . "\" ";
						break;
					case BBCODE_WS:
						$string .= "WS ";
						break;
					case BBCODE_NL:
						$string .= "NL ";
						break;
					case BBCODE_TAG:
						$string .= "[" . htmlspecialchars(@$item[BBCODE_STACK_TAG]['_name']) . "] ";
						break;
					default:
						$string .= "unknown ";
						break;
				}
			}
			if (!$raw) $string .= "</span>";
			return $string;
		}

		function Internal_CleanupWSByPoppingStack($pattern, &$array)
		{
			if (strlen($pattern) <= 0) return;
			$oldlen = count($array);
			foreach (str_split($pattern) as $char) {
				switch ($char) {
					case 's':
						while (count($array) > 0 && $array[count($array) - 1][BBCODE_STACK_TOKEN] == BBCODE_WS)
							array_pop($array);
						break;
					case 'n':
						if (count($array) > 0 && $array[count($array) - 1][BBCODE_STACK_TOKEN] == BBCODE_NL)
							array_pop($array);
						break;
					case 'a':
						while (count($array) > 0
							&& (($token = $array[count($array) - 1][BBCODE_STACK_TOKEN]) == BBCODE_WS
								|| $token == BBCODE_NL))
							array_pop($array);
						break;
				}
			}
			if (count($array) != $oldlen) {
				$this->Internal_ComputeCurrentClass();
			}
		}

		function Internal_CleanupWSByEatingInput($pattern)
		{
			if (strlen($pattern) <= 0) return;
			foreach (str_split($pattern) as $char) {
				switch ($char) {
					case 's':
						$token_type = $this->lexer->NextToken();
						while ($token_type == BBCODE_WS) {
							$token_type = $this->lexer->NextToken();
						}
						$this->lexer->UngetToken();
						break;
					case 'n':
						$token_type = $this->lexer->NextToken();
						if ($token_type != BBCODE_NL)
							$this->lexer->UngetToken();
						break;
					case 'a':
						$token_type = $this->lexer->NextToken();
						while ($token_type == BBCODE_WS || $token_type == BBCODE_NL) {
							$token_type = $this->lexer->NextToken();
						}
						$this->lexer->UngetToken();
						break;
				}
			}
		}

		function Internal_CleanupWSByIteratingPointer($pattern, $pos, $array)
		{
			if (strlen($pattern) <= 0) return $pos;
			foreach (str_split($pattern) as $char) {
				switch ($char) {
					case 's':
						while ($pos < count($array) && $array[$pos][BBCODE_STACK_TOKEN] == BBCODE_WS)
							$pos++;
						break;
					case 'n':
						if ($pos < count($array) && $array[$pos][BBCODE_STACK_TOKEN] == BBCODE_NL)
							$pos++;
						break;
					case 'a':
						while ($pos < count($array)
							&& (($token = $array[$pos][BBCODE_STACK_TOKEN]) == BBCODE_WS || $token == BBCODE_NL))
							$pos++;
						break;
				}
			}
			return $pos;
		}

		function Internal_LimitText($string, $limit)
		{
			$chunks = preg_split("/([\\x00-\\x20]+)/", $string, -1, PREG_SPLIT_DELIM_CAPTURE);
			$output = "";
			foreach ($chunks as $chunk) {
				if (strlen($output) + strlen($chunk) > $limit)
					break;
				$output .= $chunk;
			}
			$output = rtrim($output);
			return $output;
		}

		function Internal_DoLimit()
		{
			$this->Internal_CleanupWSByPoppingStack("a", $this->stack);
			if (strlen($this->limit_tail) > 0) {
				$this->stack[] = Array(
					BBCODE_STACK_TOKEN => BBCODE_TEXT,
					BBCODE_STACK_TEXT  => $this->limit_tail,
					BBCODE_STACK_TAG   => false,
					BBCODE_STACK_CLASS => $this->current_class,
				);
			}
			$this->was_limited = true;
		}

		function DoTag($action, $tag_name, $default_value, $params, $contents)
		{
			$tag_rule = @$this->tag_rules[$tag_name];
			switch ($action) {
				case BBCODE_CHECK:
					if (isset($tag_rule['allow'])) {
						foreach ($tag_rule['allow'] as $param => $pattern) {
							if ($param == '_content') $value = $contents;
							else if ($param == '_defaultcontent') {
								if (strlen($default_value))
									$value = $default_value;
								else $value = $contents;
							} else {
								if (isset($params[$param]))
									$value = $params[$param];
								else $value = @$tag_rule['default'][$param];
							}
							if (!preg_match($pattern, $value)) {
								return false;
							}
						}
						return true;
					}
					switch (@$tag_rule['mode']) {
						default:
						case BBCODE_MODE_SIMPLE:
							$result = true;
							break;
						case BBCODE_MODE_ENHANCED:
							$result = true;
							break;
						case BBCODE_MODE_INTERNAL:
							$result = @call_user_func(Array( $this, @$tag_rule['method'] ), BBCODE_CHECK,
								$tag_name, $default_value, $params, $contents);
							break;
						case BBCODE_MODE_LIBRARY:
							$result = @call_user_func(Array( $this->defaults, @$tag_rule['method'] ), $this, BBCODE_CHECK,
								$tag_name, $default_value, $params, $contents);
							break;
						case BBCODE_MODE_CALLBACK:
							$result = @call_user_func(@$tag_rule['method'], $this, BBCODE_CHECK,
								$tag_name, $default_value, $params, $contents);
							break;
					}
					return $result;
				case BBCODE_OUTPUT:
					if ($this->plain_mode) {
						if (!isset($tag_rule['plain_content']))
							$plain_content = Array( '_content' );
						else $plain_content = $tag_rule['plain_content'];
						$result = $possible_content = "";
						foreach ($plain_content as $possible_content) {
							if ($possible_content == '_content'
								&& strlen($contents) > 0
							) {
								$result = $contents;
								break;
							}
							if (isset($params[$possible_content])
								&& strlen($params[$possible_content]) > 0
							) {
								$result = htmlspecialchars($params[$possible_content]);
								break;
							}
						}
						$start = @$tag_rule['plain_start'];
						$end   = @$tag_rule['plain_end'];
						if (isset($tag_rule['plain_link'])) {
							$link = $possible_content = "";
							foreach ($tag_rule['plain_link'] as $possible_content) {
								if ($possible_content == '_content'
									&& strlen($contents) > 0
								) {
									$link = $this->UnHTMLEncode(strip_tags($contents));
									break;
								}
								if (isset($params[$possible_content])
									&& strlen($params[$possible_content]) > 0
								) {
									$link = $params[$possible_content];
									break;
								}
							}
							$params = @parse_url($link);
							if (!is_array($params)) $params = Array();
							$params['link'] = $link;
							$params['url']  = $link;
							$start          = $this->FillTemplate($start, $params);
							$end            = $this->FillTemplate($end, $params);
						}
						return $start . $result . $end;
					}
					switch (@$tag_rule['mode']) {
						default:
						case BBCODE_MODE_SIMPLE:
							$result = @$tag_rule['simple_start'] . $contents . @$tag_rule['simple_end'];
							break;
						case BBCODE_MODE_ENHANCED:
							$result = $this->Internal_DoEnhancedTag($tag_rule, $params, $contents);
							break;
						case BBCODE_MODE_INTERNAL:
							$result = @call_user_func(Array( $this, @$tag_rule['method'] ), BBCODE_OUTPUT,
								$tag_name, $default_value, $params, $contents);
							break;
						case BBCODE_MODE_LIBRARY:
							$result = @call_user_func(Array( $this->defaults, @$tag_rule['method'] ), $this, BBCODE_OUTPUT,
								$tag_name, $default_value, $params, $contents);
							break;
						case BBCODE_MODE_CALLBACK:
							$result = @call_user_func(@$tag_rule['method'], $this, BBCODE_OUTPUT,
								$tag_name, $default_value, $params, $contents);
							break;
					}
					return $result;
				default:
					return false;
			}
		}

		function Internal_DoEnhancedTag($tag_rule, $params, $contents)
		{
			$params['_content']        = $contents;
			$params['_defaultcontent'] = strlen(@$params['_default']) ? $params['_default'] : $contents;
			return $this->FillTemplate(@$tag_rule['template'], $params, @$tag_rule['default']);
		}

		function Internal_UpdateParamsForMissingEndTag(&$params)
		{
			switch ($this->tag_marker) {
				case '[':
					$tail_marker = ']';
					break;
				case '<':
					$tail_marker = '>';
					break;
				case '{':
					$tail_marker = '}';
					break;
				case '(':
					$tail_marker = ')';
					break;
				default:
					$tail_marker = $this->tag_marker;
					break;
			}
			$params['_endtag'] = $this->tag_marker . '/' . $params['_name'] . $tail_marker;
		}

		function Internal_ProcessIsolatedTag($tag_name, $tag_params, $tag_rule)
		{
			if (!$this->DoTag(BBCODE_CHECK, $tag_name, @$tag_params['_default'], $tag_params, "")) {
				$this->stack[] = Array(
					BBCODE_STACK_TOKEN => BBCODE_TEXT,
					BBCODE_STACK_TEXT  => $this->FixupOutput($this->lexer->text),
					BBCODE_STACK_TAG   => false,
					BBCODE_STACK_CLASS => $this->current_class,
				);
				return;
			}
			$this->Internal_CleanupWSByPoppingStack(@$tag_rule['before_tag'], $this->stack);
			$output = $this->DoTag(BBCODE_OUTPUT, $tag_name, @$tag_params['_default'], $tag_params, "");
			$this->Internal_CleanupWSByEatingInput(@$tag_rule['after_tag']);
			$this->stack[] = Array(
				BBCODE_STACK_TOKEN => BBCODE_TEXT,
				BBCODE_STACK_TEXT  => $output,
				BBCODE_STACK_TAG   => false,
				BBCODE_STACK_CLASS => $this->current_class,
			);
		}

		function Internal_ProcessVerbatimTag($tag_name, $tag_params, $tag_rule)
		{
			$state                 = $this->lexer->SaveState();
			$end_tag               = $this->lexer->tagmarker . "/" . $tag_name . $this->lexer->end_tagmarker;
			$start                 = count($this->stack);
			$this->lexer->verbatim = true;
			while (($token_type = $this->lexer->NextToken()) != BBCODE_EOI) {
				if ($this->lexer->text == $end_tag) {
					$end_tag_params = $this->lexer->tag;
					break;
				}
				if ($this->output_limit > 0
					&& $this->text_length + strlen($this->lexer->text) >= $this->output_limit
				) {
					$text = $this->Internal_LimitText($this->lexer->text,
						$this->output_limit - $this->text_length);
					if (strlen($text) > 0) {
						$this->text_length += strlen($text);
						$this->stack[] = Array(
							BBCODE_STACK_TOKEN => BBCODE_TEXT,
							BBCODE_STACK_TEXT  => $this->FixupOutput($text),
							BBCODE_STACK_TAG   => false,
							BBCODE_STACK_CLASS => $this->current_class,
						);
					}
					$this->Internal_DoLimit();
					break;
				}
				$this->text_length += strlen($this->lexer->text);
				$this->stack[] = Array(
					BBCODE_STACK_TOKEN => $token_type,
					BBCODE_STACK_TEXT  => htmlspecialchars($this->lexer->text),
					BBCODE_STACK_TAG   => $this->lexer->tag,
					BBCODE_STACK_CLASS => $this->current_class,
				);
			}
			$this->lexer->verbatim = false;
			if ($token_type == BBCODE_EOI) {
				$this->lexer->RestoreState($state);
				$this->stack[] = Array(
					BBCODE_STACK_TOKEN => BBCODE_TEXT,
					BBCODE_STACK_TEXT  => $this->FixupOutput($this->lexer->text),
					BBCODE_STACK_TAG   => false,
					BBCODE_STACK_CLASS => $this->current_class,
				);
				return;
			}
			$newstart = $this->Internal_CleanupWSByIteratingPointer(@$tag_rule['after_tag'], $start, $this->stack);
			$this->Internal_CleanupWSByPoppingStack(@$tag_rule['before_endtag'], $this->stack);
			$this->Internal_CleanupWSByEatingInput(@$tag_rule['after_endtag']);
			$content = $this->Internal_CollectText($this->stack, $newstart);
			array_splice($this->stack, $start);
			$this->Internal_ComputeCurrentClass();
			$this->Internal_CleanupWSByPoppingStack(@$tag_rule['before_tag'], $this->stack);
			$tag_params['_endtag'] = $end_tag_params['_tag'];
			$tag_params['_hasend'] = true;
			$output                = $this->DoTag(BBCODE_OUTPUT, $tag_name,
				@$tag_params['_default'], $tag_params, $content);
			$this->stack[]         = Array(
				BBCODE_STACK_TOKEN => BBCODE_TEXT,
				BBCODE_STACK_TEXT  => $output,
				BBCODE_STACK_TAG   => false,
				BBCODE_STACK_CLASS => $this->current_class,
			);
		}

		function Internal_ParseStartTagToken()
		{
			$tag_params = $this->lexer->tag;
			$tag_name   = @$tag_params['_name'];
			if (!isset($this->tag_rules[$tag_name])) {
				$this->stack[] = Array(
					BBCODE_STACK_TOKEN => BBCODE_TEXT,
					BBCODE_STACK_TEXT  => $this->FixupOutput($this->lexer->text),
					BBCODE_STACK_TAG   => false,
					BBCODE_STACK_CLASS => $this->current_class,
				);
				return;
			}
			$tag_rule = $this->tag_rules[$tag_name];
			$allow_in = is_array($tag_rule['allow_in'])
				? $tag_rule['allow_in'] : Array( $this->root_class );
			if (!in_array($this->current_class, $allow_in)) {
				if (!$this->Internal_RewindToClass($allow_in)) {
					$this->stack[] = Array(
						BBCODE_STACK_TOKEN => BBCODE_TEXT,
						BBCODE_STACK_TEXT  => $this->FixupOutput($this->lexer->text),
						BBCODE_STACK_TAG   => false,
						BBCODE_STACK_CLASS => $this->current_class,
					);
					return;
				}
			}
			$end_tag = isset($tag_rule['end_tag']) ? $tag_rule['end_tag'] : BBCODE_REQUIRED;
			if ($end_tag == BBCODE_PROHIBIT) {
				$this->Internal_ProcessIsolatedTag($tag_name, $tag_params, $tag_rule);
				return;
			}
			if (!$this->DoTag(BBCODE_CHECK, $tag_name, @$tag_params['_default'], $tag_params, "")) {
				$this->stack[] = Array(
					BBCODE_STACK_TOKEN => BBCODE_TEXT,
					BBCODE_STACK_TEXT  => $this->FixupOutput($this->lexer->text),
					BBCODE_STACK_TAG   => false,
					BBCODE_STACK_CLASS => $this->current_class,
				);
				return;
			}
			if (@$tag_rule['content'] == BBCODE_VERBATIM) {
				$this->Internal_ProcessVerbatimTag($tag_name, $tag_params, $tag_rule);
				return;
			}
			if (isset($tag_rule['class']))
				$newclass = $tag_rule['class'];
			else $newclass = $this->root_class;
			$this->stack[] = Array(
				BBCODE_STACK_TOKEN => $this->lexer->token,
				BBCODE_STACK_TEXT  => $this->FixupOutput($this->lexer->text),
				BBCODE_STACK_TAG   => $this->lexer->tag,
				BBCODE_STACK_CLASS => ($this->current_class = $newclass),
			);
			if (!isset($this->start_tags[$tag_name]))
				$this->start_tags[$tag_name] = Array( count($this->stack) - 1 );
			else $this->start_tags[$tag_name][] = count($this->stack) - 1;
		}

		function Internal_ParseEndTagToken()
		{
			$tag_params = $this->lexer->tag;
			$tag_name   = @$tag_params['_name'];
			$contents   = $this->Internal_FinishTag($tag_name);
			if ($contents === false) {
				if (@$this->lost_start_tags[$tag_name] > 0) {
					$this->lost_start_tags[$tag_name]--;
				} else {
					$this->stack[] = Array(
						BBCODE_STACK_TOKEN => BBCODE_TEXT,
						BBCODE_STACK_TEXT  => $this->FixupOutput($this->lexer->text),
						BBCODE_STACK_TAG   => false,
						BBCODE_STACK_CLASS => $this->current_class,
					);
				}
				return;
			}
			$start_tag_node   = array_pop($this->stack);
			$start_tag_params = $start_tag_node[BBCODE_STACK_TAG];
			$this->Internal_ComputeCurrentClass();
			$this->Internal_CleanupWSByPoppingStack(@$this->tag_rules[$tag_name]['before_tag'], $this->stack);
			$start_tag_params['_endtag'] = $tag_params['_tag'];
			$start_tag_params['_hasend'] = true;
			$output                      = $this->DoTag(BBCODE_OUTPUT, $tag_name, @$start_tag_params['_default'],
				$start_tag_params, $contents);
			$this->Internal_CleanupWSByEatingInput(@$this->tag_rules[$tag_name]['after_endtag']);
			$this->stack[] = Array(
				BBCODE_STACK_TOKEN => BBCODE_TEXT,
				BBCODE_STACK_TEXT  => $output,
				BBCODE_STACK_TAG   => false,
				BBCODE_STACK_CLASS => $this->current_class,
			);
		}

		function Parse($string)
		{
			$this->lexer        = new BBCodeLexer($string, $this->tag_marker);
			$this->lexer->debug = $this->debug;
			$old_output_limit   = $this->output_limit;
			if ($this->output_limit > 0) {
				if (strlen($string) < $this->output_limit) {
					$this->output_limit = 0;
				} else if ($this->limit_precision > 0) {
					$guess_length = $this->lexer->GuessTextLength();
					if ($guess_length < $this->output_limit * ($this->limit_precision + 1.0)) {
						$this->output_limit = 0;
					} else {
					}
				}
			}
			$this->stack           = Array();
			$this->start_tags      = Array();
			$this->lost_start_tags = Array();
			$this->text_length     = 0;
			$this->was_limited     = false;
			if (strlen($this->pre_trim) > 0)
				$this->Internal_CleanupWSByEatingInput($this->pre_trim);
			$newline = $this->plain_mode ? "\n" : "<br />\n";
			while (true) {
				if (($token_type = $this->lexer->NextToken()) == BBCODE_EOI) {
					break;
				}
				switch ($token_type) {
					case BBCODE_TEXT:
						if ($this->output_limit > 0
							&& $this->text_length + strlen($this->lexer->text) >= $this->output_limit
						) {
							$text = $this->Internal_LimitText($this->lexer->text,
								$this->output_limit - $this->text_length);
							if (strlen($text) > 0) {
								$this->text_length += strlen($text);
								$this->stack[] = Array(
									BBCODE_STACK_TOKEN => BBCODE_TEXT,
									BBCODE_STACK_TEXT  => $this->FixupOutput($text),
									BBCODE_STACK_TAG   => false,
									BBCODE_STACK_CLASS => $this->current_class,
								);
							}
							$this->Internal_DoLimit();
							break 2;
						}
						$this->text_length += strlen($this->lexer->text);
						$this->stack[] = Array(
							BBCODE_STACK_TOKEN => BBCODE_TEXT,
							BBCODE_STACK_TEXT  => $this->FixupOutput($this->lexer->text),
							BBCODE_STACK_TAG   => false,
							BBCODE_STACK_CLASS => $this->current_class,
						);
						break;
					case BBCODE_WS:
						if ($this->output_limit > 0
							&& $this->text_length + strlen($this->lexer->text) >= $this->output_limit
						) {
							$this->Internal_DoLimit();
							break 2;
						}
						$this->text_length += strlen($this->lexer->text);
						$this->stack[] = Array(
							BBCODE_STACK_TOKEN => BBCODE_WS,
							BBCODE_STACK_TEXT  => $this->lexer->text,
							BBCODE_STACK_TAG   => false,
							BBCODE_STACK_CLASS => $this->current_class,
						);
						break;
					case BBCODE_NL:
						if ($this->ignore_newlines) {
							if ($this->output_limit > 0
								&& $this->text_length + 1 >= $this->output_limit
							) {
								$this->Internal_DoLimit();
								break 2;
							}
							$this->text_length += 1;
							$this->stack[] = Array(
								BBCODE_STACK_TOKEN => BBCODE_WS,
								BBCODE_STACK_TEXT  => "\n",
								BBCODE_STACK_TAG   => false,
								BBCODE_STACK_CLASS => $this->current_class,
							);
						} else {
							$this->Internal_CleanupWSByPoppingStack("s", $this->stack);
							if ($this->output_limit > 0
								&& $this->text_length + 1 >= $this->output_limit
							) {
								$this->Internal_DoLimit();
								break 2;
							}
							$this->text_length += 1;
							$this->stack[] = Array(
								BBCODE_STACK_TOKEN => BBCODE_NL,
								BBCODE_STACK_TEXT  => $newline,
								BBCODE_STACK_TAG   => false,
								BBCODE_STACK_CLASS => $this->current_class,
							);
							$this->Internal_CleanupWSByEatingInput("s");
						}
						break;
					case BBCODE_TAG:
						$this->Internal_ParseStartTagToken();
						break;
					case BBCODE_ENDTAG:
						$this->Internal_ParseEndTagToken();
						break;
					default:
						break;
				}
			}
			if (strlen($this->post_trim) > 0)
				$this->Internal_CleanupWSByPoppingStack($this->post_trim, $this->stack);
			$result             = $this->Internal_GenerateOutput(0);
			$result             = $this->Internal_CollectTextReverse($result, count($result) - 1);
			$this->output_limit = $old_output_limit;
			if ($this->plain_mode) {
				$result = preg_replace("/[\\x00-\\x09\\x0B-\\x20]+/", " ", $result);
				$result = preg_replace("/(?:[\\x20]*\\n){2,}[\\x20]*/", "\n\n", $result);
				$result = trim($result);
			}
			return $result;
		}
	}

//$GLOBALS['poa']['bbcode'] = new BBCode();
//$GLOBALS['poa']['bbcode']->SetAllowAmpersand(true);
//$GLOBALS['poa']['bbcode']->SetSmileyDir($basedir . '/smileys');
//$GLOBALS['poa']['bbcode']->SetDetectURLs(true);
//$GLOBALS['poa']['bbcode']->SetURLTargetable(true);
//
//$GLOBALS['poa']['bbcode']->AddRule('imgsize', Array(
//	'mode'     => BBCODE_MODE_ENHANCED,
//	'template' => '<img width="{$width}" height="{$height}" src="{$_content}" />',
//	'allow'    => Array(
//		'width'  => '/^[1-9][0-9]*$/',
//		'height' => '/^[1-9][0-9]*$/',
//	),
//	'default'  => Array(
//		'width'  => '501',
//		'height' => '291',
//	),
//	'class'    => 'block',
//	'allow_in' => Array( 'listitem', 'block', 'columns', 'inline', 'link' )
//));
//
//$GLOBALS['poa']['bbcode']->AddRule('anchor', Array(
//	'mode'     => BBCODE_MODE_ENHANCED,
//	'template' => '<div id="{$name}">{$_content}</div>',
//	'allow'    => Array(
//		'name' => '/^[a-zA-Z0-9._ -]+$/',
//	),
//	'class'    => 'block',
//	'allow_in' => Array( 'listitem', 'block', 'columns', 'inline' )
//));
//
//$GLOBALS['poa']['bbcode']->AddRule('goto', Array(
//	'mode'     => BBCODE_MODE_ENHANCED,
//	'template' => '<a href="#{$name}">{$_content}</a>',
//	'allow'    => Array(
//		'name' => '/^[a-zA-Z0-9._ -]+$/',
//	),
//	'class'    => 'block',
//	'allow_in' => Array( 'listitem', 'block', 'columns', 'inline' )
//));
//
//$GLOBALS['poa']['bbcode']->AddRule('line', Array(
//	'mode'     => BBCODE_MODE_ENHANCED,
//	'template' => '<div style="width:90%;overflow:auto; zoom:1">{$_content}</div>',
////		'template' => '{$_content}<div style="clear: both;"></div>',
//	'class'    => 'block',
//	'allow_in' => Array( 'list', 'listitem', 'block', 'columns', 'inline' )
//));
//
//$GLOBALS['poa']['bbcode']->AddRule('lineleft', Array(
//	'mode'     => BBCODE_MODE_ENHANCED,
//	'template' => '<div style="margin:0; text-align:left; float:left;">{$_content}</div>',
//	'class'    => 'block',
//	'allow_in' => Array( 'list', 'listitem', 'block', 'columns', 'inline' )
//));
//
//$GLOBALS['poa']['bbcode']->AddRule('lineright', Array(
//	'mode'     => BBCODE_MODE_ENHANCED,
//	'template' => '<div style="margin:0; text-align:right; float:right;">{$_content}</div>',
//	'class'    => 'block',
//	'allow_in' => Array( 'list', 'listitem', 'block', 'columns', 'inline' )
//));
//
