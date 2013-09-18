=== Inline Ajax Comments ===
Contributors: ZaneMatthew, Matthew McFarland
Donate link: http://zanematthew.com/
Tags: comments, ajax, inline
Requires at least: 3.6
Tested up to: 3.6
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Displays a single line textarea for entering comments, users can press "enter/return", and comments are loaded and submitted via AJAX.

== Description ==

This plugin places a comment form similar to Facebook, only displaying a single line textarea, which submits when the user presses "enter/return" on their keyboard.

= Features =
* Single line textarea
* Auto expanding textarea
* AJAX submitted comments
* AJAX loaded comments
* Admin settings for additional styling


== Installation ==

1. Install the plugin via WordPress or download and upload the plugin to the `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently Asked Questions ==

= The Register link is not working? =
Make sure that "Anyone can register" is checked in "Settings --> General"

= Where is the "url", "email" field? =
Users can add their "url" or "email" by clicking the "more" link next to the textarea.

= Doesn't this prevent search engines from seeing comments? =
At the moment it does, [Google Ajax Crawling](https://developers.google.com/webmasters/ajax-crawling/docs/getting-started) will be added in a later version.

= Does this work with Custom Post Types? =
Yes

= Does this work on Pages? =
Yes

= Can this be used on pages with multiple comment listings? =
Yes

= Does this support paging? =
At the moment no, later versions might.

== Screenshots ==

1. As seen when user is logged in
2. Admin settings
3. As seen when user is not logged in

== Upgrade Notice ==

* Check settings

== Changelog ==

* Bug: Fixed "keep on" setting

= 1.2.5m =
* Fix: Works when ajax loaded (by infinite scroll)
* Enhance: use sliding effect on more/less

INFINITE SCROLL:

Whatever infinite scroll function you are using, you must make sure that you add ajaxLoadedCallback() to its callback.  If you are not sure how to do this please contact us.

= 1.2.4.5m =
* Fix: unique target id's added
* Fix: ajax loading multiple comment lists
* Fix: ajax submit multiple comment lists
* Fix: more/less buttons work on multiple comment lists
* Enhanced: swapped more/less buttons with graphics
* Tweak: More/Less stays on the same line as the input box at smallest browser widths for responsiveness

TODO: implement multi-listing wp_nonce functionality.

= 1.2.4.3m =
* Fix: Allow for use ANYWHERE 

= 1.2.4.2m =
* Fix: Changed "user_nicename" (deprecated) to "display_name"

= 1.2.4m =
* Tweak: Changed min-height from 17 to 32

= 1.2.2 =
* Tweak: Improved reliability in css classes by user role

= 1.2.1 =
* Enhancement: Comment area now toggles from 1 line to multi-line
* Enhancement: The following `html` tags are now allowed `<a href="" title="">`, `<blockquote>`, `<code>`, `<em>`, `<strong>`
* Enhancement: Added additional styling for post authors
* Enhancement: User name links to their website
* Enhancement: You can now share direct links to comments
* Enhancement: Added setting to keep comment box open

= 1.2 =
* Comments now work on Pages (not just posts)
* Adding a setting to keep the 'more' section open

= 1.1 =
* Removing dynamic functions for older PHP support
* Removing dead code

= 0.1-alpha =
* Initial version
