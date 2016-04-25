<?php
/*Controls custom field creation in the dashboard area*/
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_option=$wpdb->prefix."crf_option";
$path =  plugin_dir_url(__FILE__); 
$general_options = new crf_basic_options;
if(isset($_REQUEST['saveoption']))
{
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'save_crf_general_setting' ) ) die( 'Failed security check' );
	if(!isset($_REQUEST['send_password'])) $_REQUEST['send_password']='no';
	if(!isset($_REQUEST['userip'])) $_REQUEST['userip']='no';
	$general_options->crf_add_option( 'userip', $_REQUEST['userip']);	
	$general_options->crf_add_option( 'crf_theme', $_REQUEST['crf_theme']);
	update_option( 'ucf_default_Registration_url',$_POST['default_registration_url']);	
	update_option( 'ucf_redirect_after_login',$_POST['redirect_after_login']);	
	wp_redirect('admin.php?page=crf_settings');exit;	
}
$crf_theme = $general_options->crf_get_global_option_value('crf_theme');
?>
<div class="crf-main-form">
  <div class="crf-form-heading">
    <h1>
      <?php _e( 'General', $textdomain ); ?>
    </h1>
  </div>
  <form method="post">
    <div class="option-main crf-form-setting">
      <div class="user-group crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Form Style:', $textdomain ); ?>
        </div>
      </div>
      <div class="user-group-option crf-form-right-area">
        <select name="crf_theme" id="crf_theme">
          <option value="classic" <?php if($crf_theme=='classic')echo 'selected';?>>Classic</option>
          <option value="default" <?php if($crf_theme=='default')echo 'selected';?>>Match My Theme</option>
        </select>
      </div>
    </div>
    <div class="option-main crf-form-setting">
      <div class="user-group crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Capture IP and Browser Info:', $textdomain ); ?>
        </div>
      </div>
      <div class="user-group-option crf-form-right-area">
        <input name="userip" id="userip" type="checkbox" class="upb_toggle" value="yes" <?php if ($general_options->checkfieldname("userip","yes")==true){ echo "checked";}?> style="display:none;" />
        <label for="userip"></label>
      </div>
    </div>
    <div class="option-main crf-form-setting crf_lock_feature">
      <div class="user-group crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Define allowed file types (file extensions)', $textdomain ); ?>
          <small>
          <?php _e( '(Separate multiple values by “|”. For example PDF|JPEG|XLS)', $textdomain ); ?>
          </small>
           </div>
            <span class="crf_pro_features"><?php _e('Only Available in Pro Editions.',$textdomain);?> <a href="http://registrationmagic.com/?download_id=317&edd_action=add_to_cart"><?php _e('Click here',$textdomain);?></a><?php _e(' to upgrade.',$textdomain);?></span>
      </div>
      <div class="user-group-option crf-form-right-area">
        <textarea name="filetypes" id="filetypes" disabled><?php echo get_option('ucf_allowfiletypes','jpg|jpeg|png|gif|doc|pdf|docx|txt|psd'); ?></textarea>
       
      </div>
    </div>
    <div class="option-main crf-form-setting crf_lock_feature">
      <div class="user-group crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Allow Multiple Attachments:', $textdomain ); ?>
        </div>
      </div>
      <div class="user-group-option crf-form-right-area">
        <input name="repeatedfilefield" id="repeatedfilefield" type="checkbox" class="upb_toggle" value="yes" style="display:none;" <?php if(get_option('ucf_repeatfilefields','no')=='yes')echo 'checked'; ?> disabled/>
        <label for="repeatedfilefield"></label>
        <span class="crf_pro_features"><?php _e('Only Available in Pro Editions.',$textdomain);?> <a href="http://registrationmagic.com/?download_id=317&edd_action=add_to_cart"><?php _e('Click here',$textdomain);?></a><?php _e(' to upgrade.',$textdomain);?></span>
      </div>
    </div>
    <div class="option-main crf-form-setting">
      <div class="user-group crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Default WP Registration Page:', $textdomain ); ?>
        </div>
      </div>
      <div class="user-group-option crf-form-right-area">
        <?php 
	   $default_registration_url = get_option('ucf_default_Registration_url');
			if(!empty($default_registration_url))
			{
				$selected = $default_registration_url;
			}
			else
			{
				$selected = 0;
			}
			$args = array(
				'depth'            => 0,
				'child_of'         => 0,
				'selected'         => $selected,
				'echo'             => 1,
				'show_option_none'      => 'Select Page',
    			'option_none_value'     => 0, 
				'name'             => 'default_registration_url'); 
			wp_dropdown_pages($args); 
		?>
      </div>
    </div>
    <div class="option-main crf-form-setting">
      <div class="user-group crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'After Login Redirect User to:', $textdomain ); ?>
        </div>
      </div>
      <div class="user-group-option crf-form-right-area">
        <?php 
	   $ucf_redirect_after_login = get_option('ucf_redirect_after_login');
			if(!empty($ucf_redirect_after_login))
			{
				$selected = $ucf_redirect_after_login;
			}
			else
			{
				$selected = 0;
			}
			$args = array(
				'depth'            => 0,
				'child_of'         => 0,
				'selected'         => $selected,
				'echo'             => 1,
				'show_option_none'      => 'Select Page',
    			'option_none_value'     => 0, 
				'name'             => 'redirect_after_login'); 
			wp_dropdown_pages($args); 
		?>
      </div>
    </div>
    <br>
    <br>
    <div class="crf-form-footer">
      <div class="crf-form-button">
        <?php wp_nonce_field('save_crf_general_setting'); ?>
        <input type="submit"  class="button-primary" value="<?php _e('Save',$textdomain);?>" name="saveoption" id="saveoption" />
        <a href="admin.php?page=crf_settings" class="cancel_button">
        <?php _e('Cancel',$textdomain);?>
        </a> </div>
    </div>
  </form>
</div>