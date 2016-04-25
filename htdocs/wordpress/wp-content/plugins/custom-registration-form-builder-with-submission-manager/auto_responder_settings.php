<?php
/*Controls custom field creation in the dashboard area*/
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_option=$wpdb->prefix."crf_option";
$path =  plugin_dir_url(__FILE__); 
$autoresponder_options = new crf_basic_options;
if(isset($_REQUEST['saveoption']))
{
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'save_crf_auto_responder_setting' ) ) die( 'Failed security check' );
	if(!isset($_REQUEST['admin_notification'])) $_REQUEST['admin_notification']='no';
	
	$autoresponder_options->crf_add_option( 'adminnotification', $_REQUEST['admin_notification']);
	//echo implode(',',$_REQUEST['optionvalue']);die;
	$autoresponder_options->crf_add_option( 'adminemail', rtrim(implode(',',$_REQUEST['optionvalue']),','));
	$autoresponder_options->crf_add_option( 'from_email', $_REQUEST['from_email']);
	
	wp_redirect('admin.php?page=crf_settings');exit;
}
$admin_email = $autoresponder_options->crf_get_global_option_value('adminemail');
$from_email = $autoresponder_options->crf_get_global_option_value('from_email');
?>
<div class="crf-main-form">
  <div class="crf-form-heading">
    <h1><?php _e( 'Email Notification', $textdomain ); ?></h1>
  </div>
  <form method="post">
    
    <div class="option-main crf-form-setting crf_lock_feature">
      <div class="user-group crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Send Notification to the User for Front-End Notes:', $textdomain ); ?>
        </div>
      </div>
      <div class="user-group-option crf-form-right-area">
        <input name="crf_note_notification" id="crf_note_notification" type="checkbox" class="upb_toggle" value="yes" style="display:none;" disabled/>
        <label for="crf_note_notification"></label>
        <span class="crf_pro_features"><?php _e('Only Available in Pro Editions.',$textdomain);?> <a href="http://registrationmagic.com/?download_id=317&edd_action=add_to_cart"><?php _e('Click here',$textdomain);?></a><?php _e(' to upgrade.',$textdomain);?></span>
      </div>
    </div>
    
    
    <div class="option-main crf-form-setting">
      <div class="user-group crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Send Notification To Site Admin:', $textdomain ); ?>
        </div>
      </div>
      <div class="user-group-option crf-form-right-area">
        <input name="admin_notification" id="admin_notification" type="checkbox" class="upb_toggle" value="yes" <?php if ($autoresponder_options->checkfieldname("adminnotification","yes")==true){ echo "checked";}?> style="display:none;"/>
        <label for="admin_notification"></label>
      </div>
    </div>
    <div id="notification_fun" <?php if ($autoresponder_options->checkfieldname("adminnotification","yes")==true){ echo 'style="display:block"';}else{echo 'style="display:none"';}?>>
    <div class="option-main crf-form-setting">
      <div class="user-group crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Or Define Recipients Manually:', $textdomain ); ?>
        </div>
      </div>
      
      <div class="user-group-option crf-form-right-area" id="optionsfield2">
      <ul id="sortablefield" class="optionsfieldwrapper">
      <?php
	  $optionvalues = @explode(',',$admin_email);
	  foreach($optionvalues as $optionvalue)
	  {
		  ?>
          <li class="optioninputfield">
          <span class="handle"></span>
          	<input type="text" name="optionvalue[]" value="<?php if(!empty($optionvalue)) echo esc_attr($optionvalue); ?>"><span class="removefield" onClick="removefield(this)">Delete</span>
            
            </li>
          <?php
	  }
	  ?>
      </ul>
      <input type="text" value="" placeholder="Click to add another email address" maxlength="0" onClick="addoption()" onKeyUp="addoption()">
      </div>
      
      
      
    </div>
    </div>
    
    
    
    <div class="option-main crf-form-setting">
      <div class="user-group crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'From Email:', $textdomain ); ?>
        </div>
      </div>
      <div class="user-group-option crf-form-right-area">
        <input type="text" name="from_email" id="from_email" placeholder="Wordpress<<?php echo get_option('admin_email'); ?> >" value="<?php if(isset($from_email)) echo $from_email; ?>" />
      </div>
    </div>
  
    <br>
    <br>
    <div class="crf-form-footer">
      <div class="crf-form-button">
      <?php wp_nonce_field('save_crf_auto_responder_setting'); ?>
        <input type="submit"  class="button-primary" value="<?php _e('Save',$textdomain);?>" name="saveoption" id="saveoption" />
        <a href="admin.php?page=crf_settings" class="cancel_button"><?php _e('Cancel',$textdomain);?></a>
      </div>
    </div>
  </form>
</div>