<?php

/* Plugin Name: Simple Interlinear Glosses
 * Plugin URI: http://benung.nfshost.com/archives/1721
 * Description: A WordPress shortcode to make simple interlinear glosses.
 * Author: Carsten Becker
 * Version: 0.2.3
 * Author URI: http://sanstitre.nfshost.com
 *
 * Copyleft 2012-13 Carsten Becker (carbeck@gmail.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 */

function nbsp($match) {
	$match[0] = str_replace(' ', '&nbsp;', $match[0]);
	$match[0] = preg_replace('/[\{\}]/', '', $match[0]);
	return $match[0];
}

function smcp($match) {

	if(preg_match('/`(.*?)`/', $match[0])) {
		return str_replace('`', '', $match[0]);
	} elseif(preg_match('/\<\//', $match[0])) {	// Leave closing HTML tags alone!!!
		return $match[0];
	} else {
		return "<span class='smcp'>$match[0]</span>";
	}
}

/*** THE ACTUAL FUNCTION THAT DOES THE SHORTCODE PROCESSING ***/

function fn_cb_gloss($attr, $content = null) {
	
	// Detect stuff that is supposed to be returned as small caps:
	// Any string of more than two uppercase characters, digits or
	// punctuation will be assumed to mark functional morphemes.
	// Use `...` to escape, |...| to explicitly mark for conversion.
	// This is probably a little clumsy, but I don't know my
	// regexps better unfortunately.
	
	if($content) {
	
		// Some prearrangements
	
		$content = trim($content);
		$allow_tags = "<strong><b><em><i><s><strike><u><big><small><sup><sub>";
		$content = strip_tags($content, $allow_tags); // Strip HTML tags, but allow markup
		$content = preg_replace('/([\<][\/]?(.*?)[\s]?[\/]?[\>])/', '​$0​', $content); // put zero-width blanks (U+200B) around HTML tags to make sure
		
		// Matched: more than two uppercase chars, digits, or 
		// punctuation, but not including `...`.
		// Replacement: span class for small-caps text.
		// Note: We're using the smcp() function declared above here
		// in order to check whether the string is excluded from
		// being put into small caps or not.
		
		$content = preg_replace_callback('/[[:upper:][:digit:][:punct:]]{2,}/', 'smcp', $content);
		
		// Matched: anything like |this|, but not like | this |.
		// Replacement: span class for small-caps text.
		
		$content = preg_replace('/\|([^\s].*?[^\s])\|/', '<span class="smcp">$1</span>', $content);
		
		// Matched: anything between ` and `.
		// Replacement: the in-between text (a.k.a. stripping off ``)
		
		$content = preg_replace('/`(.*?)`/', '$1', $content);
		
		// Matched: anything between { and }.
		// Replacement: blanks to &nbsp; to keep stuff together
		// Note: We're using the nbsp() function declared above here
		
		$content = preg_replace_callback('/\{(.*?)\}/', 'nbsp', $content);
	}

	// Split into lines at linebreak and read individual lines into 
	// the array $lines.
	
	$lines = preg_split("/(<br( \/)?>)?\n/", $content);

	// Check if a divider has been set. If not, assume a word space.
	
	if(!isset($attr["div"])) {
		$div = "(?<!span) "; // space not preceded by "span"
	} else {
		$div = $attr["div"];
		$div = preg_quote($div, '/');
	};
	
	// Explode individual lines at the divider, read into $elems
	
	foreach($lines as $l) {
		$elems[] = preg_split("/$div/", $l);
	}

	/*** START HTML CONVERSION HERE ***/
	
	$result = "<div class='cb-gloss' style='margin-bottom: 0em;'>\n";

	// This will turn the whole thing into a definitions list
	
	$i = 0;
	$n = count($elems);
	$k = count($elems[0]);
	
	for($i; $i < $k; $i++) {
	
		// For each column of morphemes, write a <dl>...</dl>
	
		$result .= "<dl style='display: inline-block; margin: 0 0.5em 0.25em 0;'>\n";
		
			// For the top line of the gloss, take the first elements
			// per morpheme column and return them as <dt>...</dt>
			
			$result .= sprintf("\t<dt class='%s'>%s</dt>\n", @$attr[0], $elems[0][$i]);
			
			// Do the same for the other lines and elements, but
			// return them as <dd>...</dd>
			
			$j = 1;
			
			for($j; $j < $n; $j++) {
				$result .= sprintf("\t<dd class='%s' style='margin: 0; padding: 0'>%s</dd>\n", @$attr[$j], $elems[$j][$i]);
			}
		
		$result .= "</dl>\n";
	}
	
	$result .= "</div>\n";
	
	/*** RETURN THE WHOLE INTERLINEARIZED GLOSS ***/

	return $result;

} /* END fn_cb_glosses() */

add_shortcode('gloss', 'fn_cb_gloss');

// Register the plugin's style.css with WordPress, *properly*, as defined per WordPress Codex ...

add_action('wp_enqueue_scripts', 'add_cb_gloss_style');
function add_cb_gloss_style() {
	$myStyleUrl = plugins_url('style.css', __FILE__);
	$myStyleFile = WP_PLUGIN_DIR . '/cb-gloss/style.css';
	if ( file_exists($myStyleFile) ) {
		wp_register_style('cb-gloss', $myStyleUrl);
		wp_enqueue_style( 'cb-gloss');
	}
}

?>
