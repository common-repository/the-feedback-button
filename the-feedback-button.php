<?php
/*
 * Plugin Name: The Feedback Button
 * Plugin URI: http://www.thefeedbackbutton.com/plugins/wordpress
 * Description: Get easy feedback from your readers with a single click.
 * Version: 2.3
 * Author: The Feedback Button
 * Author URI: http://www.thefeedbackbutton.com
 * License: GPL2
 * */
?>
<?php

add_action('admin_menu', 'tfb_add_page_fn');
// Add sub page to the Settings Menu
function tfb_add_page_fn() {
  add_options_page('The Feedback Button', 'The Feedback Button', 'administrator', __FILE__, 'tfb_options_page_fn');
}

function tfb_options_page_fn() {
?>
  <div class="wrap">
    <div class="icon32" id="icon-options-general"><br></div>
    <h2>The Feedback Button</h2>
    Set up your domain profile ID here to be able to view your comments. <h3>Need a valid profile ID? <a href="http://www.thefeedbackbutton.com/users/sign_up">Sign up</a>.</h3>
    <form action="options.php" method="post">
    <?php settings_fields('plugin_options'); ?>
    <?php do_settings_sections(__FILE__); ?>
    <p class="submit">
      <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
    </p>
    <h3><a href="http://www.thefeedbackbutton.com/profile/feedbacks">Log in</a> to check for incoming comments.</h3>
    </form>
  </div>
<?php
}

add_action('admin_init', 'tfb_options_init_fn' );
// Register our settings. Add the settings section, and settings fields
function tfb_options_init_fn(){
  register_setting('plugin_options', 'plugin_options', 'plugin_options_validate' );
  add_settings_section('main_section', 'Button Settings', 'section_text_fn', __FILE__);
  add_settings_field('tfb_profile', 'Profile ID', 'setting_profile', __FILE__, 'main_section');
  add_settings_field('tfb_button_title', 'Button Title', 'setting_button_title', __FILE__, 'main_section');
}

function section_text_fn(){}

function setting_profile() {
  $options = get_option('plugin_options');
  echo "<input id='plugin_text_string' name='plugin_options[tfb_profile]' size='40' type='text' value='{$options['tfb_profile']}' />";
}

function setting_key() {
  $options = get_option('plugin_options');
  echo "<input id='plugin_text_string' name='plugin_options[tfb_key]' size='40' type='text' value='{$options['tfb_key']}' />";
}

function setting_button_title() {
  $options = get_option('plugin_options');
  $button_title = empty($options['tfb_button_title']) ? 'Feedback?' : $options['tfb_button_title'];
  echo "<input id='plugin_text_string' name='plugin_options[tfb_button_title]' size='40' type='text' value='{$button_title}' />";
}


function plugin_options_validate($input) {
  // Check our textbox option field contains no HTML tags - if so strip them out
  $input['text_string'] =  wp_filter_nohtml_kses($input['text_string']);
  return $input; // return validated input
}


// add meta info
add_action('wp_footer', 'tfb_addButton');
function tfb_addButton(){
  $o = get_option('plugin_options');
  $profile = empty($o['tfb_profile']) ? "" : "/{$o['tfb_profile']}";
  $key = empty($o['tfb_key']) ? "" : " data-key=\"{$o['tfb_key']}\"";
  $button_title = empty($o['tfb_button_title']) ? 'Feedback?' : $o['tfb_button_title'];
  echo "<a href=\"http://www.thefeedbackbutton.com/feedback{$profile}\"{$key} class=\"the-feedback-button\">{$button_title}</a>";
  echo '<script type="text/javascript">!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="http://cdn.thefeedbackbutton.com/assets/button/v2.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","the-feedback-button-js");</script>';
}

function no_api_key_provided_admin_notice() {
    $no_api_key_provided =<<<EOS
<p>You have not entered your Profile ID for <b>The Feedback Button</b> plugin. <a href="options-general.php?page=the-feedback-button/the-feedback-button.php">Please enter your API key here</a>.</p>
<p><a href="http://www.thefeedbackbutton.com/users/sign_up">Get your Profile ID if you don't have one here.</a></p>
EOS;
    ?>
    <div class="error">
        <p><?php _e( $no_api_key_provided ); ?></p>
    </div>
    <?php
}

$o = get_option('plugin_options');
if (empty($o['tfb_profile'])) { add_action( 'admin_notices', 'no_api_key_provided_admin_notice'); }

