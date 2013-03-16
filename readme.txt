=== Inline Ajax Comments ===
Contributors: zanematthew
Donate link: http://zanematthew.com/
Tags: comments, ajax, inline
Requires at least: 3.5
Tested up to: 3.5.1
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Displays a single line textarea for entering comments, users can press "enter/return", and comments are loaded and submitted via AJAX.

== Description ==

This plugin places a comment form similar to Facebook, only displaying a single line textarea, which submits when the user presses "enter/return" on their keyboard.

=== Features ===
* Single line textarea
* AJAX submitted comments
* AJAX loaded comments


== Installation ==

1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Place `<?php if ( get_option('zm_ajax_comments_version') ) : zm_ajax_comments(); ?>` in your template where you want comments to load

== Frequently Asked Questions ==

= The Register link is not working? =
Make sure that "Anyone can register" is checked in "Settings --> General"

= Where is the "url", "email" field? =
Users can add their "url" or "email" by clicking the "more" link next to the textarea.

= Doesn't this prevent search engines from seeing comments? =
No, it doesn't! Through the magic of Google's "AJAX Crawling" specification, search engines (currently Googlebot and Bingbot) will be made aware that the page includes AJAX-fetched content, and will request your page with a special URL that will have this plugin skip AJAX-generation and instead give the full HTML to the crawler.

== Screenshots ==
1. As seen when user is logged in
1. Comments form with additional fields
1. As seen when user is not logged in

== Changelog ==

= 1.0 =
* Initial version