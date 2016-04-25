<?php
/*Controls custom field creation in the dashboard area*/
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_option=$wpdb->prefix."crf_option";
$path =  plugin_dir_url(__FILE__); 
$thirdparty_options = new crf_basic_options;
if(isset($_REQUEST['saveoption']))
{
		$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'save_crf_thirdparty_setting' ) ) die( 'Failed security check' );
	if(!isset($_REQUEST['enable_mailchimp'])) $_REQUEST['enable_mailchimp']='no';
	if(!isset($_REQUEST['enable_facebook'])) $_REQUEST['enable_facebook']='no';
	$thirdparty_options->crf_add_option( 'enable_facebook', $_REQUEST['enable_facebook']);
	$thirdparty_options->crf_add_option( 'facebook_app_id', $_REQUEST['facebook_app_id']);
	$thirdparty_options->crf_add_option( 'facebook_app_secret', $_REQUEST['facebook_app_secret']);	
	$thirdparty_options->crf_add_option( 'enable_mailchimp', $_REQUEST['enable_mailchimp']);
	$thirdparty_options->crf_add_option( 'mailchimp_key', $_REQUEST['mailchimp_key']);
	wp_redirect('admin.php?page=crf_settings');exit;	
}
$facebook_app_id = $thirdparty_options->crf_get_global_option_value('facebook_app_id');
$facebook_app_secret = $thirdparty_options->crf_get_global_option_value('facebook_app_secret');
$mailchimp_key = $thirdparty_options->crf_get_global_option_value('mailchimp_key');
?>
<div class="crf-main-form">
  <div class="crf-form-heading">
    <h1>
      <?php _e( 'Third Party Integrations', $textdomain ); ?>
    </h1>
  </div>
  <form method="post">
    <div class="option-main crf-form-setting">
      <div class="user-group crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Allow User to Login using Facebook:', $textdomain ); ?>
        </div>
      </div>
      <div class="user-group-option crf-form-right-area">
        <input name="enable_facebook" id="enable_facebook" type="checkbox" class="upb_toggle" value="yes" <?php if ($thirdparty_options->checkfieldname("enable_facebook","yes")==true){ echo "checked";}?> style="display:none;"/>
        <label for="enable_facebook"></label>
      </div>
    </div>
    <div id="facebook_fun" <?php if ($thirdparty_options->checkfieldname("enable_facebook","yes")==true){ echo 'style="display:block"';}else{echo 'style="display:none"';}?>>
      <div class="option-main crf-form-setting">
        <div class="user-group crf-form-left-area">
          <div class="crf-label">
            <?php _e( 'Facebook App ID:', $textdomain ); ?>
          </div>
        </div>
        <div class="user-group-option crf-form-right-area">
          <input type="text" name="facebook_app_id" id="facebook_app_id" value="<?php if(isset($facebook_app_id)) echo esc_attr($facebook_app_id); ?>" />
        </div>
      </div>
      <div class="option-main crf-form-setting">
        <div class="user-group crf-form-left-area">
          <div class="crf-label">
            <?php _e( 'Facebook App Secret:', $textdomain ); ?>
          </div>
        </div>
        <div class="user-group-option crf-form-right-area">
          <input type="text" name="facebook_app_secret" id="facebook_app_secret" value="<?php if(isset($facebook_app_secret)) echo esc_attr($facebook_app_secret); ?>" />
        </div>
      </div>
    </div>
    <div class="option-main crf-form-setting">
      <div class="user-group crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'MailChimp Integration:', $textdomain ); ?>
        </div>
      </div>
      <div class="user-group-option crf-form-right-area">
        <input name="enable_mailchimp" id="enable_mailchimp" type="checkbox" class="upb_toggle" value="yes" <?php if ($thirdparty_options->checkfieldname("enable_mailchimp","yes")==true){ echo "checked";}?> style="display:none;"/>
        <label for="enable_mailchimp"></label>
      </div>
    </div>
    <div class="option-main ">
      <div id="mailchimp_fun" <?php if ($thirdparty_options->checkfieldname("enable_mailchimp","yes")==true){ echo 'style="display:block"';}else{echo 'style="display:none"';}?>>
        <div class="option-main crf-form-setting">
          <div class="user-group crf-form-left-area">
            <div class="crf-label">
              <?php _e( 'MailChimp API:', $textdomain ); ?>
            </div>
          </div>
          <div class="user-group-option crf-form-right-area">
            <input type="text" name="mailchimp_key" id="mailchimp_key" value="<?php if(isset($mailchimp_key)) echo $mailchimp_key; ?>" />
          </div>
        </div>
      </div>
    </div>
    <br>
    <br>
    <div class="crf-form-footer">
      <div class="crf-form-button">
        <?php wp_nonce_field('save_crf_thirdparty_setting'); ?>
        <input type="submit"  class="button-primary" value="<?php _e('Save',$textdomain);?>" name="saveoption" id="saveoption" />
        <a href="admin.php?page=crf_settings" class="cancel_button">
        <?php _e('Cancel',$textdomain);?>
        </a> </div>
    </div>
  </form>
</div>
