<?php

	/**
	 * SjonSite - Markdown Filter
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @package Sjonsite_Filter
	 * @copyright Sjon's dotCom 2007
	 * @license Mozilla Public License 1.1
	 * @version $Id$
	 */

	/**
	 * Class Sjonsite_Filter_Markdown
	 *
	 * @author Sjon <sjonscom@gmail.com>
	 * @copyright Sjon's dotCom 2007
	 */
	class Sjonsite_Filter_Markdown implements Sjonsite_Filter {
	}


	/*
	# Markdown Extra  -  A text-to-HTML conversion tool for web writers
	# PHP Markdown & Extra Copyright (c) 2004-2007 Michel Fortin <http://www.michelf.com/projects/php-markdown/>
	# Original Markdown Copyright (c) 2004-2006 John Gruber <http://daringfireball.net/projects/markdown/>
	define( 'MARKDOWN_VERSION',       "1.0.1f" ); # Wed 7 Feb 2007
	define( 'MARKDOWNEXTRA_VERSION',  "1.1.2" );  # Wed 7 Feb 2007
	define( 'MARKDOWN_EMPTY_ELEMENT_SUFFIX',  " />");
	define( 'MARKDOWN_TAB_WIDTH',     4 );
	define( 'MARKDOWN_FN_LINK_TITLE',         "" );
	define( 'MARKDOWN_FN_BACKLINK_TITLE',     "" );
	define( 'MARKDOWN_FN_LINK_CLASS',         "" );
	define( 'MARKDOWN_FN_BACKLINK_CLASS',     "" );
	define( 'MARKDOWN_PARSER_CLASS',  'MarkdownExtra_Parser' );

	function Markdown($text) {
		static $parser;
		if (!isset($parser)) {
			$parser_class = MARKDOWN_PARSER_CLASS;
			$parser = new $parser_class;
			@include_once 'smartypants.php';
		}
		$text = $parser->transform($text);
		if (function_exists('SmartyPants'))  $text = SmartyPants($text);
		return $text;
	}

	class Markdown_Parser {
		var $nested_brackets_depth = 6;
		var $nested_brackets;
		var $escape_chars = '\`*_{}[]()>#+-.!';
		var $escape_table = array();
		var $backslash_escape_table = array();
		var $empty_element_suffix = MARKDOWN_EMPTY_ELEMENT_SUFFIX;
		var $tab_width = MARKDOWN_TAB_WIDTH;
		function Markdown_Parser() {
			$this->_initDetab();
			$this->nested_brackets = str_repeat('(?>[^\[\]]+|\[', $this->nested_brackets_depth) . str_repeat('\])*', $this->nested_brackets_depth);
			foreach (preg_split('/(?!^|$)/', $this->escape_chars) as $char) {
				$hash = md5($char);
				$this->escape_table[$char] = $hash;
				$this->backslash_escape_table["\\$char"] = $hash;
			}
			asort($this->document_gamut);
			asort($this->block_gamut);
			asort($this->span_gamut);
		}
		var $urls = array();
		var $titles = array();
		var $html_blocks = array();
		var $html_hashes = array(); # Contains both blocks and span hashes.
		function transform($text) {
			$this->urls = array();
			$this->titles = array();
			$this->html_blocks = array();
			$this->html_hashes = array();
			$text = str_replace(array("\r\n", "\r"), "\n", $text);
			$text .= "\n\n";
			$text = $this->detab($text);
			$text = $this->hashHTMLBlocks($text);
			$text = preg_replace('/^[ \t]+$/m', '', $text);
			foreach ($this->document_gamut as $method => $priority) {
				$text = $this->$method($text);
			}
			return $text . "\n";
		}
		var $document_gamut = array(
			"stripLinkDefinitions" => 20,
			"runBasicBlockGamut"   => 30,
			"unescapeSpecialChars" => 90,
		);
		function stripLinkDefinitions($text) {
			$less_than_tab = $this->tab_width - 1;
			$text = preg_replace_callback('{
							^[ ]{0,'.$less_than_tab.'}\[(.+)\][ ]?:	# id = $1
							  [ \t]*
							  \n?				# maybe *one* newline
							  [ \t]*
							<?(\S+?)>?			# url = $2
							  [ \t]*
							  \n?				# maybe one newline
							  [ \t]*
							(?:
								(?<=\s)			# lookbehind for whitespace
								["(]
								(.*?)			# title = $3
								[")]
								[ \t]*
							)?	# title is optional
							(?:\n+|\Z)
			}xm',
			array(&$this, '_stripLinkDefinitions_callback'),
			$text);
			return $text;
		}
		function _stripLinkDefinitions_callback($matches) {
			$link_id = strtolower($matches[1]);
			$this->urls[$link_id] = $this->encodeAmpsAndAngles($matches[2]);
			if (isset($matches[3]))
				$this->titles[$link_id] = str_replace('"', '&quot;', $matches[3]);
			return '';
		}
		function hashHTMLBlocks($text) {
			$less_than_tab = $this->tab_width - 1;
			$block_tags_a = 'p|div|h[1-6]|blockquote|pre|table|dl|ol|ul|address|script|noscript|form|fieldset|iframe|math|ins|del';
			$block_tags_b = 'p|div|h[1-6]|blockquote|pre|table|dl|ol|ul|address|script|noscript|form|fieldset|iframe|math';
			$nested_tags_level = 4;
			$attr = '
			(?>				# optional tag attributes
			  \s			# starts with whitespace
			  (?>
				[^>"/]+		# text outside quotes
			  |
				/+(?!>)		# slash not followed by ">"
			  |
				"[^"]*"		# text inside double quotes (tolerate ">")
			  |
				\'[^\']*\'	# text inside single quotes (tolerate ">")
			  )*
			)?
			';
			$content = str_repeat('
				(?>
				  [^<]+			# content without tag
				|
				  <\2			# nested opening tag
					'.$attr.'	# attributes
					(?:
					  />
					|
					  >', $nested_tags_level).	# end of opening tag
					  '.*?'.					# last level nested tag content
			str_repeat('
					  </\2\s*>	# closing nested tag
					)
				  |
					<(?!/\2\s*>	# other tags with a different name
				  )
				)*',
				$nested_tags_level);
			$text = preg_replace_callback('{
					(						# save in $1
						^					# start of line  (with /m)
						<('.$block_tags_a.')# start tag = $2
						'.$attr.'>\n		# attributes followed by > and \n
						'.$content.'		# content, support nesting
						</\2>				# the matching end tag
						[ \t]*				# trailing spaces/tabs
						(?=\n+|\Z)	# followed by a newline or end of document
					)
			}xm',
			array(&$this, '_hashHTMLBlocks_callback'),
			$text);
			$text = preg_replace_callback('{
					(						# save in $1
						^					# start of line  (with /m)
						<('.$block_tags_b.')# start tag = $2
						'.$attr.'>			# attributes followed by >
						'.$content.'		# content, support nesting
						</\2>				# the matching end tag
						[ \t]*				# trailing spaces/tabs
						(?=\n+|\Z)	# followed by a newline or end of document
					)
			}xm',
			array(&$this, '_hashHTMLBlocks_callback'),
			$text);
			$text = preg_replace_callback('{
					(?:
						(?<=\n\n)		# Starting after a blank line
						|				# or
						\A\n?			# the beginning of the doc
					)
					(						# save in $1
						[ ]{0,'.$less_than_tab.'}
						<(hr)				# start tag = $2
						\b					# word break
						([^<>])*?			#
						/?>					# the matching end tag
						[ \t]*
						(?=\n{2,}|\Z)		# followed by a blank line or end of document
					)
			}x',
			array(&$this, '_hashHTMLBlocks_callback'),
			$text);
			$text = preg_replace_callback('{
				(?:
					(?<=\n\n)		# Starting after a blank line
					|				# or
					\A\n?			# the beginning of the doc
				)
				(						# save in $1
					[ ]{0,'.$less_than_tab.'}
					(?s:
						<!-- .*? -->
					)
					[ \t]*
					(?=\n{2,}|\Z)		# followed by a blank line or end of document
				)
			}x',
			array(&$this, '_hashHTMLBlocks_callback'),
			$text);
			$text = preg_replace_callback('{
				(?:
					(?<=\n\n)		# Starting after a blank line
					|				# or
					\A\n?			# the beginning of the doc
				)
				(						# save in $1
					[ ]{0,'.$less_than_tab.'}
					(?s:
						<([?%])			# $2
						.*?
						\2>
					)
					[ \t]*
					(?=\n{2,}|\Z)		# followed by a blank line or end of document
				)
			}x',
			array(&$this, '_hashHTMLBlocks_callback'),
			$text);
			return $text;
		}
		function _hashHTMLBlocks_callback($matches) {
			$text = $matches[1];
			$key  = $this->hashBlock($text);
			return "\n\n$key\n\n";
		}
		function hashBlock($text) {
			$text = $this->unhash($text);
			$key = md5($text);
			$this->html_hashes[$key] = $text;
			$this->html_blocks[$key] = $text;
			return $key;
		}
		function hashSpan($text) {
			$text = $this->unhash($text);
			$key = md5($text);
			$this->html_hashes[$key] = $text;
			return $key;
		}
		var $block_gamut = array(
			"doHeaders"         => 10,
			"doHorizontalRules" => 20,
			"doLists"           => 40,
			"doCodeBlocks"      => 50,
			"doBlockQuotes"     => 60,
		);
		function runBlockGamut($text) {
			$text = $this->hashHTMLBlocks($text);
			return $this->runBasicBlockGamut($text);
		}
		function runBasicBlockGamut($text) {
			foreach ($this->block_gamut as $method => $priority) {
				$text = $this->$method($text);
			}
			$text = $this->formParagraphs($text);
			return $text;
		}
		function doHorizontalRules($text) {
			return preg_replace(
			array('{^[ ]{0,2}([ ]?\*[ ]?){3,}[ \t]*$}mx',
				  '{^[ ]{0,2}([ ]? -[ ]?){3,}[ \t]*$}mx',
				  '{^[ ]{0,2}([ ]? _[ ]?){3,}[ \t]*$}mx'),
			"\n".$this->hashBlock("<hr$this->empty_element_suffix")."\n",
			$text);
		}
		var $span_gamut = array(
			"escapeSpecialCharsWithinTagAttributes"	=> -20,
			"doCodeSpans"							=> -10,
			"encodeBackslashEscapes"				=>  -5,
			"doImages"            =>  10,
			"doAnchors"           =>  20,
			"doAutoLinks"         =>  30,
			"encodeAmpsAndAngles" =>  40,
			"doItalicsAndBold"    =>  50,
			"doHardBreaks"        =>  60,
		);
		function runSpanGamut($text) {
			foreach ($this->span_gamut as $method => $priority) {
				$text = $this->$method($text);
			}
			return $text;
		}
		function doHardBreaks($text) {
			$br_tag = $this->hashSpan("<br$this->empty_element_suffix\n");
			return preg_replace('/ {2,}\n/', $br_tag, $text);
		}
		function escapeSpecialCharsWithinTagAttributes($text) {
			$tokens = $this->tokenizeHTML($text);
			$text = '';
			foreach ($tokens as $cur_token) {
				if ($cur_token[0] == 'tag') {
					$cur_token[1] = str_replace('\\', $this->escape_table['\\'], $cur_token[1]);
					$cur_token[1] = str_replace(array('`'), $this->escape_table['`'], $cur_token[1]);
					$cur_token[1] = str_replace('*', $this->escape_table['*'], $cur_token[1]);
					$cur_token[1] = str_replace('_', $this->escape_table['_'], $cur_token[1]);
				}
				$text .= $cur_token[1];
			}
			return $text;
		}
		function doAnchors($text) {
			$text = preg_replace_callback('{
			(					# wrap whole match in $1
			  \[
				('.$this->nested_brackets.')	# link text = $2
			  \]

			  [ ]?				# one optional space
			  (?:\n[ ]*)?		# one optional newline followed by spaces

			  \[
				(.*?)		# id = $3
			  \]
			)
			}xs',
			array(&$this, '_doAnchors_reference_callback'), $text);
			$text = preg_replace_callback('{
			(				# wrap whole match in $1
			  \[
				('.$this->nested_brackets.')	# link text = $2
			  \]
			  \(			# literal paren
				[ \t]*
				<?(.*?)>?	# href = $3
				[ \t]*
				(			# $4
				  ([\'"])	# quote char = $5
				  (.*?)		# Title = $6
				  \5		# matching quote
				  [ \t]*	# ignore any spaces/tabs between closing quote and )
				)?			# title is optional
			  \)
			)
			}xs',
			array(&$this, '_DoAnchors_inline_callback'), $text);
			return $text;
		}
		function _doAnchors_reference_callback($matches) {
			$whole_match =  $matches[1];
			$link_text   =  $matches[2];
			$link_id     =& $matches[3];
			if ($link_id == "") {
				$link_id = $link_text;
			}
			$link_id = strtolower($link_id);
			$link_id = preg_replace('{[ ]?\n}', ' ', $link_id);
			if (isset($this->urls[$link_id])) {
				$url = $this->urls[$link_id];
				$url = $this->encodeAmpsAndAngles($url);
				$result = "<a href=\"$url\"";
				if ( isset( $this->titles[$link_id] ) ) {
					$title = $this->titles[$link_id];
					$title = $this->encodeAmpsAndAngles($title);
					$result .=  " title=\"$title\"";
				}
				$link_text = $this->runSpanGamut($link_text);
				$result .= ">$link_text</a>";
				$result = $this->hashSpan($result);
			}
			else {
				$result = $whole_match;
			}
			return $result;
		}
		function _doAnchors_inline_callback($matches) {
			$whole_match	=  $matches[1];
			$link_text		=  $this->runSpanGamut($matches[2]);
			$url			=  $matches[3];
			$title			=& $matches[6];
			$url = $this->encodeAmpsAndAngles($url);
			$result = "<a href=\"$url\"";
			if (isset($title)) {
				$title = str_replace('"', '&quot;', $title);
				$title = $this->encodeAmpsAndAngles($title);
				$result .=  " title=\"$title\"";
			}
			$link_text = $this->runSpanGamut($link_text);
			$result .= ">$link_text</a>";
			return $this->hashSpan($result);
		}
		function doImages($text) {
			$text = preg_replace_callback('{
			(				# wrap whole match in $1
			  !\[
				('.$this->nested_brackets.')		# alt text = $2
			  \]

			  [ ]?				# one optional space
			  (?:\n[ ]*)?		# one optional newline followed by spaces

			  \[
				(.*?)		# id = $3
			  \]

			)
			}xs',
			array(&$this, '_doImages_reference_callback'), $text);
			$text = preg_replace_callback('{
			(				# wrap whole match in $1
			  !\[
				('.$this->nested_brackets.')		# alt text = $2
			  \]
			  \s?			# One optional whitespace character
			  \(			# literal paren
				[ \t]*
				<?(\S+?)>?	# src url = $3
				[ \t]*
				(			# $4
				  ([\'"])	# quote char = $5
				  (.*?)		# title = $6
				  \5		# matching quote
				  [ \t]*
				)?			# title is optional
			  \)
			)
			}xs',
			array(&$this, '_doImages_inline_callback'), $text);
			return $text;
		}
		function _doImages_reference_callback($matches) {
			$whole_match = $matches[1];
			$alt_text    = $matches[2];
			$link_id     = strtolower($matches[3]);
			if ($link_id == "") {
				$link_id = strtolower($alt_text); # for shortcut links like ![this][].
			}
			$alt_text = str_replace('"', '&quot;', $alt_text);
			if (isset($this->urls[$link_id])) {
				$url = $this->urls[$link_id];
				$result = "<img src=\"$url\" alt=\"$alt_text\"";
				if (isset($this->titles[$link_id])) {
					$title = $this->titles[$link_id];
					$result .=  " title=\"$title\"";
				}
				$result .= $this->empty_element_suffix;
				$result = $this->hashSpan($result);
			}
			else {
				$result = $whole_match;
			}
			return $result;
		}
		function _doImages_inline_callback($matches) {
			$whole_match	= $matches[1];
			$alt_text		= $matches[2];
			$url			= $matches[3];
			$title			=& $matches[6];
			$alt_text = str_replace('"', '&quot;', $alt_text);
			$result = "<img src=\"$url\" alt=\"$alt_text\"";
			if (isset($title)) {
				$title = str_replace('"', '&quot;', $title);
				$result .=  " title=\"$title\""; # $title already quoted
			}
			$result .= $this->empty_element_suffix;
			return $this->hashSpan($result);
		}
		function doHeaders($text) {
			$text = preg_replace_callback('{ ^(.+)[ \t]*\n=+[ \t]*\n+ }mx',
			array(&$this, '_doHeaders_callback_setext_h1'), $text);
			$text = preg_replace_callback('{ ^(.+)[ \t]*\n-+[ \t]*\n+ }mx',
			array(&$this, '_doHeaders_callback_setext_h2'), $text);
			$text = preg_replace_callback('{
				^(\#{1,6})	# $1 = string of #\'s
				[ \t]*
				(.+?)		# $2 = Header text
				[ \t]*
				\#*			# optional closing #\'s (not counted)
				\n+
			}xm',
			array(&$this, '_doHeaders_callback_atx'), $text);
			return $text;
		}
		function _doHeaders_callback_setext_h1($matches) {
			$block = "<h1>".$this->runSpanGamut($matches[1])."</h1>";
			return "\n" . $this->hashBlock($block) . "\n\n";
		}
		function _doHeaders_callback_setext_h2($matches) {
			$block = "<h2>".$this->runSpanGamut($matches[1])."</h2>";
			return "\n" . $this->hashBlock($block) . "\n\n";
		}
		function _doHeaders_callback_atx($matches) {
			$level = strlen($matches[1]);
			$block = "<h$level>".$this->runSpanGamut($matches[2])."</h$level>";
			return "\n" . $this->hashBlock($block) . "\n\n";
		}
		function doLists($text) {
			$less_than_tab = $this->tab_width - 1;
			$marker_ul  = '[*+-]';
			$marker_ol  = '\d+[.]';
			$marker_any = "(?:$marker_ul|$marker_ol)";
			$markers = array($marker_ul, $marker_ol);
			foreach ($markers as $marker) {
				$whole_list = '
				(								# $1 = whole list
				  (								# $2
					[ ]{0,'.$less_than_tab.'}
					('.$marker.')				# $3 = first list item marker
					[ \t]+
				  )
				  (?s:.+?)
				  (								# $4
					  \z
					|
					  \n{2,}
					  (?=\S)
					  (?!						# Negative lookahead for another list item marker
						[ \t]*
						'.$marker.'[ \t]+
					  )
				  )
				)
			';
			if ($this->list_level) {
				$text = preg_replace_callback('{
						^
						'.$whole_list.'
					}mx',
					array(&$this, '_doLists_callback'), $text);
			}
			else {
				$text = preg_replace_callback('{
						(?:(?<=\n)\n|\A\n?) # Must eat the newline
						'.$whole_list.'
					}mx',
					array(&$this, '_doLists_callback'), $text);
			}
			}
			return $text;
		}
		function _doLists_callback($matches) {
			$marker_ul  = '[*+-]';
			$marker_ol  = '\d+[.]';
			$marker_any = "(?:$marker_ul|$marker_ol)";
			$list = $matches[1];
			$list_type = preg_match("/$marker_ul/", $matches[3]) ? "ul" : "ol";
			$marker_any = ( $list_type == "ul" ? $marker_ul : $marker_ol );
			$list .= "\n";
			$result = $this->processListItems($list, $marker_any);
			$result = $this->hashBlock("<$list_type>\n" . $result . "</$list_type>");
			return "\n". $result ."\n\n";
		}
		var $list_level = 0;
		function processListItems($list_str, $marker_any) {
		$this->list_level++;
		$list_str = preg_replace("/\n{2,}\\z/", "\n", $list_str);
		$list_str = preg_replace_callback('{
			(\n)?							# leading line = $1
			(^[ \t]*)						# leading whitespace = $2
			('.$marker_any.') [ \t]+		# list marker = $3
			((?s:.+?))						# list item text   = $4
			(?:(\n+(?=\n))|\n)				# tailing blank line = $5
			(?= \n* (\z | \2 ('.$marker_any.') [ \t]+))
			}xm',
			array(&$this, '_processListItems_callback'), $list_str);
		$this->list_level--;
		return $list_str;
	}
	function _processListItems_callback($matches) {
		$item = $matches[4];
		$leading_line =& $matches[1];
		$leading_space =& $matches[2];
		$tailing_blank_line =& $matches[5];
		if ($leading_line || $tailing_blank_line ||
			preg_match('/\n{2,}/', $item))
		{
			$item = $this->runBlockGamut($this->outdent($item)."\n");
		}
		else {
			$item = $this->doLists($this->outdent($item));
			$item = preg_replace('/\n+$/', '', $item);
			$item = $this->runSpanGamut($item);
		}
		return "<li>" . $item . "</li>\n";
	}
	function doCodeBlocks($text) {
		$text = preg_replace_callback('{
				(?:\n\n|\A)
				(	            # $1 = the code block -- one or more lines, starting with a space/tab
				  (?:
					(?:[ ]{'.$this->tab_width.'} | \t)  # Lines must start with a tab or a tab-width of spaces
					.*\n+
				  )+
				)
				((?=^[ ]{0,'.$this->tab_width.'}\S)|\Z)	# Lookahead for non-space at line-start, or end of doc
			}xm',
			array(&$this, '_doCodeBlocks_callback'), $text);
		return $text;
	}
	function _doCodeBlocks_callback($matches) {
		$codeblock = $matches[1];
		$codeblock = $this->encodeCode($this->outdent($codeblock));
		$codeblock = preg_replace(array('/\A\n+/', '/\n+\z/'), '', $codeblock);
		$result = "\n\n".$this->hashBlock("<pre><code>" . $codeblock . "\n</code></pre>")."\n\n";
		return $result;
	}
	function doCodeSpans($text) {
		$text = preg_replace_callback('@
				(?<!\\\)	# Character before opening ` can\'t be a backslash
				(`+)		# $1 = Opening run of `
				(.+?)		# $2 = The code block
				(?<!`)
				\1			# Matching closer
				(?!`)
			@xs',
			array(&$this, '_doCodeSpans_callback'), $text);
		return $text;
	}
	function _doCodeSpans_callback($matches) {
		$c = $matches[2];
		$c = preg_replace('@^[ \t]*@', '', $c); # leading whitespace
		$c = preg_replace('/[ \t]*$/', '', $c); # trailing whitespace
		$c = $this->encodeCode($c);
		return $this->hashSpan("<code>$c</code>");
	}
	function encodeCode($_) {
		$_ = str_replace('&', '&amp;', $_);
		$_ = str_replace(array('<',    '>'),array('&lt;', '&gt;'), $_);
		return $_;
	}
	function doItalicsAndBold($text) {
		$text = preg_replace_callback('{
				(						# $1: Marker
					(?<!\*\*) \* |		#     (not preceded by two chars of
					(?<!__)   _			#      the same marker)
				)
				\1
				(?=\S) 					# Not followed by whitespace
				(?!\1\1)				#   or two others marker chars.
				(						# $2: Content
					(?:
						[^*_]+?			# Anthing not em markers.
					|
										# Balence any regular emphasis inside.
						\1 (?=\S) .+? (?<=\S) \1
					|
						(?! \1 ) .		# Allow unbalenced * and _.
					)+?
				)
				(?<=\S) \1\1			# End mark not preceded by whitespace.
			}sx',
			array(&$this, '_doItalicAndBold_strong_callback'), $text);
		# Then <em>:
		$text = preg_replace_callback(
			'{ ( (?<!\*)\* | (?<!_)_ ) (?=\S) (?! \1) (.+?) (?<=\S) \1 }sx',
			array(&$this, '_doItalicAndBold_em_callback'), $text);

		return $text;
	}
	function _doItalicAndBold_em_callback($matches) {
		$text = $matches[2];
		$text = $this->runSpanGamut($text);
		return $this->hashSpan("<em>$text</em>");
	}
	function _doItalicAndBold_strong_callback($matches) {
		$text = $matches[2];
		$text = $this->runSpanGamut($text);
		return $this->hashSpan("<strong>$text</strong>");
	}
	function doBlockQuotes($text) {
		$text = preg_replace_callback('/
			  (								# Wrap whole match in $1
				(
				  ^[ \t]*>[ \t]?			# ">" at the start of a line
					.+\n					# rest of the first line
				  (.+\n)*					# subsequent consecutive lines
				  \n*						# blanks
				)+
			  )
			/xm',
			array(&$this, '_doBlockQuotes_callback'), $text);

		return $text;
	}
	function _doBlockQuotes_callback($matches) {
		$bq = $matches[1];
		# trim one level of quoting - trim whitespace-only lines
		$bq = preg_replace(array('/^[ \t]*>[ \t]?/m', '/^[ \t]+$/m'), '', $bq);
		$bq = $this->runBlockGamut($bq);		# recurse

		$bq = preg_replace('/^/m', "  ", $bq);
		# These leading spaces cause problem with <pre> content,
		# so we need to fix that:
		$bq = preg_replace_callback('{(\s*<pre>.+?</pre>)}sx',
			array(&$this, '_DoBlockQuotes_callback2'), $bq);
		return "\n". $this->hashBlock("<blockquote>\n$bq\n</blockquote>")."\n\n";
	}
	function _doBlockQuotes_callback2($matches) {
		$pre = $matches[1];
		$pre = preg_replace('/^  /m', '', $pre);
		return $pre;
	}
	function formParagraphs($text) {
		$text = preg_replace(array('/\A\n+/', '/\n+\z/'), '', $text);
		$grafs = preg_split('/\n{2,}/', $text, -1, PREG_SPLIT_NO_EMPTY);
		foreach ($grafs as $key => $value) {
			if (!isset( $this->html_blocks[$value] )) {
				$value = $this->runSpanGamut($value);
				$value = preg_replace('/^([ \t]*)/', "<p>", $value);
				$value .= "</p>";
				$grafs[$key] = $this->unhash($value);
			}
		}
		foreach ($grafs as $key => $graf) {
			# Modify elements of @grafs in-place...
			if (isset($this->html_blocks[$graf])) {
				$block = $this->html_blocks[$graf];
				$graf = $block;
				$grafs[$key] = $graf;
			}
		}
		return implode("\n\n", $grafs);
	}
	function encodeAmpsAndAngles($text) {
		$text = preg_replace('/&(?!#?[xX]?(?:[0-9a-fA-F]+|\w+);)/',
							 '&amp;', $text);;
		$text = preg_replace('{<(?![a-z/?\$!%])}i', '&lt;', $text);
		return $text;
	}
	function encodeBackslashEscapes($text) {
		return str_replace(array_keys($this->backslash_escape_table),
						   array_values($this->backslash_escape_table), $text);
	}
	function doAutoLinks($text) {
		$text = preg_replace_callback('{<((https?|ftp|dict):[^\'">\s]+)>}',
			array(&$this, '_doAutoLinks_url_callback'), $text);
		$text = preg_replace_callback('{
			<
			(?:mailto:)?
			(
				[-.\w\x80-\xFF]+
				\@
				[-a-z0-9\x80-\xFF]+(\.[-a-z0-9\x80-\xFF]+)*\.[a-z]+
			)
			>
			}xi',
			array(&$this, '_doAutoLinks_email_callback'), $text);
		return $text;
	}
	function _doAutoLinks_url_callback($matches) {
		$url = $this->encodeAmpsAndAngles($matches[1]);
		$link = "<a href=\"$url\">$url</a>";
		return $this->hashSpan($link);
	}
	function _doAutoLinks_email_callback($matches) {
		$address = $matches[1];
		$address = $this->unescapeSpecialChars($address);
		$link = $this->encodeEmailAddress($address);
		return $this->hashSpan($link);
	}
	function encodeEmailAddress($addr) {
		$addr = "mailto:" . $addr;
		$chars = preg_split('/(?<!^)(?!$)/', $addr);
		$seed = (int)abs(crc32($addr) / strlen($addr));
		foreach ($chars as $key => $char) {
			$ord = ord($char);
			# Ignore non-ascii chars.
			if ($ord < 128) {
				$r = ($seed * (1 + $key)) % 100;
				if ($r > 90 && $char != '@');
				else if ($r < 45) $chars[$key] = '&#x'.dechex($ord).';';
				else              $chars[$key] = '&#'.$ord.';';
			}
		}
		$addr = implode('', $chars);
		$text = implode('', array_slice($chars, 7));
		$addr = "<a href=\"$addr\">$text</a>";
		return $addr;
	}
	function unescapeSpecialChars($text) {
		return str_replace(array_values($this->escape_table),
						   array_keys($this->escape_table), $text);
	}
	function tokenizeHTML($str) {
		$tokens = array();
		while ($str != "") {
			$parts = preg_split('{
				(
					(?<![`\\\\])
					`+						# code span marker
				|
					<!--    .*?     -->		# comment
				|
					<\?.*?\?> | <%.*?%>		# processing instruction
				|
					<[/!$]?[-a-zA-Z0-9:]+	# regular tags
					(?:
						\s
						(?>[^"\'>]+|"[^"]*"|\'[^\']*\')*
					)?
					>
				)
				}xs', $str, 2, PREG_SPLIT_DELIM_CAPTURE);
			if ($parts[0] != "") {
				$tokens[] = array('text', $parts[0]);
			}

			# Check if we reach the end.
			if (count($parts) < 3) {
				break;
			}

			# Create token from tag or code span.
			if ($parts[1]{0} == "`") {
				$tokens[] = array('text', $parts[1]);
				$str = $parts[2];

				# Skip the whole code span, pass as text token.
				if (preg_match('/^(.*(?<!`\\\\)'.$parts[1].'(?!`))(.*)$/sm',
					$str, $matches))
				{
					$tokens[] = array('text', $matches[1]);
					$str = $matches[2];
				}
			} else {
				$tokens[] = array('tag', $parts[1]);
				$str = $parts[2];
			}
		}

		return $tokens;
	}
	function outdent($text) {
		return preg_replace("/^(\\t|[ ]{1,$this->tab_width})/m", "", $text);
	}
	var $utf8_strlen = 'mb_strlen';
	function detab($text) {
		$strlen = $this->utf8_strlen;
		$lines = explode("\n", $text);
		$text = "";
		foreach ($lines as $line) {
			# Split in blocks.
			$blocks = explode("\t", $line);
			# Add each blocks to the line.
			$line = $blocks[0];
			unset($blocks[0]); # Do not add first block twice.
			foreach ($blocks as $block) {
				# Calculate amount of space, insert spaces, insert block.
				$amount = $this->tab_width -
					$strlen($line, 'UTF-8') % $this->tab_width;
				$line .= str_repeat(" ", $amount) . $block;
			}
			$text .= "$line\n";
		}
		return $text;
	}
	function _initDetab() {
		if (function_exists($this->utf8_strlen)) return;
		$this->utf8_strlen = 'Markdown_UTF8_strlen';
		if (function_exists($this->utf8_strlen)) return;
		function Markdown_UTF8_strlen($text) {
			return preg_match_all('@[\x00-\xBF]|[\xC0-\xFF][\x80-\xBF]*@',
				$text, $m);
		}
	}
	function unhash($text) {
		return str_replace(array_keys($this->html_hashes),
						   array_values($this->html_hashes), $text);
	}

}

class MarkdownExtra_Parser extends Markdown_Parser {
	var $fn_id_prefix = "";
	var $fn_link_title = MARKDOWN_FN_LINK_TITLE;
	var $fn_backlink_title = MARKDOWN_FN_BACKLINK_TITLE;
	var $fn_link_class = MARKDOWN_FN_LINK_CLASS;
	var $fn_backlink_class = MARKDOWN_FN_BACKLINK_CLASS;

	function MarkdownExtra_Parser() {
	#
	# Constructor function. Initialize the parser object.
	#
		# Add extra escapable characters before parent constructor
		# initialize the table.
		$this->escape_chars .= ':|';

		# Insert extra document, block, and span transformations.
		# Parent constructor will do the sorting.
		$this->document_gamut += array(
			"stripFootnotes"     => 15,
			"stripAbbreviations" => 25,
			"appendFootnotes"    => 50,
			);
		$this->block_gamut += array(
			"doTables"           => 15,
			"doDefLists"         => 45,
			);
		$this->span_gamut += array(
			"doFootnotes"        =>  4,
			"doAbbreviations"    =>  5,
			);

		parent::Markdown_Parser();
	}


	# Extra hashes used during extra transformations.
	var $footnotes = array();
	var $footnotes_ordered = array();
	var $abbr_desciptions = array();
	var $abbr_matches = array();
	var $html_cleans = array();

	function transform($text) {
		$this->footnotes = array();
		$this->footnotes_ordered = array();
		$this->abbr_desciptions = array();
		$this->abbr_matches = array();
		$this->html_cleans = array();

		return parent::transform($text);
	}
	var $block_tags = 'p|div|h[1-6]|blockquote|pre|table|dl|ol|ul|address|form|fieldset|iframe|hr|legend';
	var $context_block_tags = 'script|noscript|math|ins|del';
	var $contain_span_tags = 'p|h[1-6]|li|dd|dt|td|th|legend|address';
	var $clean_tags = 'script|math';
	var $auto_close_tags = 'hr|img';


	function hashHTMLBlocks($text) {
		list($text, ) = $this->_hashHTMLBlocks_inMarkdown($text);

		return $text;
	}
	function _hashHTMLBlocks_inMarkdown($text, $indent = 0,
										$enclosing_tag = '', $span = false)
	{
		if ($text === '') return array('', '');

		# Regex to check for the presense of newlines around a block tag.
		$newline_match_before = '/(?:^\n?|\n\n)*$/';
		$newline_match_after =
			'{
				^						# Start of text following the tag.
				(?:[ ]*<!--.*?-->)?		# Optional comment.
				[ ]*\n					# Must be followed by newline.
			}xs';

		# Regex to match any tag.
		$block_tag_match =
			'{
				(					# $2: Capture hole tag.
					</?					# Any opening or closing tag.
						(?:				# Tag name.
							'.$this->block_tags.'			|
							'.$this->context_block_tags.'	|
							'.$this->clean_tags.'        	|
							(?!\s)'.$enclosing_tag.'
						)
						\s*				# Whitespace.
						(?:
							".*?"		|	# Double quotes (can contain `>`)
							\'.*?\'   	|	# Single quotes (can contain `>`)
							.+?				# Anything but quotes and `>`.
						)*?
					>					# End of tag.
				|
					<!--    .*?     -->	# HTML Comment
				|
					<\?.*?\?> | <%.*?%>	# Processing instruction
				|
					<!\[CDATA\[.*?\]\]>	# CData Block
				)
			}xs';


		$depth = 0;		# Current depth inside the tag tree.
		$parsed = "";	# Parsed text that will be returned.
		do {
			$parts = preg_split($block_tag_match, $text, 2,
								PREG_SPLIT_DELIM_CAPTURE);
			if ($span) {
				$newline = $this->hashSpan("") . "\n";
				$parts[0] = str_replace("\n", $newline, $parts[0]);
			}

			$parsed .= $parts[0]; # Text before current tag.

			if (count($parts) < 3) {
				$text = "";
				break;
			}
			$tag  = $parts[1]; # Tag to handle.
			$text = $parts[2]; # Remaining text after current tag.
			if (# Find current paragraph
				preg_match('/(?>^\n?|\n\n)((?>.\n?)+?)$/', $parsed, $matches) &&
				(
				# Then match in it either a code block...
				preg_match('/^ {'.($indent+4).'}.*(?>\n {'.($indent+4).'}.*)*'.
							'(?!\n)$/', $matches[1], $x) ||
				# ...or unbalenced code span markers. (the regex matches balenced)
				!preg_match('/^(?>[^`]+|(`+)(?>[^`]+|(?!\1[^`])`)*?\1(?!`))*$/s',
							 $matches[1])
				))
			{
				$parsed .= $tag{0};
				$text = substr($tag, 1) . $text; # Put back $tag minus first char.
			}
			else if (preg_match("{^<(?:$this->block_tags)\b}", $tag) ||
				(	preg_match("{^<(?:$this->context_block_tags)\b}", $tag) &&
					preg_match($newline_match_before, $parsed) &&
					preg_match($newline_match_after, $text)	)
				)
			{
				list($block_text, $text) =
					$this->_hashHTMLBlocks_inHTML($tag . $text, "hashBlock", true);
				$parsed .= "\n\n$block_text\n\n";
			}
			else if (preg_match("{^<(?:$this->clean_tags)\b}", $tag) ||
				$tag{1} == '!' || $tag{1} == '?')
			{
				list($block_text, $text) =
					$this->_hashHTMLBlocks_inHTML($tag . $text, "hashClean", false);

				$parsed .= $block_text;
			}
			else if ($enclosing_tag !== '' &&
				# Same name as enclosing tag.
				preg_match("{^</?(?:$enclosing_tag)\b}", $tag))
			{
				if ($tag{1} == '/')						$depth--;
				else if ($tag{strlen($tag)-2} != '/')	$depth++;
				if ($depth < 0) {
					$text = $tag . $text;
					break;
				}

				$parsed .= $tag;
			}
			else {
				$parsed .= $tag;
			}
		} while ($depth >= 0);

		return array($parsed, $text);
	}
	function _hashHTMLBlocks_inHTML($text, $hash_method, $md_attr) {
		if ($text === '') return array('', '');
		$markdown_attr_match = '
			{
				\s*			# Eat whitespace before the `markdown` attribute
				markdown
				\s*=\s*
				(["\'])		# $1: quote delimiter
				(.*?)		# $2: attribute value
				\1			# matching delimiter
			}xs';

		# Regex to match any tag.
		$tag_match = '{
				(					# $2: Capture hole tag.
					</?					# Any opening or closing tag.
						[\w:$]+			# Tag name.
						\s*				# Whitespace.
						(?:
							".*?"		|	# Double quotes (can contain `>`)
							\'.*?\'   	|	# Single quotes (can contain `>`)
							.+?				# Anything but quotes and `>`.
						)*?
					>					# End of tag.
				|
					<!--    .*?     -->	# HTML Comment
				|
					<\?.*?\?> | <%.*?%>	# Processing instruction
				|
					<!\[CDATA\[.*?\]\]>	# CData Block
				)
			}xs';

		$original_text = $text;		# Save original text in case of faliure.
		$depth		= 0;	# Current depth inside the tag tree.
		$block_text	= "";	# Temporary text holder for current text.
		$parsed		= "";	# Parsed text that will be returned.
		if (preg_match("/^<([\w:$]*)\b/", $text, $matches))
			$base_tag_name = $matches[1];
		do {
			$parts = preg_split($tag_match, $text, 2, PREG_SPLIT_DELIM_CAPTURE);
			if (count($parts) < 3) {
				return array($original_text{0}, substr($original_text, 1));
			}
			$block_text .= $parts[0]; # Text before current tag.
			$tag         = $parts[1]; # Tag to handle.
			$text        = $parts[2]; # Remaining text after current tag.
			if (preg_match("{^</?(?:$this->auto_close_tags)\b}", $tag) ||
				$tag{1} == '!' || $tag{1} == '?')
			{
				$block_text .= $tag;
			}
			else {
				if (preg_match("{^</?$base_tag_name\b}", $tag)) {
					if ($tag{1} == '/')						$depth--;
					else if ($tag{strlen($tag)-2} != '/')	$depth++;
				}
				if ($md_attr &&
					preg_match($markdown_attr_match, $tag, $attr_matches) &&
					preg_match('/^1|block|span$/', $attr_matches[2]))
				{
					$tag = preg_replace($markdown_attr_match, '', $tag);
					$this->mode = $attr_matches[2];
					$span_mode = $this->mode == 'span' || $this->mode != 'block' &&
						preg_match("{^<(?:$this->contain_span_tags)\b}", $tag);
					preg_match('/(?:^|\n)( *?)(?! ).*?$/', $block_text, $matches);
					$indent = strlen($matches[1]);
					$block_text .= $tag;
					$parsed .= $this->$hash_method($block_text);
					preg_match('/^<([\w:$]*)\b/', $tag, $matches);
					$tag_name = $matches[1];
					list ($block_text, $text)
						= $this->_hashHTMLBlocks_inMarkdown($text, $indent,
														$tag_name, $span_mode);
					if ($indent > 0) {
						$block_text = preg_replace("/^[ ]{1,$indent}/m", "",
													$block_text);
					}
					if (!$span_mode)	$parsed .= "\n\n$block_text\n\n";
					else				$parsed .= "$block_text";
					$block_text = "";
				}
				else $block_text .= $tag;
			}
		} while ($depth > 0);
		$parsed .= $this->$hash_method($block_text);
		return array($parsed, $text);
	}

	function hashClean($text) {
		$text = $this->unhash($text);
		$key = md5($text);
		$this->html_cleans[$key] = $text;
		$this->html_hashes[$key] = $text;
		return $key;
	}


	function doHeaders($text) {
		$text = preg_replace_callback(
			'{ (^.+?) (?:[ ]+\{\#([-_:a-zA-Z0-9]+)\})? [ \t]*\n=+[ \t]*\n+ }mx',
			array(&$this, '_doHeaders_callback_setext_h1'), $text);
		$text = preg_replace_callback(
			'{ (^.+?) (?:[ ]+\{\#([-_:a-zA-Z0-9]+)\})? [ \t]*\n-+[ \t]*\n+ }mx',
			array(&$this, '_doHeaders_callback_setext_h2'), $text);
		$text = preg_replace_callback('{
				^(\#{1,6})	# $1 = string of #\'s
				[ \t]*
				(.+?)		# $2 = Header text
				[ \t]*
				\#*			# optional closing #\'s (not counted)
				(?:[ ]+\{\#([-_:a-zA-Z0-9]+)\})? # id attribute
				[ \t]*
				\n+
			}xm',
			array(&$this, '_doHeaders_callback_atx'), $text);

		return $text;
	}
	function _doHeaders_attr($attr) {
		if (empty($attr))  return "";
		return " id=\"$attr\"";
	}
	function _doHeaders_callback_setext_h1($matches) {
		$attr  = $this->_doHeaders_attr($id =& $matches[2]);
		$block = "<h1$attr>".$this->runSpanGamut($matches[1])."</h1>";
		return "\n" . $this->hashBlock($block) . "\n\n";
	}
	function _doHeaders_callback_setext_h2($matches) {
		$attr  = $this->_doHeaders_attr($id =& $matches[2]);
		$block = "<h2$attr>".$this->runSpanGamut($matches[1])."</h2>";
		return "\n" . $this->hashBlock($block) . "\n\n";
	}
	function _doHeaders_callback_atx($matches) {
		$level = strlen($matches[1]);
		$attr  = $this->_doHeaders_attr($id =& $matches[3]);
		$block = "<h$level$attr>".$this->runSpanGamut($matches[2])."</h$level>";
		return "\n" . $this->hashBlock($block) . "\n\n";
	}

	function doTables($text) {
		$less_than_tab = $this->tab_width - 1;
		$text = preg_replace_callback('
			{
				^							# Start of a line
				[ ]{0,'.$less_than_tab.'}	# Allowed whitespace.
				[|]							# Optional leading pipe (present)
				(.+) \n						# $1: Header row (at least one pipe)

				[ ]{0,'.$less_than_tab.'}	# Allowed whitespace.
				[|] ([ ]*[-:]+[-| :]*) \n	# $2: Header underline

				(							# $3: Cells
					(?:
						[ ]*				# Allowed whitespace.
						[|] .* \n			# Row content.
					)*
				)
				(?=\n|\Z)					# Stop at final double newline.
			}xm',
			array(&$this, '_doTable_leadingPipe_callback'), $text);
		$text = preg_replace_callback('
			{
				^							# Start of a line
				[ ]{0,'.$less_than_tab.'}	# Allowed whitespace.
				(\S.*[|].*) \n				# $1: Header row (at least one pipe)

				[ ]{0,'.$less_than_tab.'}	# Allowed whitespace.
				([-:]+[ ]*[|][-| :]*) \n	# $2: Header underline

				(							# $3: Cells
					(?:
						.* [|] .* \n		# Row content
					)*
				)
				(?=\n|\Z)					# Stop at final double newline.
			}xm',
			array(&$this, '_DoTable_callback'), $text);
		return $text;
	}
	function _doTable_leadingPipe_callback($matches) {
		$head		= $matches[1];
		$underline	= $matches[2];
		$content	= $matches[3];
		$content	= preg_replace('/^ *[|]/m', '', $content);
		return $this->_doTable_callback(array($matches[0], $head, $underline, $content));
	}
	function _doTable_callback($matches) {
		$head		= $matches[1];
		$underline	= $matches[2];
		$content	= $matches[3];
		$head		= preg_replace('/[|] *$/m', '', $head);
		$underline	= preg_replace('/[|] *$/m', '', $underline);
		$content	= preg_replace('/[|] *$/m', '', $content);
		$separators	= preg_split('@ *[|] *@', $underline);
		foreach ($separators as $n => $s) {
			if (preg_match('/^ *-+: *$/', $s))		$attr[$n] = ' align="right"';
			else if (preg_match('/^ *:-+: *$/', $s))$attr[$n] = ' align="center"';
			else if (preg_match('/^ *:-+ *$/', $s))	$attr[$n] = ' align="left"';
			else									$attr[$n] = '';
		}
		$head	= $this->doCodeSpans($head);
		$headers	= preg_split('@ *[|] *@', $head);
		$col_count	= count($headers);
		$text = "<table>\n";
		$text .= "<thead>\n";
		$text .= "<tr>\n";
		foreach ($headers as $n => $header)
			$text .= "  <th$attr[$n]>".$this->runSpanGamut(trim($header))."</th>\n";
		$text .= "</tr>\n";
		$text .= "</thead>\n";
		$rows = explode("\n", trim($content, "\n"));
		$text .= "<tbody>\n";
		foreach ($rows as $row) {
			$row = $this->doCodeSpans($row);
			$row_cells = preg_split('@ *[|] *@', $row, $col_count);
			$row_cells = array_pad($row_cells, $col_count, '');
			$text .= "<tr>\n";
			foreach ($row_cells as $n => $cell)
				$text .= "  <td$attr[$n]>".$this->runSpanGamut(trim($cell))."</td>\n";
			$text .= "</tr>\n";
		}
		$text .= "</tbody>\n";
		$text .= "</table>";
		return $this->hashBlock($text) . "\n";
	}

	function doDefLists($text) {
		$less_than_tab = $this->tab_width - 1;
		$whole_list = '
			(								# $1 = whole list
			  (								# $2
				[ ]{0,'.$less_than_tab.'}
				((?>.*\S.*\n)+)				# $3 = defined term
				\n?
				[ ]{0,'.$less_than_tab.'}:[ ]+ # colon starting definition
			  )
			  (?s:.+?)
			  (								# $4
				  \z
				|
				  \n{2,}
				  (?=\S)
				  (?!						# Negative lookahead for another term
					[ ]{0,'.$less_than_tab.'}
					(?: \S.*\n )+?			# defined term
					\n?
					[ ]{0,'.$less_than_tab.'}:[ ]+ # colon starting definition
				  )
				  (?!						# Negative lookahead for another definition
					[ ]{0,'.$less_than_tab.'}:[ ]+ # colon starting definition
				  )
			  )
			)
		'; // mx

		$text = preg_replace_callback('{
				(?:(?<=\n\n)|\A\n?)
				'.$whole_list.'
			}mx',
			array(&$this, '_doDefLists_callback'), $text);

		return $text;
	}
	function _doDefLists_callback($matches) {
		$list = $matches[1];
		$result = trim($this->processDefListItems($list));
		$result = "<dl>\n" . $result . "\n</dl>";
		return $this->hashBlock($result) . "\n\n";
	}

	function processDefListItems($list_str) {
		$less_than_tab = $this->tab_width - 1;
		$list_str = preg_replace("/\n{2,}\\z/", "\n", $list_str);
		$list_str = preg_replace_callback('{
			(?:\n\n+|\A\n?)					# leading line
			(								# definition terms = $1
				[ ]{0,'.$less_than_tab.'}	# leading whitespace
				(?![:][ ]|[ ])				# negative lookahead for a definition
											#   mark (colon) or more whitespace.
				(?: \S.* \n)+?				# actual term (not whitespace).
			)
			(?=\n?[ ]{0,3}:[ ])				# lookahead for following line feed
											#   with a definition mark.
			}xm',
			array(&$this, '_processDefListItems_callback_dt'), $list_str);

		# Process actual definitions.
		$list_str = preg_replace_callback('{
			\n(\n+)?						# leading line = $1
			[ ]{0,'.$less_than_tab.'}		# whitespace before colon
			[:][ ]+							# definition mark (colon)
			((?s:.+?))						# definition text = $2
			(?= \n+ 						# stop at next definition mark,
				(?:							# next term or end of text
					[ ]{0,'.$less_than_tab.'} [:][ ]	|
					<dt> | \z
				)
			)
			}xm',
			array(&$this, '_processDefListItems_callback_dd'), $list_str);

		return $list_str;
	}
	function _processDefListItems_callback_dt($matches) {
		$terms = explode("\n", trim($matches[1]));
		$text = '';
		foreach ($terms as $term) {
			$term = $this->runSpanGamut(trim($term));
			$text .= "\n<dt>" . $term . "</dt>";
		}
		return $text . "\n";
	}
	function _processDefListItems_callback_dd($matches) {
		$leading_line	= $matches[1];
		$def			= $matches[2];
		if ($leading_line || preg_match('/\n{2,}/', $def)) {
			$def = $this->runBlockGamut($this->outdent($def . "\n\n"));
			$def = "\n". $def ."\n";
		}
		else {
			$def = rtrim($def);
			$def = $this->runSpanGamut($this->outdent($def));
		}

		return "\n<dd>" . $def . "</dd>\n";
	}

	function doItalicsAndBold($text) {
		$text = preg_replace_callback(array(
			'{
				(						# $1: Marker
					(?<![a-zA-Z0-9])	# Not preceded by alphanum
					(?<!__)				#	or by two marker chars.
					__
				)
				(?=\S) 					# Not followed by whitespace
				(?!__)					#   or two others marker chars.
				(						# $2: Content
					(?:
						[^_]+?			# Anthing not em markers.
					|
										# Balence any regular _ emphasis inside.
						(?<![a-zA-Z0-9]) _ (?=\S) (.+?)
						(?<=\S) _ (?![a-zA-Z0-9])
					|
						___+
					)+?
				)
				(?<=\S) __				# End mark not preceded by whitespace.
				(?![a-zA-Z0-9])			# Not followed by alphanum
				(?!__)					#   or two others marker chars.
			}sx',
			'{
				( (?<!\*\*) \*\* )		# $1: Marker (not preceded by two *)
				(?=\S) 					# Not followed by whitespace
				(?!\1)					#   or two others marker chars.
				(						# $2: Content
					(?:
						[^*]+?			# Anthing not em markers.
					|
										# Balence any regular * emphasis inside.
						\* (?=\S) (.+?) (?<=\S) \*
					)+?
				)
				(?<=\S) \*\*			# End mark not preceded by whitespace.
			}sx',
			),
			array(&$this, '_doItalicAndBold_strong_callback'), $text);
		# Then <em>:
		$text = preg_replace_callback(array(
			'{ ( (?<![a-zA-Z0-9])(?<!_)_ ) (?=\S) (?! \1) (.+?) (?<=\S) \1(?![a-zA-Z0-9]) }sx',
			'{ ( (?<!\*)\* ) (?=\S) (?! \1) (.+?) (?<=\S) \1 }sx',
			),
			array(&$this, '_doItalicAndBold_em_callback'), $text);

		return $text;
	}

	function formParagraphs($text) {
		$text = preg_replace(array('/\A\n+/', '/\n+\z/'), '', $text);
		$grafs = preg_split('/\n{2,}/', $text, -1, PREG_SPLIT_NO_EMPTY);
		foreach ($grafs as $key => $value) {
			$value = trim($this->runSpanGamut($value));
			$clean_key = $value;
			$block_key = substr($value, 0, 32);

			$is_p = (!isset($this->html_blocks[$block_key]) &&
					 !isset($this->html_cleans[$clean_key]));

			if ($is_p) {
				$value = "<p>$value</p>";
			}
			$grafs[$key] = $value;
		}
		$text = implode("\n\n", $grafs);
		$text = $this->unhash($text);

		return $text;
	}


	### Footnotes

	function stripFootnotes($text) {
		$less_than_tab = $this->tab_width - 1;
		$text = preg_replace_callback('{
			^[ ]{0,'.$less_than_tab.'}\[\^(.+?)\][ ]?:	# note_id = $1
			  [ \t]*
			  \n?					# maybe *one* newline
			(						# text = $2 (no blank lines allowed)
				(?:
					.+				# actual text
				|
					\n				# newlines but
					(?!\[\^.+?\]:\s)# negative lookahead for footnote marker.
					(?!\n+[ ]{0,3}\S)# ensure line is not blank and followed
									# by non-indented content
				)*
			)
			}xm',
			array(&$this, '_stripFootnotes_callback'),
			$text);
		return $text;
	}
	function _stripFootnotes_callback($matches) {
		$note_id = $matches[1];
		$this->footnotes[$note_id] = $this->outdent($matches[2]);
		return ''; # String that will replace the block
	}


	function doFootnotes($text) {
		$text = preg_replace('{\[\^(.+?)\]}', "a\0fn:\\1\0z", $text);
		return $text;
	}


	function appendFootnotes($text) {
		$text = preg_replace_callback('{a\0fn:(.*?)\0z}',
			array(&$this, '_appendFootnotes_callback'), $text);

		if (!empty($this->footnotes_ordered)) {
			$text .= "\n\n";
			$text .= "<div class=\"footnotes\">\n";
			$text .= "<hr". MARKDOWN_EMPTY_ELEMENT_SUFFIX ."\n";
			$text .= "<ol>\n\n";

			$attr = " rev=\"footnote\"";
			if ($this->fn_backlink_class != "") {
				$class = $this->fn_backlink_class;
				$class = $this->encodeAmpsAndAngles($class);
				$class = str_replace('"', '&quot;', $class);
				$attr .= " class=\"$class\"";
			}
			if ($this->fn_backlink_title != "") {
				$title = $this->fn_backlink_title;
				$title = $this->encodeAmpsAndAngles($title);
				$title = str_replace('"', '&quot;', $title);
				$attr .= " title=\"$title\"";
			}
			$num = 0;

			foreach ($this->footnotes_ordered as $note_id => $footnote) {
				$footnote .= "\n"; # Need to append newline before parsing.
				$footnote = $this->runBlockGamut("$footnote\n");

				$attr2 = str_replace("%%", ++$num, $attr);

				# Add backlink to last paragraph; create new paragraph if needed.
				$backlink = "<a href=\"#fnref:$note_id\"$attr2>&#8617;</a>";
				if (preg_match('{</p>$}', $footnote)) {
					$footnote = substr($footnote, 0, -4) . "&#160;$backlink</p>";
				} else {
					$footnote .= "\n\n<p>$backlink</p>";
				}

				$text .= "<li id=\"fn:$note_id\">\n";
				$text .= $footnote . "\n";
				$text .= "</li>\n\n";
			}

			$text .= "</ol>\n";
			$text .= "</div>";

			$text = preg_replace('{a\{fn:(.*?)\}z}', '[^\\1]', $text);
		}
		return $text;
	}
	function _appendFootnotes_callback($matches) {
		$node_id = $this->fn_id_prefix . $matches[1];

		# Create footnote marker only if it has a corresponding footnote *and*
		# the footnote hasn't been used by another marker.
		if (isset($this->footnotes[$node_id])) {
			# Transfert footnote content to the ordered list.
			$this->footnotes_ordered[$node_id] = $this->footnotes[$node_id];
			unset($this->footnotes[$node_id]);

			$num = count($this->footnotes_ordered);
			$attr = " rel=\"footnote\"";
			if ($this->fn_link_class != "") {
				$class = $this->fn_link_class;
				$class = $this->encodeAmpsAndAngles($class);
				$class = str_replace('"', '&quot;', $class);
				$attr .= " class=\"$class\"";
			}
			if ($this->fn_link_title != "") {
				$title = $this->fn_link_title;
				$title = $this->encodeAmpsAndAngles($title);
				$title = str_replace('"', '&quot;', $title);
				$attr .= " title=\"$title\"";
			}
			$attr = str_replace("%%", $num, $attr);

			return
				"<sup id=\"fnref:$node_id\">".
				"<a href=\"#fn:$node_id\"$attr>$num</a>".
				"</sup>";
		}

		return "[^".$matches[1]."]";
	}


	### Abbreviations ###

	function stripAbbreviations($text) {
		$less_than_tab = $this->tab_width - 1;

		# Link defs are in the form: [id]*: url "optional title"
		$text = preg_replace_callback('{
			^[ ]{0,'.$less_than_tab.'}\*\[(.+?)\][ ]?:	# abbr_id = $1
			(.*)					# text = $2 (no blank lines allowed)
			}xm',
			array(&$this, '_stripAbbreviations_callback'),
			$text);
		return $text;
	}
	function _stripAbbreviations_callback($matches) {
		$abbr_word = $matches[1];
		$abbr_desc = $matches[2];
		$this->abbr_matches[] = preg_quote($abbr_word);
		$this->abbr_desciptions[$abbr_word] = trim($abbr_desc);
		return ''; # String that will replace the block
	}


	function doAbbreviations($text) {
		if ($this->abbr_matches) {
			$regex = '{(?<!\w)(?:'. implode('|', $this->abbr_matches) .')(?!\w)}';

			$text = preg_replace_callback($regex,
				array(&$this, '_doAbbreviations_callback'), $text);
		}
		return $text;
	}
	function _doAbbreviations_callback($matches) {
		$abbr = $matches[0];
		if (isset($this->abbr_desciptions[$abbr])) {
			$desc = $this->abbr_desciptions[$abbr];
			if (empty($desc)) {
				return $this->hashSpan("<abbr>$abbr</abbr>");
			} else {
				$desc = $this->escapeSpecialCharsWithinTagAttributes($desc);
				return $this->hashSpan("<abbr title=\"$desc\">$abbr</abbr>");
			}
		} else {
			return $matches[0];
		}
	}

}

#
# SmartyPants Typographer  -  Smart typography for web sites
#
# PHP SmartyPants & Typographer
# Copyright (c) 2004-2006 Michel Fortin
# <http://www.michelf.com/>
#
# Original SmartyPants
# Copyright (c) 2003-2004 John Gruber
# <http://daringfireball.net/>
#


define( 'SMARTYPANTS_VERSION',            "1.5.1oo2" ); # Unreleased
define( 'SMARTYPANTSTYPOGRAPHER_VERSION', "1.0"      ); # Wed 28 Jun 2006


#
# Default configuration:
#
#  1  ->  "--" for em-dashes; no en-dash support
#  2  ->  "---" for em-dashes; "--" for en-dashes
#  3  ->  "--" for em-dashes; "---" for en-dashes
#  See docs for more configuration options.
#
define( 'SMARTYPANTS_ATTR',    1 );

# Openning and closing smart double-quotes.
define( 'SMARTYPANTS_SMART_DOUBLEQUOTE_OPEN',  "&#8220;" );
define( 'SMARTYPANTS_SMART_DOUBLEQUOTE_CLOSE', "&#8221;" );

# Space around em-dashes.  "He_—_or she_—_should change that."
define( 'SMARTYPANTS_SPACE_EMDASH',      " " );

# Space around en-dashes.  "He_–_or she_–_should change that."
define( 'SMARTYPANTS_SPACE_ENDASH',      " " );

# Space before a colon. "He said_: here it is."
define( 'SMARTYPANTS_SPACE_COLON',       "&#160;" );

# Space before a semicolon. "That's what I said_; that's what he said."
define( 'SMARTYPANTS_SPACE_SEMICOLON',   "&#160;" );

# Space before a question mark and an exclamation mark: "¡_Holà_! What_?"
define( 'SMARTYPANTS_SPACE_MARKS',       "&#160;" );

# Space inside french quotes. "Voici la «_chose_» qui m'a attaqué."
define( 'SMARTYPANTS_SPACE_FRENCHQUOTE', "&#160;" );

# Space as thousand separator. "On compte 10_000 maisons sur cette liste."
define( 'SMARTYPANTS_SPACE_THOUSAND',    "&#160;" );

# Space before a unit abreviation. "This 12_kg of matter costs 10_$."
define( 'SMARTYPANTS_SPACE_UNIT',        "&#160;" );

# SmartyPants will not alter the content of these tags:
define( 'SMARTYPANTS_TAGS_TO_SKIP', 'pre|code|kbd|script|math');



### Standard Function Interface ###

define( 'SMARTYPANTS_PARSER_CLASS', 'SmartyPantsTypographer_Parser' );

function SmartyPants($text, $attr = SMARTYPANTS_ATTR) {
#
# Initialize the parser and return the result of its transform method.
#
	# Setup static parser array.
	static $parser = array();
	if (!isset($parser[$attr])) {
		$parser_class = SMARTYPANTS_PARSER_CLASS;
		$parser[$attr] = new $parser_class($attr);
	}

	# Transform text using parser.
	return $parser[$attr]->transform($text);
}

function SmartQuotes($text, $attr = 1) {
	switch ($attr) {
		case 0:  return $text;
		case 2:  $attr = 'qb'; break;
		default: $attr = 'q'; break;
	}
	return SmartyPants($text, $attr);
}

function SmartDashes($text, $attr = 1) {
	switch ($attr) {
		case 0:  return $text;
		case 2:  $attr = 'D'; break;
		case 3:  $attr = 'i'; break;
		default: $attr = 'd'; break;
	}
	return SmartyPants($text, $attr);
}

function SmartEllipsis($text, $attr = 1) {
	switch ($attr) {
		case 0:  return $text;
		default: $attr = 'e'; break;
	}
	return SmartyPants($text, $attr);
}

class SmartyPants_Parser {

	# Options to specify which transformations to make:
	var $do_nothing   = 0;
	var $do_quotes    = 0;
	var $do_backticks = 0;
	var $do_dashes    = 0;
	var $do_ellipses  = 0;
	var $do_stupefy   = 0;
	var $convert_quot = 0; # should we translate &quot; entities into normal quotes?

	function SmartyPants_Parser($attr = SMARTYPANTS_ATTR) {
		if ($attr == "0") {
			$this->do_nothing   = 1;
		}
		else if ($attr == "1") {
			# Do everything, turn all options on.
			$this->do_quotes    = 1;
			$this->do_backticks = 1;
			$this->do_dashes    = 1;
			$this->do_ellipses  = 1;
		}
		else if ($attr == "2") {
			# Do everything, turn all options on, use old school dash shorthand.
			$this->do_quotes    = 1;
			$this->do_backticks = 1;
			$this->do_dashes    = 2;
			$this->do_ellipses  = 1;
		}
		else if ($attr == "3") {
			# Do everything, turn all options on, use inverted old school dash shorthand.
			$this->do_quotes    = 1;
			$this->do_backticks = 1;
			$this->do_dashes    = 3;
			$this->do_ellipses  = 1;
		}
		else if ($attr == "-1") {
			# Special "stupefy" mode.
			$this->do_stupefy   = 1;
		}
		else {
			$chars = preg_split('//', $attr);
			foreach ($chars as $c){
				if      ($c == "q") { $this->do_quotes    = 1; }
				else if ($c == "b") { $this->do_backticks = 1; }
				else if ($c == "B") { $this->do_backticks = 2; }
				else if ($c == "d") { $this->do_dashes    = 1; }
				else if ($c == "D") { $this->do_dashes    = 2; }
				else if ($c == "i") { $this->do_dashes    = 3; }
				else if ($c == "e") { $this->do_ellipses  = 1; }
				else if ($c == "w") { $this->convert_quot = 1; }
				else {
					# Unknown attribute option, ignore.
				}
			}
		}
	}

	function transform($text) {

		if ($this->do_nothing) {
			return $text;
		}

		$tokens = $this->tokenizeHTML($text);
		$result = '';
		$in_pre = 0;

		$prev_token_last_char = "";
		foreach ($tokens as $cur_token) {
			if ($cur_token[0] == "tag") {
				# Don't mess with quotes inside tags.
				$result .= $cur_token[1];
				if (preg_match('@<(/?)(?:'.SMARTYPANTS_TAGS_TO_SKIP.')[\s>]@', $cur_token[1], $matches)) {
					$in_pre = isset($matches[1]) && $matches[1] == '/' ? 0 : 1;
				}
			} else {
				$t = $cur_token[1];
				$last_char = substr($t, -1); # Remember last char of this token before processing.
				if (! $in_pre) {
					$t = $this->educate($t, $prev_token_last_char);
				}
				$prev_token_last_char = $last_char;
				$result .= $t;
			}
		}

		return $result;
	}


	function educate($t, $prev_token_last_char) {
		$t = $this->processEscapes($t);

		if ($this->convert_quot) {
			$t = preg_replace('/&quot;/', '"', $t);
		}

		if ($this->do_dashes) {
			if ($this->do_dashes == 1) $t = $this->educateDashes($t);
			if ($this->do_dashes == 2) $t = $this->educateDashesOldSchool($t);
			if ($this->do_dashes == 3) $t = $this->educateDashesOldSchoolInverted($t);
		}

		if ($this->do_ellipses) $t = $this->educateEllipses($t);

		# Note: backticks need to be processed before quotes.
		if ($this->do_backticks) {
			$t = $this->educateBackticks($t);
			if ($this->do_backticks == 2) $t = $this->educateSingleBackticks($t);
		}

		if ($this->do_quotes) {
			if ($t == "'") {
				# Special case: single-character ' token
				if (preg_match('/\S/', $prev_token_last_char)) {
					$t = "&#8217;";
				}
				else {
					$t = "&#8216;";
				}
			}
			else if ($t == '"') {
				# Special case: single-character " token
				if (preg_match('/\S/', $prev_token_last_char)) {
					$t = "&#8221;";
				}
				else {
					$t = "&#8220;";
				}
			}
			else {
				# Normal case:
				$t = $this->educateQuotes($t);
			}
		}

		if ($this->do_stupefy) $t = $this->stupefyEntities($t);

		return $t;
	}


	function educateQuotes($_) {
		$punct_class = "[!\"#\\$\\%'()*+,-.\\/:;<=>?\\@\\[\\\\\]\\^_`{|}~]";

		$_ = preg_replace(
			array("/^'(?=$punct_class\\B)/", "/^\"(?=$punct_class\\B)/"),
			array('&#8217;',                 '&#8221;'), $_);
		$_ = preg_replace(
			array("/\"'(?=\w)/",    "/'\"(?=\w)/"),
			array('&#8220;&#8216;', '&#8216;&#8220;'), $_);
		$_ = preg_replace("/'(?=\\d{2}s)/", '&#8217;', $_);

		$close_class = '[^\ \t\r\n\[\{\(\-]';
		$dec_dashes = '&\#8211;|&\#8212;';
		$_ = preg_replace("{
			(
				\\s          |   # a whitespace char, or
				&nbsp;      |   # a non-breaking space entity, or
				--          |   # dashes, or
				&[mn]dash;  |   # named dash entities
				$dec_dashes |   # or decimal entities
				&\\#x201[34];    # or hex
			)
			'                   # the quote
			(?=\\w)              # followed by a word character
			}x", '\1&#8216;', $_);
		# Single closing quotes:
		$_ = preg_replace("{
			($close_class)?
			'
			(?(1)|          # If $1 captured, then do nothing;
			  (?=\\s | s\\b)  # otherwise, positive lookahead for a whitespace
			)               # char or an 's' at a word ending position. This
							# is a special case to handle something like:
							# \"<i>Custer</i>'s Last Stand.\"
			}xi", '\1&#8217;', $_);

		# Any remaining single quotes should be opening ones:
		$_ = str_replace("'", '&#8216;', $_);


		# Get most opening double quotes:
		$_ = preg_replace("{
			(
				\\s          |   # a whitespace char, or
				&nbsp;      |   # a non-breaking space entity, or
				--          |   # dashes, or
				&[mn]dash;  |   # named dash entities
				$dec_dashes |   # or decimal entities
				&\\#x201[34];    # or hex
			)
			\"                   # the quote
			(?=\\w)              # followed by a word character
			}x", '\1&#8220;', $_);

		# Double closing quotes:
		$_ = preg_replace("{
			($close_class)?
			\"
			(?(1)|(?=\\s))   # If $1 captured, then do nothing;
							   # if not, then make sure the next char is whitespace.
			}x", '\1&#8221;', $_);

		# Any remaining quotes should be opening ones.
		$_ = str_replace('"', '&#8220;', $_);

		return $_;
	}


	function educateBackticks($_) {
		$_ = str_replace(array("``",       "''",),
						 array('&#8220;', '&#8221;'), $_);
		return $_;
	}


	function educateSingleBackticks($_) {
		$_ = str_replace(array("`",       "'",),
						 array('&#8216;', '&#8217;'), $_);
		return $_;
	}


	function educateDashes($_) {
		$_ = str_replace('--', '&#8212;', $_);
		return $_;
	}


	function educateDashesOldSchool($_) {
		$_ = str_replace(array("---",     "--",),
						 array('&#8212;', '&#8211;'), $_);
		return $_;
	}


	function educateDashesOldSchoolInverted($_) {
		$_ = str_replace(array("---",     "--",),
						 array('&#8211;', '&#8212;'), $_);
		return $_;
	}


	function educateEllipses($_) {
		$_ = str_replace(array("...",     ". . .",), '&#8230;', $_);
		return $_;
	}


	function stupefyEntities($_) {
		$_ = str_replace(array('&#8211;', '&#8212;'),
						 array('-',       '--'), $_);

		# single quote         open       close
		$_ = str_replace(array('&#8216;', '&#8217;'), "'", $_);

		# double quote         open       close
		$_ = str_replace(array('&#8220;', '&#8221;'), '"', $_);

		$_ = str_replace('&#8230;', '...', $_); # ellipsis

		return $_;
	}


	function processEscapes($_) {
		$_ = str_replace(
			array('\\\\',  '\"',    "\'",    '\.',    '\-',    '\`'),
			array('&#92;', '&#34;', '&#39;', '&#46;', '&#45;', '&#96;'), $_);

		return $_;
	}


	function tokenizeHTML($str) {
		$index = 0;
		$tokens = array();

		$match = '(?s:<!(?:--.*?--\s*)+>)|'.	# comment
				 '(?s:<\?.*?\?>)|'.				# processing instruction
												# regular tags
				 '(?:<[/!$]?[-a-zA-Z0-9:]+\b(?>[^"\'>]+|"[^"]*"|\'[^\']*\')*>)';

		$parts = preg_split("{($match)}", $str, -1, PREG_SPLIT_DELIM_CAPTURE);

		foreach ($parts as $part) {
			if (++$index % 2 && $part != '')
				$tokens[] = array('text', $part);
			else
				$tokens[] = array('tag', $part);
		}
		return $tokens;
	}

}

class SmartyPantsTypographer_Parser extends SmartyPants_Parser {
	var $do_comma_quotes      = 0;
	var $do_guillemets        = 0;
	var $do_space_emdash      = 0;
	var $do_space_endash      = 0;
	var $do_space_colon       = 0;
	var $do_space_semicolon   = 0;
	var $do_space_marks       = 0;
	var $do_space_frenchquote = 0;
	var $do_space_thousand    = 0;
	var $do_space_unit        = 0;

	# Smart quote characters:
	var $smart_doublequote_open  = SMARTYPANTS_SMART_DOUBLEQUOTE_OPEN;
	var $smart_doublequote_close = SMARTYPANTS_SMART_DOUBLEQUOTE_CLOSE;
	var $smart_singlequote_open  = '&#8216;';
	var $smart_singlequote_close = '&#8217;'; # Also apostrophe.

	# Space characters for different places:
	var $space_emdash      = SMARTYPANTS_SPACE_EMDASH;
	var $space_endash      = SMARTYPANTS_SPACE_ENDASH;
	var $space_colon       = SMARTYPANTS_SPACE_COLON;
	var $space_semicolon   = SMARTYPANTS_SPACE_SEMICOLON;
	var $space_marks       = SMARTYPANTS_SPACE_MARKS;
	var $space_frenchquote = SMARTYPANTS_SPACE_FRENCHQUOTE;
	var $space_thousand    = SMARTYPANTS_SPACE_THOUSAND;
	var $space_unit        = SMARTYPANTS_SPACE_UNIT;

	# Expression of a space (breakable or not):
	var $space = '(?: | |&nbsp;|&#0*160;|&#x0*[aA]0;)';

	function SmartyPantsTypographer_Parser($attr = SMARTYPANTS_ATTR) {
	parent::SmartyPants_Parser($attr);

		if ($attr == "1" || $attr == "2" || $attr == "3") {
			# Do everything, turn all options on.
			$this->do_comma_quotes      = 1;
			$this->do_guillemets  = 1;
			$this->do_space_emdash      = 1;
			$this->do_space_endash      = 1;
			$this->do_space_colon       = 1;
			$this->do_space_semicolon   = 1;
			$this->do_space_marks       = 1;
			$this->do_space_frenchquote = 1;
			$this->do_space_thousand    = 1;
			$this->do_space_unit        = 1;
		}
		else if ($attr == "-1") {
			# Special "stupefy" mode.
			$this->do_stupefy   = 1;
		}
		else {
			$chars = preg_split('//', $attr);
			foreach ($chars as $c){
				if      ($c == "c") { $current =& $this->do_comma_quotes; }
				else if ($c == "g") { $current =& $this->do_guillemets; }
				else if ($c == ":") { $current =& $this->do_space_colon; }
				else if ($c == ";") { $current =& $this->do_space_semicolon; }
				else if ($c == "m") { $current =& $this->do_space_marks; }
				else if ($c == "h") { $current =& $this->do_space_emdash; }
				else if ($c == "H") { $current =& $this->do_space_endash; }
				else if ($c == "f") { $current =& $this->do_space_frenchquote; }
				else if ($c == "t") { $current =& $this->do_space_thousand; }
				else if ($c == "u") { $current =& $this->do_space_unit; }
				else if ($c == "+") {
					$current = 2;
					unset($current);
				}
				else if ($c == "-") {
					$current = -1;
					unset($current);
				}
				else {
					# Unknown attribute option, ignore.
				}
				$current = 1;
			}
		}
	}


	function educate($t, $prev_token_last_char) {
		$t = parent::educate($t, $prev_token_last_char);

		if ($this->do_comma_quotes)      $t = $this->educateCommaQuotes($t);
		if ($this->do_guillemets)        $t = $this->educateGuillemets($t);
		if ($this->do_space_emdash)      $t = $this->spaceEmDash($t);
		if ($this->do_space_endash)      $t = $this->spaceEnDash($t);
		if ($this->do_space_colon)       $t = $this->spaceColon($t);
		if ($this->do_space_semicolon)   $t = $this->spaceSemicolon($t);
		if ($this->do_space_marks)       $t = $this->spaceMarks($t);
		if ($this->do_space_frenchquote) $t = $this->spaceFrenchQuotes($t);
		if ($this->do_space_thousand)    $t = $this->spaceThousandSeparator($t);
		if ($this->do_space_unit)        $t = $this->spaceUnit($t);

		return $t;
	}


	function educateQuotes($_) {
		$dq_open  = $this->smart_doublequote_open;
		$dq_close = $this->smart_doublequote_close;
		$sq_open  = $this->smart_singlequote_open;
		$sq_close = $this->smart_singlequote_close;

		# Make our own "punctuation" character class, because the POSIX-style
		# [:PUNCT:] is only available in Perl 5.6 or later:
		$punct_class = "[!\"#\\$\\%'()*+,-.\\/:;<=>?\\@\\[\\\\\]\\^_`{|}~]";

		# Special case if the very first character is a quote
		# followed by punctuation at a non-word-break. Close the quotes by brute force:
		$_ = preg_replace(
			array("/^'(?=$punct_class\\B)/", "/^\"(?=$punct_class\\B)/"),
			array($sq_close,                 $dq_close), $_);

		# Special case for double sets of quotes, e.g.:
		#   <p>He said, "'Quoted' words in a larger quote."</p>
		$_ = preg_replace(
			array("/\"'(?=\w)/",     "/'\"(?=\w)/"),
			array($dq_open.$sq_open, $sq_open.$dq_open), $_);

		# Special case for decade abbreviations (the '80s):
		$_ = preg_replace("/'(?=\\d{2}s)/", $sq_close, $_);

		$close_class = '[^\ \t\r\n\[\{\(\-]';
		$dec_dashes = '&\#8211;|&\#8212;';

		# Get most opening single quotes:
		$_ = preg_replace("{
			(
				\\s          |   # a whitespace char, or
				&nbsp;      |   # a non-breaking space entity, or
				--          |   # dashes, or
				&[mn]dash;  |   # named dash entities
				$dec_dashes |   # or decimal entities
				&\\#x201[34];    # or hex
			)
			'                   # the quote
			(?=\\w)              # followed by a word character
			}x", '\1'.$sq_open, $_);
		# Single closing quotes:
		$_ = preg_replace("{
			($close_class)?
			'
			(?(1)|          # If $1 captured, then do nothing;
			  (?=\\s | s\\b)  # otherwise, positive lookahead for a whitespace
			)               # char or an 's' at a word ending position. This
							# is a special case to handle something like:
							# \"<i>Custer</i>'s Last Stand.\"
			}xi", '\1'.$sq_close, $_);

		# Any remaining single quotes should be opening ones:
		$_ = str_replace("'", $sq_open, $_);


		# Get most opening double quotes:
		$_ = preg_replace("{
			(
				\\s          |   # a whitespace char, or
				&nbsp;      |   # a non-breaking space entity, or
				--          |   # dashes, or
				&[mn]dash;  |   # named dash entities
				$dec_dashes |   # or decimal entities
				&\\#x201[34];    # or hex
			)
			\"                   # the quote
			(?=\\w)              # followed by a word character
			}x", '\1'.$dq_open, $_);

		# Double closing quotes:
		$_ = preg_replace("{
			($close_class)?
			\"
			(?(1)|(?=\\s))   # If $1 captured, then do nothing;
							   # if not, then make sure the next char is whitespace.
			}x", '\1'.$dq_close, $_);

		# Any remaining quotes should be opening ones.
		$_ = str_replace('"', $dq_open, $_);

		return $_;
	}

	function educateCommaQuotes($_) {
		$_ = str_replace(",,", '&#8222;', $_);
		return $_;
	}


	function educateGuillemets($_) {
		$_ = preg_replace("/(?:<|&lt;){2}/", '&#171;', $_);
		$_ = preg_replace("/(?:>|&gt;){2}/", '&#187;', $_);
		return $_;
	}


	function spaceFrenchQuotes($_) {
		$opt = ( $this->do_space_frenchquote ==  2 ? '?' : '' );
		$chr = ( $this->do_space_frenchquote != -1 ? $this->space_frenchquote : '' );

		# Characters allowed immediatly outside quotes.
		$outside_char = $this->space . '|\s|[.,:;!?\[\](){}|@*~=+-]|¡|¿';

		$_ = preg_replace(
			"/(^|$outside_char)(&#171;|«|&#8250;|‹)$this->space$opt/",
			"\\1\\2$chr", $_);
		$_ = preg_replace(
			"/$this->space$opt(&#187;|»|&#8249;|›)($outside_char|$)/",
			"$chr\\1\\2", $_);
		return $_;
	}


	function spaceColon($_) {
		$opt = ( $this->do_space_colon ==  2 ? '?' : '' );
		$chr = ( $this->do_space_colon != -1 ? $this->space_colon : '' );

		$_ = preg_replace("/$this->space$opt(:)(\\s|$)/m",
						  "$chr\\1\\2", $_);
		return $_;
	}


	function spaceSemicolon($_) {
		$opt = ( $this->do_space_semicolon ==  2 ? '?' : '' );
		$chr = ( $this->do_space_semicolon != -1 ? $this->space_semicolon : '' );

		$_ = preg_replace("/$this->space(;)(?=\\s|$)/m",
						  " \\1", $_);
		$_ = preg_replace("/((?:^|\\s)(?>[^&;\\s]+|&#?[a-zA-Z0-9]+;)*)".
						  " $opt(;)(?=\\s|$)/m",
						  "\\1$chr\\2", $_);
		return $_;
	}


	function spaceMarks($_) {
		$opt = ( $this->do_space_marks ==  2 ? '?' : '' );
		$chr = ( $this->do_space_marks != -1 ? $this->space_marks : '' );

		// Regular marks.
		$_ = preg_replace("/$this->space$opt([?!]+)/", "$chr\\1", $_);

		// Inverted marks.
		$imarks = "(?:¡|&iexcl;|&#161;|&#x[Aa]1;|¿|&iquest;|&#191;|&#x[Bb][Ff];)";
		$_ = preg_replace("/($imarks+)$this->space$opt/", "\\1$chr", $_);

		return $_;
	}


	function spaceEmDash($_) {
		$opt = ( $this->do_space_emdash ==  2 ? '?' : '' );
		$chr = ( $this->do_space_emdash != -1 ? $this->space_emdash : '' );
		$_ = preg_replace("/$this->space$opt(&#8212;|—)$this->space$opt/",
			"$chr\\1$chr", $_);
		return $_;
	}


	function spaceEnDash($_) {
		$opt = ( $this->do_space_endash ==  2 ? '?' : '' );
		$chr = ( $this->do_space_endash != -1 ? $this->space_endash : '' );
		$_ = preg_replace("/$this->space$opt(&#8211;|–)$this->space$opt/",
			"$chr\\1$chr", $_);
		return $_;
	}


	function spaceThousandSeparator($_) {
		$chr = ( $this->do_space_thousand != -1 ? $this->space_thousand : '' );
		$_ = preg_replace('/([0-9]) ([0-9])/', "\\1$chr\\2", $_);
		return $_;
	}


	var $units = '
		### Metric units (with prefixes)
		(?:
			p |
			µ | &micro; | &\#0*181; | &\#[xX]0*[Bb]5; |
			[mcdhkMGT]
		)?
		(?:
			[mgstAKNJWCVFSTHBL]|mol|cd|rad|Hz|Pa|Wb|lm|lx|Bq|Gy|Sv|kat|
			Ω | Ohm | &Omega; | &\#0*937; | &\#[xX]0*3[Aa]9;
		)|
		### Computers units (KB, Kb, TB, Kbps)
		[kKMGT]?(?:[oBb]|[oBb]ps|flops)|
		### Money
		¢ | &cent; | &\#0*162; | &\#[xX]0*[Aa]2; |
		M?(?:
			£ | &pound; | &\#0*163; | &\#[xX]0*[Aa]3; |
			¥ | &yen;   | &\#0*165; | &\#[xX]0*[Aa]5; |
			€ | &euro;  | &\#0*8364; | &\#[xX]0*20[Aa][Cc]; |
			$
		)|
		### Other units
		(?: ° | &deg; | &\#0*176; | &\#[xX]0*[Bb]0; ) [CF]? |
		%|pt|pi|M?px|em|en|gal|lb|[NSEOW]|[NS][EOW]|ha|mbar
		'; //x

	function spaceUnit($_) {
		$opt = ( $this->do_space_unit ==  2 ? '?' : '' );
		$chr = ( $this->do_space_unit != -1 ? $this->space_unit : '' );

		$_ = preg_replace('/
			(?:([0-9])[ ]'.$opt.') # Number followed by space.
			('.$this->units.')     # Unit.
			(?![a-zA-Z0-9])  # Negative lookahead for other unit characters.
			/x',
			"\\1$chr\\2", $_);

		return $_;
	}


	function spaceAbbr($_) {
		$opt = ( $this->do_space_abbr ==  2 ? '?' : '' );
		$_ = preg_replace("/(^|\s)($this->abbr_after) $opt/m",
			"\\1\\2$this->space_abbr", $_);
		$_ = preg_replace("/( )$opt($this->abbr_sp_before)(?![a-zA-Z'])/m",
			"\\1$this->space_abbr\\2", $_);
		return $_;
	}

	function stupefyEntities($_) {
		$_ = parent::stupefyEntities($_);

		$_ = str_replace(array('&#8222;', '&#171;', '&#187'), '"', $_);

		return $_;
	}

	function processEscapes($_) {
		$_ = parent::processEscapes($_);

		$_ = str_replace(
			array('\,',    '\<',    '\>',    '\&lt;', '\&gt;'),
			array('&#44;', '&#60;', '&#62;', '&#60;', '&#62;'), $_);

		return $_;
	}
} */

?>