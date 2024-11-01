<?php
/*
Plugin Name: WP Twitter Connect
Plugin URI: http://technorious.com/wordpress-plugins/wp-twitter-connect
Description: Adds Twitter @Anywhere to your wordpress blog
Version: 0.1
Author: Technorious
Author URI: http://technorious.com
*/

/*  Copyright 2010  Technorious  (email : ping@technorious.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// display button on all posts and pages
function wpta_add_twitter_anywhere($content) {
   $linkify_target = get_option('wpta-linkify-target');
   $hovercard_target = get_option('wpta-hovercard-target');
   $follow_button_user = get_option('wpta-followbutton-user');
   $twitter_api_key = get_option('wpta-twitter-apikey');

   if ($linkify_target) $linkify_js = "\t\ttwitter('$linkify_target').linkifyUsers();\n";
   else $linkify_js = "\t\ttwitter.linkifyUsers();\n";
   if ($hovercard_target) $hovercard_js = "\t\ttwitter('$hovercard_target').hovercards();\n";
   else $hovercard_js = "\t\ttwitter.hovercards();\n";

   $anywhere_setup_js = "";
   if (is_single()) {
      $linkify_posts = get_option('wpta-linkify-posts');
      $hovercard_posts = get_option('wpta-hovercard-posts');
      if ($linkify_posts == 1) $anywhere_setup_js .= $linkify_js;
      if ($hovercard_posts == 1) $anywhere_setup_js .= $hovercard_js;
   } else if (is_home()) {
      $linkify_home = get_option('wpta-linkify-home');
      $hovercard_home = get_option('wpta-hovercard-home');
      if ($linkify_home == 1) $anywhere_setup_js .= $linkify_js;
      if ($hovercard_home == 1) $anywhere_setup_js .= $hovercard_js;
   } else if (is_page()) {
      $linkify_pages = get_option('wpta-linkify-pages');
      $hovercard_pages = get_option('wpta-hovercard-pages');
      if ($linkify_pages == 1) $anywhere_setup_js .= $linkify_js;
      if ($hovercard_pages == 1) $anywhere_setup_js .= $hovercard_js;
   }
   if ($follow_button_user) {
       $anywhere_setup_js .= "\t\ttwitter('.twitter-follow-button').followButton('$follow_button_user');\n";
   }
   if ($anywhere_setup_js) {
?>
         <script src="http://platform.twitter.com/anywhere.js?id=<?php echo $twitter_api_key; ?>&v=1" type="text/javascript"></script>
         <script type="text/javascript">
             twttr.anywhere(onAnywhereLoad);
             function onAnywhereLoad(twitter) {
<?php echo $anywhere_setup_js; ?>
             }
         </script>
<?
   }
   
}




function wpta_settings_page() {
?>
         <script src="http://platform.twitter.com/anywhere.js?id=lDQkTV1fnkrxv5lnOcAWfA&v=1" type="text/javascript"></script>
         <script type="text/javascript">
             twttr.anywhere(onAnywhereLoad);
             function onAnywhereLoad(twitter) {
				twitter('#wpta_linkify_demo').linkifyUsers();
				twitter('#wpta_hovercard_demo').hovercards();
				twitter('#wpta_followbutton_demo').followButton('technorious');
             }
         </script>

    <div class="wrap">
    <h2>WP Twitter Anywhere Settings</h2>
    <form method="post" action="options.php">
    <?php
        if (function_exists('settings_field')) {
            settings_field("wpta-options");
        } else { 
            wp_nonce_field('update-options'); 
        }
    ?>
    <table class="form-table">
    <tr valign="top">
      <th scope="row">Twitter API KEY</th>
      <td><input type="text" name="wpta-twitter-apikey" value="<?php echo get_option('wpta-twitter-apikey'); ?>" /> 
      </td>
      <td rowspan="3">
        <div style='background: #ffc; border: 1px solid #333; margin: 2px; padding: 5px;'>
        <h3 align='center'><?php echo 'Twitter Anywhere Demo'; ?></h3>
         <div id="wpta_linkify_demo">The Linkify Example: Tweet me @technorious</div><br />
         <div id="wpta_hovercard_demo">The Hovercard Example: Tweet me @technorious</div><br />
         <div id="wpta_followbutton_demo">The FollowButton Example: </div><br />
        <div align='center'></div>
        </div>
      </td>
    </tr>
 
    <tr valign="top">
      <th scope="row">Enable Linkify On</th>
      <td><input type="checkbox" name="wpta-linkify-posts" value="1" <?php if (get_option('wpta-linkify-posts') == '1') { ?>checked="checked"<?php } ?>/>Posts
          <input type="checkbox" name="wpta-linkify-pages" value="1" <?php if (get_option('wpta-linkify-pages') == '1') { ?>checked="checked"<?php } ?>/>Pages
          <input type="checkbox" name="wpta-linkify-home" value="1" <?php if (get_option('wpta-linkify-home') == '1') { ?>checked="checked"<?php } ?>/>Home
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">Linkify Target</th>
      <td><input type="text" name="wpta-linkify-target" value="<?php echo get_option('wpta-linkify-target'); ?>" /> (CSS Selector: e.g: .post, .entry or #content)<br />
      Note: If no target specified, it defaults to whole page.
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">Enable Hovercard On</th>
      <td><input type="checkbox" name="wpta-hovercard-posts" value="1" <?php if (get_option('wpta-hovercard-posts') == '1') { ?>checked="checked"<?php } ?>/>Posts
          <input type="checkbox" name="wpta-hovercard-pages" value="1" <?php if (get_option('wpta-hovercard-pages') == '1') { ?>checked="checked"<?php } ?>/>Pages
          <input type="checkbox" name="wpta-hovercard-home" value="1" <?php if (get_option('wpta-hovercard-home') == '1') { ?>checked="checked"<?php } ?>/>Home
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">Hovercard Target</th>
      <td><input type="text" name="wpta-hovercard-target" value="<?php echo get_option('wpta-hovercard-target'); ?>" /> (CSS Selector: e.g: .post, .entry or #content)<br />
      Note: If no target specified, it defaults to whole page.
      </td>
    </tr>
    <tr valign="top">
      <th scope="row">Follow Button User</th>
      <td><input type="text" name="wpta-followbutton-user" value="<?php echo get_option('wpta-followbutton-user'); ?>" /> (Leave empty to not use it.)<br />
      <h3>How to display button:</h3>
      Put the following HTML where you want to display the follow button:<br /> &lt;div class="twitter-follow-button"&gt;&lt;/div&gt; 
      </td>
    </tr>
   </div>
    </table>
    <input type="hidden" name="action" value="update" />
    <input type="hidden" name="page_options" value="wpta-twitter-apikey,wpta-linkify-posts,wpta-linkify-home,wpta-linkify-pages,wpta-linkify-target,wpta-hovercard-posts,wpta-hovercard-home,wpta-hovercard-pages,wpta-hovercard-target,wpta-followbutton-user" />
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
    </form>
    </div>


<?
}


function wpta_activate() {
    add_option('wpta-twitter-apikey', '');
    add_option('wpta-linkify-home', 1);
    add_option('wpta-linkify-posts', 1);
    add_option('wpta-linkify-pages', 1);
    add_option('wpta-linkify-target', '');  // css selector
    add_option('wpta-hovercard-home', 1);
    add_option('wpta-hovercard-posts', 1);
    add_option('wpta-hovercard-pages', 1);
    add_option('wpta-hovercard-target', '');  // css selector
    add_option('wpta-followbutton-user', '');
}

function wpta_register_settings() {
    if (function_exists('register_settings')) {
        register_settings('wpta-options', 'wpta-twitter-apikey');
        register_settings('wpta-options', 'wpta-linkify-home');
        register_settings('wpta-options', 'wpta-linkify-posts');
        register_settings('wpta-options', 'wpta-linkify-pages');
        register_settings('wpta-options', 'wpta-linkify-target');
        register_settings('wpta-options', 'wpta-hovercard-home');
        register_settings('wpta-options', 'wpta-hovercard-posts');
        register_settings('wpta-options', 'wpta-hovercard-pages');
        register_settings('wpta-options', 'wpta-hovercard-target');
        register_settings('wpta-options', 'wpta-followbutton-user');
    }
}

/* add the settings page */
function wpta_menu() {
    if (function_exists('add_options_page')) {
        add_options_page('WP Twitter Connect', 'WP Twitter Connect', 'administrator', basename(__FILE__), 'wpta_settings_page');
    }
}


if ( is_admin() ){ // admin actions
    add_action( 'admin_init', 'wpta_register_settings' );
    add_action( 'admin_menu', 'wpta_menu' );
} 

add_action('wp_head', 'wpta_add_twitter_anywhere', 100);

register_activation_hook( __FILE__, 'wpta_activate');
?>
