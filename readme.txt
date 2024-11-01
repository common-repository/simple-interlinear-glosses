=== Simple Interlinear Glosses ===
Contributors: carbeck
Tags: linguistics, conlang, interlinear, gloss, shortcode
Requires at least: 2.7
Tested up to: 3.6.1
Stable tag: 0.2.3

A simple shortcode to generate interlinear glosses as used in linguistics with CSS markup to align morpheme columns. Tries to display abbreviations for functional morphemes as small caps.

== Description ==

This is a shortcode plugin that generates interlinear glosses as used in linguistics from an arbitrary number of lines of text in a [gloss][/gloss] block. This works basically like when you import a CSV file into a stylesheet: The first word of the first line is mapped to the first word of subsequent lines, the second word of the first line is mapped to the second word of the subsequent lines etc. You can specify a delimiting character to splice up individual lines, otherwise lines will be split at word spaces. The alignment of columns is achieved with CSS, and specifically with its floating blocks function. Since this basic functionality is hardcoded, CSS-capable RSS readers won't break your layout. A style.css file in the plugin's folder allows you to make further modifications; you can also add your own styling permanently by editing the style.css file in the child-theme directory of your Wordpress theme. See the README.pdf file in the plugin's folder for further demonstrations or visit the plugin's 
page on my blog, at <a href="http://benung.nfshost.com/archives/1721" target="_blank">http://benung.nfshost.com/archives/1721</a>.


== Installation ==

1. Upload the `simple-interlinear-glosses` folder to the `/wp-content/plugins/` directory


OR


1. Go to 'Add Plugin'
1. Search for 'Simple Interlinear Glosses'


== Frequently Asked Questions ==

<ul>
<li>HTML tags like <tt>a</tt> and <tt>​span</tt> aren't yet supported because I need to figure out how to make the script not match punctuation inside the tags. All HTML tags but <tt>strong, b, em, i, s, strike, u, big, small, sup, sub</tt> are currently stripped from what's inside the <tt>[gloss]</tt> block. (2012-02-15)</li>

</ul>

== Screenshots ==

1. Feature display.

== Changelog ==

= 2013-09-22 =
* Fixed CSS handle's name back to cb-gloss.

= 2013-09-21 =
* Fixed a minor issue with !$blah vs. !isset($blah) that occurred when I updated to PHP 5.4.

= 2012-03-06 =
* Changed the accidental 'myStyleSheets' to the more meaningful 'cb-gloss' in the title of the stylesheet ID for `style.css`.

= 2012-02-20 =
* Added the `[smcp]` shortcode to make text appear as small caps as defined in the plugin's `style.css` file also in bodies of text outside of the `[gloss]` block.

= 2012-02-16 (0.2.1) =

* Fixed some typos in readme.txt
* Fixed some typos in README.pdf

= 2012-02-15 (0.2) =
* 2nd release

= 2012-02-14 =
* FIX: Allow simple markup HTML tags in glosses: Do not recognize the sequence "<​/" as a trigger for small caps.
* FIX: HTML tags also get a zero-width space (U+200B) around them now so as not to collide with the function that turns things into small caps. Support only for basic styling tags, i.e. <em>strong, b, em, i, s, strike, u, big, small, sup, sub</em>. (FIXME)
* FIX: Process only <em>|this|</em>, but not <em>| this |</em> and don't eat the <em>|</em>.

= 2012-02-13 (0.1) =
Initial release

== Upgrade Notice ==
Some bug fixes (See "Changelog").
