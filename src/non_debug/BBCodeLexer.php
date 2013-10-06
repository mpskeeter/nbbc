<?php

	namespace MPeters\NbbcBundle\src\non_debug;

	class BBCodeLexer {
		var $token;
		var $text;
		var $tag;
		var $state;
		var $input;
		var $ptr;
		var $unget;
		var $verbatim;
		var $debug;
		var $tagmarker;
		var $end_tagmarker;
		var $pat_main;
		var $pat_comment;
		var $pat_comment2;
		var $pat_wiki;

		function __construct($string, $tagmarker = '[') {
			$regex_beginmarkers = Array( '[' => '\[', '<' => '<', '{' => '\{', '(' => '\(' );
			$regex_endmarkers = Array( '[' => '\]', '<' => '>', '{' => '\}', '(' => '\)' );
			$endmarkers = Array( '[' => ']', '<' => '>', '{' => '}', '(' => ')' );
			if (!isset($regex_endmarkers[$tagmarker])) $tagmarker = '[';
			$e = $regex_endmarkers[$tagmarker];
			$b = $regex_beginmarkers[$tagmarker];
			$this->tagmarker = $tagmarker;
			$this->end_tagmarker = $endmarkers[$tagmarker];
			$this->pat_main = "/( "
				. "{$b}"
				. "(?! -- | ' | !-- | {$b}{$b} )"
				. "(?: [^\\n\\r{$b}{$e}] | \\\" [^\\\"\\n\\r]* \\\" | \\' [^\\'\\n\\r]* \\' )*"
				. "{$e}"
				. "| {$b}{$b} (?: [^{$e}\\r\\n] | {$e}[^{$e}\\r\\n] )* {$e}{$e}"
				. "| {$b} (?: -- | ' ) (?: [^{$e}\\n\\r]* ) {$e}"
				. "| {$b}!-- (?: [^-] | -[^-] | --[^{$e}] )* --{$e}"
				. "| -----+"
				. "| \\x0D\\x0A | \\x0A\\x0D | \\x0D | \\x0A"
				. "| [\\x00-\\x09\\x0B-\\x0C\\x0E-\\x20]+(?=[\\x0D\\x0A{$b}]|-----|$)"
				. "| (?<=[\\x0D\\x0A{$e}]|-----|^)[\\x00-\\x09\\x0B-\\x0C\\x0E-\\x20]+"
				. " )/Dx";
			$this->input = preg_split($this->pat_main, $string, -1, PREG_SPLIT_DELIM_CAPTURE);
			$this->pat_comment = "/^ {$b} (?: -- | ' ) /Dx";
			$this->pat_comment2 = "/^ {$b}!-- (?: [^-] | -[^-] | --[^{$e}] )* --{$e} $/Dx";
			$this->pat_wiki = "/^ {$b}{$b} ([^\\|]*) (?:\\|(.*))? {$e}{$e} $/Dx";
			$this->ptr = 0;
			$this->unget = false;
			$this->state = BBCODE_LEXSTATE_TEXT;
			$this->verbatim = false;
			$this->token = BBCODE_EOI;
			$this->tag = false;
			$this->text = "";
		}

		function GuessTextLength() {
			$length = 0;
			$ptr = 0;
			$state = BBCODE_LEXSTATE_TEXT;
			while ($ptr < count($this->input)) {
				$text = $this->input[$ptr++];
				if ($state == BBCODE_LEXSTATE_TEXT) {
					$state = BBCODE_LEXSTATE_TAG;
					$length += strlen($text);
				}
				else {
					switch (ord(substr($this->text, 0, 1))) {
						case 10:
						case 13:
							$state = BBCODE_LEXSTATE_TEXT;
							$length++;
							break;
						default:
							$state = BBCODE_LEXSTATE_TEXT;
							$length += strlen($text);
							break;
						case 40:
						case 60:
						case 91:
						case 123:
							$state = BBCODE_LEXSTATE_TEXT;
							break;
					}
				}
			}
			return $length;
		}

		function NextToken() {
			if ($this->unget) {
				$this->unget = false;
				return $this->token;
			}
			while (true) {
				if ($this->ptr >= count($this->input)) {
					$this->text = "";
					$this->tag = false;
					return $this->token = BBCODE_EOI;
				}
				$this->text = preg_replace("/[\\x00-\\x08\\x0B-\\x0C\\x0E-\\x1F]/", "",
					$this->input[$this->ptr++]);
				if ($this->verbatim) {
					$this->tag = false;
					if ($this->state == BBCODE_LEXSTATE_TEXT) {
						$this->state = BBCODE_LEXSTATE_TAG;
						$token_type = BBCODE_TEXT;
					}
					else {
						$this->state = BBCODE_LEXSTATE_TEXT;
						switch (ord(substr($this->text, 0, 1))) {
							case 10:
							case 13:
								$token_type = BBCODE_NL;
								break;
							default:
								$token_type = BBCODE_WS;
								break;
							case 45:
							case 40:
							case 60:
							case 91:
							case 123:
								$token_type = BBCODE_TEXT;
								break;
						}
					}
					if (strlen($this->text) > 0)
						return $this->token = $token_type;
				}
				else if ($this->state == BBCODE_LEXSTATE_TEXT) {
					$this->state = BBCODE_LEXSTATE_TAG;
					$this->tag = false;
					if (strlen($this->text) > 0)
						return $this->token = BBCODE_TEXT;
				}
				else {
					switch (ord(substr($this->text, 0, 1))) {
						case 10:
						case 13:
							$this->tag = false;
							$this->state = BBCODE_LEXSTATE_TEXT;
							return $this->token = BBCODE_NL;
						case 45:
							if (preg_match("/^-----/", $this->text)) {
								$this->tag = Array('_name' => 'rule', '_endtag' => false, '_default' => '');
								$this->state = BBCODE_LEXSTATE_TEXT;
								return $this->token = BBCODE_TAG;
							}
							else {
								$this->tag = false;
								$this->state = BBCODE_LEXSTATE_TEXT;
								if (strlen($this->text) > 0)
									return $this->token = BBCODE_TEXT;
								continue;
							}
						default:
							$this->tag = false;
							$this->state = BBCODE_LEXSTATE_TEXT;
							return $this->token = BBCODE_WS;
						case 40:
						case 60:
						case 91:
						case 123:
							if (preg_match($this->pat_comment, $this->text)) {
								$this->state = BBCODE_LEXSTATE_TEXT;
								continue;
							}
							if (preg_match($this->pat_comment2, $this->text)) {
								$this->state = BBCODE_LEXSTATE_TEXT;
								continue;
							}
							if (preg_match($this->pat_wiki, $this->text, $matches)) {
								$this->tag = Array('_name' => 'wiki', '_endtag' => false,
												   '_default' => @$matches[1], 'title' => @$matches[2]);
								$this->state = BBCODE_LEXSTATE_TEXT;
								return $this->token = BBCODE_TAG;
							}
							$this->tag = $this->Internal_DecodeTag($this->text);
							$this->state = BBCODE_LEXSTATE_TEXT;
							return $this->token = ($this->tag['_end'] ? BBCODE_ENDTAG : BBCODE_TAG);
					}
				}
			}
		}

		function UngetToken() {
			if ($this->token !== BBCODE_EOI)
				$this->unget = true;
		}

		function PeekToken() {
			$result = $this->NextToken();
			if ($this->token !== BBCODE_EOI)
				$this->unget = true;
			return $result;
		}

		function SaveState() {
			return Array(
				'token' => $this->token,
				'text' => $this->text,
				'tag' => $this->tag,
				'state' => $this->state,
				'input' => $this->input,
				'ptr' => $this->ptr,
				'unget' => $this->unget,
				'verbatim' => $this->verbatim
			);
		}

		function RestoreState($state) {
			if (!is_array($state)) return;
			$this->token = @$state['token'];
			$this->text = @$state['text'];
			$this->tag = @$state['tag'];
			$this->state = @$state['state'];
			$this->input = @$state['input'];
			$this->ptr = @$state['ptr'];
			$this->unget = @$state['unget'];
			$this->verbatim = @$state['verbatim'];
		}

		function Internal_StripQuotes($string) {
			if (preg_match("/^\\\"(.*)\\\"$/", $string, $matches))
				return $matches[1];
			else if (preg_match("/^\\'(.*)\\'$/", $string, $matches))
				return $matches[1];
			else return $string;
		}

		function Internal_ClassifyPiece($ptr, $pieces) {
			if ($ptr >= count($pieces)) return -1;
			$piece = $pieces[$ptr];
			if ($piece == '=') return '=';
			else if (preg_match("/^[\\'\\\"]/", $piece)) return '"';
			else if (preg_match("/^[\\x00-\\x20]+$/", $piece)) return ' ';
			else return 'A';
		}

		function Internal_DecodeTag($tag) {
			$result = Array('_tag' => $tag, '_endtag' => '', '_name' => '',
							'_hasend' => false, '_end' => false, '_default' => false);
			$tag = substr($tag, 1, strlen($tag)-2);
			$ch = ord(substr($tag, 0, 1));
			if ($ch >= 0 && $ch <= 32) return $result;
			$pieces = preg_split("/(\\\"[^\\\"]+\\\"|\\'[^\\']+\\'|=|[\\x00-\\x20]+)/",
				$tag, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
			$ptr = 0;
			if (count($pieces) < 1) return $result;
			if (@substr($pieces[$ptr], 0, 1) == '/') {
				$result['_name'] = strtolower(substr($pieces[$ptr++], 1));
				$result['_end'] = true;
			}
			else {
				$result['_name'] = strtolower($pieces[$ptr++]);
				$result['_end'] = false;
			}
			while (($type = $this->Internal_ClassifyPiece($ptr, $pieces)) == ' ')
				$ptr++;
			$params = Array();
			if ($type != '=') {
				$result['_default'] = false;
				$params[] = Array('key' => '', 'value' => '');
			}
			else {
				$ptr++;
				while (($type = $this->Internal_ClassifyPiece($ptr, $pieces)) == ' ')
					$ptr++;
				if ($type == "\"")
					$value = $this->Internal_StripQuotes($pieces[$ptr++]);
				else {
					$after_space = false;
					$start = $ptr;
					while (($type = $this->Internal_ClassifyPiece($ptr, $pieces)) != -1) {
						if ($type == ' ') $after_space = true;
						if ($type == '=' && $after_space) break;
						$ptr++;
					}
					if ($type == -1) $ptr--;
					if ($type == '=') {
						$ptr--;
						while ($ptr > $start && $this->Internal_ClassifyPiece($ptr, $pieces) == ' ')
							$ptr--;
						while ($ptr > $start && $this->Internal_ClassifyPiece($ptr, $pieces) != ' ')
							$ptr--;
					}
					$value = "";
					for (; $start <= $ptr; $start++) {
						if ($this->Internal_ClassifyPiece($start, $pieces) == ' ')
							$value .= " ";
						else $value .= $this->Internal_StripQuotes($pieces[$start]);
					}
					$value = trim($value);
					$ptr++;
				}
				$result['_default'] = $value;
				$params[] = Array('key' => '', 'value' => $value);
			}
			while (($type = $this->Internal_ClassifyPiece($ptr, $pieces)) != -1) {
				while ($type == ' ') {
					$ptr++;
					$type = $this->Internal_ClassifyPiece($ptr, $pieces);
				}
				if ($type == 'A' || $type == '"')
					$key = strtolower($this->Internal_StripQuotes(@$pieces[$ptr++]));
				else if ($type == '=') {
					$ptr++;
					continue;
				}
				else if ($type == -1) break;
				while (($type = $this->Internal_ClassifyPiece($ptr, $pieces)) == ' ')
					$ptr++;
				if ($type != '=')
					$value = $this->Internal_StripQuotes($key);
				else {
					$ptr++;
					while (($type = $this->Internal_ClassifyPiece($ptr, $pieces)) == ' ')
						$ptr++;
					if ($type == '"') {
						$value = $this->Internal_StripQuotes($pieces[$ptr++]);
					}
					else if ($type != -1) {
						$value = $pieces[$ptr++];
						while (($type = $this->Internal_ClassifyPiece($ptr, $pieces)) != -1
							&& $type != ' ')
							$value .= $pieces[$ptr++];
					}
					else $value = "";
				}
				if (substr($key, 0, 1) != '_')
					$result[$key] = $value;
				$params[] = Array('key' => $key, 'value' => $value);
			}
			$result['_params'] = $params;
			return $result;
		}
	}

