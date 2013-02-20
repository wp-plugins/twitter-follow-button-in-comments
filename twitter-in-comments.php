<?php
/*
Plugin Name: Twitter follow button in coments
Description: This plugin will allow your visitors to submit their twitter, while they are commenting your posts. They account will by showed as a twitter follow button.
Author: Michal Ozogan
Version: 0.5
Author URI: https://twitter.com/fabulatorEN
*/
?>
<?php

function tfbin_create_menu(){
	add_options_page('Twitter follow button Settings', 'Twitter follow button Setting', "administrator", 'twitter-follow-button', 'tfbin_twitter_follow_button_page'); 
	add_action( 'admin_init', 'tfbin_register_mysettings' );
	}
add_action('admin_menu', 'tfbin_create_menu');

function tfbin_register_mysettings(){
	register_setting( 'tfbin-settings-group', 'tfbin-show_count' );
	register_setting( 'tfbin-settings-group', 'tfbin-data_size' );
	register_setting( 'tfbin-settings-group', 'tfbin-lang' );
	register_setting( 'tfbin-settings-group', 'tfbin-text_color' );
	register_setting( 'tfbin-settings-group', 'tfbin-link_color' );
	register_setting( 'tfbin-settings-group', 'tfbin-twitter' );
	}

/* SETTING PAGE */

function tfbin_twitter_follow_button_page(){
?>
<div class="wrap">
	<h2>Twitter follow button Setting</h2>
	<form method="post" action="options.php">
		<?php settings_fields('tfbin-settings-group'); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Medium or Large button?</th>
				<td>
					<fieldset><label><input <?php if(get_option('tfbin-data_size') == 'medium' or get_option('tfbin-data_size') == "") echo "checked=\"checked\""; ?> name="tfbin-data_size" type="radio" value="medium" /><span></span> Medium</label><label> <input <?php if(get_option('tfbin-data_size') == 'large') echo "checked=\"checked\""; ?> name="tfbin-data_size" type="radio" value="large" /><span></span> Large</label></fieldset>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Show follower count?</th>
				<td>
					<fieldset><label><input <?php if(get_option('tfbin-show_count') == 'false') echo "checked=\"checked\""; ?> name="tfbin-show_count" type="radio" value="false" /> No</label><label> <input <?php if(get_option('tfbin-show_count') == 'true') echo "checked=\"checked\""; ?> name="tfbin-show_count" type="radio" value="true" /> Yes</label></fieldset>
				</td>
			</tr>		
			<tr valign="top">
				<th scope="row">What text color will be used?</th>
				<td>
					<input name="tfbin-text_color" type="text" value="<?php if(get_option('tfbin-text_color') == "") echo "800080"; else echo get_option('tfbin-text_color') ?>" />
				</td>
			</tr>	
			<tr valign="top">
				<th scope="row">What link color will be used?</th>
				<td>
					<input name="tfbin-link_color" type="text" value="<?php if(get_option('tfbin-link_color') == "") echo "800080"; else echo get_option('tfbin-link_color') ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">Name of field for Twitter</th>
				<td>
					<input name="tfbin-twitter" type="text" value="<?php if(get_option('tfbin-twitter') == "") echo "Twitter"; else echo get_option('tfbin-twitter') ?>" />
				</td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="tfbin-lang">Language options</label></th>
				<td>
				<select name="tfbin-lang">
				<?php 
					$langs = array( "nl" => "Nederlands", "en" => "English", "fr" => "français", "de" => "Deutsch", "id" => "Bahasa Indonesia", "it" => "Italiano", "pt" => "Português", "es" => "Español", "tr" => "Türkçe", "pl" => "Polski", "no" => "Norsk", "da" => "Dansk", "af" => "Afrikaans", "ca" => "catala", "hu" => "Magyar","fi" => "Suomi", "sv" => "Svenska", "eu" => "Euskara", "fil" => "Filipino", "ur" => "اردو", "cs" => "Čeština", "ru" => "Русский", "msa" => "Bahasa Melayu", "ar" => "العربية", "hi" => "हिन्दी", "uk" => "Українська мова", "zh-cn" => "简体中文", "zh-tw" => "繁體中文", "fa" => "فارسی", "he" => "עִבְרִית", "th" => "ภาษาไทย", "ja" => "日本語", "ko" => "한국어", "el" => "Ελληνικά");?>
					<?php foreach($langs as $key => $value){ ?>
						<option value="<?php echo $key ?>" <?php if(get_option('tfbin-lang') == $key or (get_option('tfbin-lang') == "" && $key == "en")) echo "selected"; ?>><?php echo $value; ?></option>
					<?php } ?>
				</select>
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
		</p>
	</form>
</div>
<?php } 

/* ENQUE TWITTER JAVASCRIPT FILES */

add_action('wp_enqueue_scripts', 'tfbin_load_javascript_files');
function tfbin_load_javascript_files() {
	wp_register_script('twitter', "http://platform.twitter.com/widgets.js", array(), '1.0', true);
	wp_enqueue_script('twitter');
}

/* ADD TWITTER FIELD TO COMMENT FORM, INSERT USERNAME IF IT IS KNOWN VISITOR */

add_filter('comment_form_defaults', 'tfbin_add_item_to_comments');
function tfbin_add_item_to_comments( $default ) {
		$commenter = wp_get_current_commenter();
		if(isset($commenter['twitter'])) $twitter = $commenter['twitter'];
		else $twitter = "";
		if(isset($_COOKIE['comment_twitter_'.COOKIEHASH])){
				$twitter = esc_attr($_COOKIE['comment_twitter_'.COOKIEHASH]);
				$_COOKIE['comment_twitter_'.COOKIEHASH] = $twitter;
			}
		$default[ 'fields' ][ 'email' ] .= '
				<p class="comment-form-author">
					<label for="twitter">Twitter</label>
					<input name="twitter" size="30" type="text" value="'. $twitter .'"/>
				</p>';
		return $default;
	}

/* PUT TWITTER INTO DATABASE AND SAVE COOKIE */

add_action( 'comment_post', 'tfbin_save_twitter_comment' );	
function tfbin_save_twitter_comment($comment_id){
		if(isset($_POST['twitter'])){
			add_comment_meta($comment_id, 'twitter', $_POST['twitter']);
			$comment_cookie_lifetime = apply_filters('comment_cookie_lifetime', 30000000);
			setcookie('comment_twitter_' . COOKIEHASH, $_POST['twitter'], time() + $comment_cookie_lifetime, COOKIEPATH, COOKIE_DOMAIN);
		}
	}

 add_action('show_user_profile', 'tfbin_add_item_to_profile');
 add_action('edit_user_profile', 'tfbin_add_item_to_profile');
 function tfbin_add_item_to_profile($user){
	 ?>
	 <table>
	 <tr>
	 <th><label for="twitter"><?php if(get_option('tfbin-twitter') == "") echo "Twitter"; else echo get_option('tfbin-twitter') ?>: </label></th>
	 <td><input type="text" name="twitter" id="twitter" value="<?php echo esc_attr(get_the_author_meta('twitter', $user->ID) ); ?>" /></td>
	 </tr>
	 </table>
	 <?php
 }
 
add_action('personal_options_update', 'tfbin_save_item_to_profile');
add_action('edit_user_profile_update', 'tfbin_save_item_to_profile');
function tfbin_save_item_to_profile($user_id){
	if(!current_user_can('edit_user', $user_id )) return false;
	update_usermeta($user_id, 'twitter', $_POST['twitter']);
}

add_filter('get_comment_text', 'tfbin_attach_twitter_to_text');
function tfbin_attach_twitter_to_text($text) {
	global $comment;
	if($comment->user_id == 0) $twitter = get_comment_meta(get_comment_ID(), 'twitter', true);
	else $twitter = get_user_meta($comment->user_id, 'twitter', true);
	$twitter = explode("/", str_replace("@","",$twitter)); // prevent user inserting whole url
	$twitter = $twitter[count($twitter) - 1];
	if($twitter != "") {
		$tw = "<a href=\"http://twitter.com/$twitter\" class=\"twitter-follow-button\"";
		if(get_option('tfbin-show_count')) $tw .= " data-show-count=\"". get_option('tfbin-show_count') ."\"";
		if(get_option('tfbin-text_color')) $tw .= " data-text-color=\"". get_option('tfbin-text_color') ."\"";
		if(get_option('tfbin-link_color')) $tw .= " data-link-color=\"". get_option('tfbin-link_color') ."\"";
		if(get_option('tfbin-lang')) $tw .= " data-lang=\"". get_option('tfbin-lang') ."\"";
		if(get_option('tfbin-data_size')) $tw .= " data-size=\"". get_option('tfbin-data_size') ."\"";
		$tw .= ">Follow @" . $twitter . "</a><br /><br />" . $text;
		return $tw;
		}
		else return $text;
	}
?>
