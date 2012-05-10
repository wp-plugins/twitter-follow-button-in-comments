<?php
/*
Plugin Name: Twitter follow button in coments
Description: This plugin will allow your visitors to submit their twitter, while they are commenting your posts. They account will by showed as a twitter follow button.
Author: Michal Ozogán
Version: 0.2
Author URI: http://fabulator.cz/
*/
?>
<?php

function tfbin_create_menu() {	
	add_options_page('Twitter follow button Settings', 'Twitter follow button Setting', administrator, 'twitter-follow-button', 'tfbin_twitter_follow_button_page'); 
	add_action( 'admin_init', 'tfbin_register_mysettings' );
	}
add_action('admin_menu', 'tfbin_create_menu');

function tfbin_register_mysettings() {
	register_setting( 'tfbin-settings-group', 'tfbin-show_count' );
	register_setting( 'tfbin-settings-group', 'tfbin-data_size' );
	register_setting( 'tfbin-settings-group', 'tfbin-lang' );
	register_setting( 'tfbin-settings-group', 'tfbin-text_color' );
	register_setting( 'tfbin-settings-group', 'tfbin-link_color' );
	}
function tfbin_twitter_follow_button_page() {
?>
<div class="wrap">
	<h2>Twitter follow button Setting</h2>
	<form method="post" action="options.php">
		<?php settings_fields( 'tfbin-settings-group' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Large or Medium button?</th>
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
				<th scope="row"><label for="tfbin-lang">Language options</label></th>
				<td>
				<select name="tfbin-lang">
					<option value="nl" <?php if(get_option('tfbin-lang') == 'nl') echo "selected"; ?>>Dutch</option>
					<option value="en" <?php if(get_option('tfbin-lang') == 'en' or get_option('tfbin-lang') == "") echo "selected"; ?>>English</option>
					<option value="fr" <?php if(get_option('tfbin-lang') == 'fr') echo "selected"; ?>>French</option>
					<option value="de" <?php if(get_option('tfbin-lang') == 'de') echo "selected"; ?>>German</option>
					<option value="id" <?php if(get_option('tfbin-lang') == 'id') echo "selected"; ?>>Indonesian</option>
					<option value="it" <?php if(get_option('tfbin-lang') == 'it') echo "selected"; ?>>Italian</option>
					<option value="ja" <?php if(get_option('tfbin-lang') == 'ja') echo "selected"; ?>>Japanese</option>
					<option value="ko" <?php if(get_option('tfbin-lang') == 'ko') echo "selected"; ?>>Korean</option>
					<option value="pt" <?php if(get_option('tfbin-lang') == 'pt') echo "selected"; ?>>Portuguese</option>
					<option value="ru" <?php if(get_option('tfbin-lang') == 'ru') echo "selected"; ?>>Russian</option>
					<option value="es" <?php if(get_option('tfbin-lang') == 'es') echo "selected"; ?>>Spanish</option>
					<option value="tr" <?php if(get_option('tfbin-lang') == 'tr') echo "selected"; ?>>Turkish</option>
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

add_action('wp_footer', 'tfbin_footer_function');
function tfbin_footer_function() {
	?>
	<script src="http://platform.twitter.com/widgets.js" type="text/javascript"></script>
	<?php 
	}

	
add_filter( 'comment_form_defaults', 'pridat_pole_ke_komentarum');
function pridat_pole_ke_komentarum( $default ) {
    $commenter = wp_get_current_commenter();
    $default[ 'fields' ][ 'email' ] .= '<p class="comment-form-author">' .
        '<label for="twitter">Twitter</label>
        <input name="twitter" size="30" type="text" value="'. esc_attr( $commenter['twitter'] ) .'"/></p>';
    return $default;
	}
	
add_action( 'comment_post', 'ulozit_komentar' );	
function ulozit_komentar( $comment_id ) {
    add_comment_meta( $comment_id, 'twitter', $_POST[ 'twitter' ] );
	}

 add_action('show_user_profile', 'pridat_polozky_do_profilu');
 add_action('edit_user_profile', 'pridat_polozky_do_profilu');
 function pridat_polozky_do_profilu($user)
 {
 ?>
 <table>
 <tr>
 <th><label for="twitter">Twitter: </label></th>
 <td><input type="text" name="twitter" id="twitter" value="<?php echo esc_attr(get_the_author_meta('twitter', $user->ID) ); ?>" /></td>
 </tr>
 </table>
 <?php
 }
 
add_action( 'personal_options_update', 'ulozit_polozky_do_profilu' );
add_action( 'edit_user_profile_update', 'ulozit_polozky_do_profilu' );
function ulozit_polozky_do_profilu( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;
	update_usermeta( $user_id, 'twitter', $_POST['twitter'] );
}

add_filter( 'get_comment_text', 'attach_twitter_to_text' );
function attach_twitter_to_text( $text ) {
	global $comment;	
	if($comment->user_id == 0) $twitter = get_comment_meta( get_comment_ID(), 'twitter', true );
	else $twitter = get_user_meta($comment->user_id, 'twitter', true);
	$twitter = str_replace("@","",$twitter);
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
