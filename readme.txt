=== Twitter follow button in coments ===
Contributors: Fabulatorcz
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=michal%40fabulator%2ecz&lc=CZ&item_name=Michal%20Ozogan%20%2d%20donate&no_note=0&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHostedGuest
Tags: twitter, comments, social, follow button
Requires at least: 3.0.0
Tested up to: 3.5.1
Stable tag: 0.5

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



== Changelog ==

= 0.5 =
add: store Twitter user name in cookies
mod: inserting twitter javascript by wordpress rules

= 0.3 =
add: Your can have custom name of Twitter field in comment form.
add: New languages.
mod: it now accept twitter profile as url


= 0.2 =
First version.