=== Twitter follow button in coments ===
Contributors: Fabulatorcz
Tags: twitter, comments, social, follow button
Requires at least: 3.0.0
Tested up to: 3.3.2
Stable tag: 0.2

Allow your visitors to add their twitter.

== Description ==

This plugin will allow visitor to submit they twitter, while thay are commenting your posts. They account will by showed as twitter follow button.

Registred users can add their twitter account in profile administration.

There is also a setting page where you can edit color, size, showing count and language.

Some themes dont't use function `<?php comment_form(); ?>` to show comment form. In this case you have to edit your template. Find comments.php and add 

`<p><label for="twitter">Twitter</label><input name="twitter" size="30" type="text" value=''/></p>`

 to form.

== Installation ==


1. Upload `twitter-in-comments` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Edit your twitter account in profile
4. If you can't see Twitter in comments form you have to edit your template. Find comments.php and add 

`<p><label for="twitter">Twitter</label><input name="twitter" size="30" type="text" value=''/></p>`

 to form.

== Screenshots ==


== Changelog ==

= 0.2 =
First version.