<?php
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_submissions =$wpdb->prefix."crf_submissions";
$crf_fields =$wpdb->prefix."crf_fields";
$crf_forms =$wpdb->prefix."crf_forms";
$crf_stats =$wpdb->prefix."crf_stats";
$path =  plugin_dir_url(__FILE__); 
?>
<div class="crf-main-form">
  <div class="crf-main-form-top-area">
    <div class="crf-form-name-buttons" style="float:left; margin:10px;">
      <div class="crf-setting"><img src="<?php echo $path; ?>images/global_settings.png"></div>
    </div>
    <div class="crf-form-name-heading" style=" padding-left:0px;">
      <h1>
        <?php _e( 'Global Settings', $textdomain ); ?>
      </h1>
    </div>
  </div>
  <div class="rf-form-icon-wrap">
    <div class="rf-main-icons">
    
      <div class="rf-form-icon">
      <a href="admin.php?page=crf_general_settings"> 
      <div class="crf_setting_image">
      <img src="<?php echo $path;?>images/general.png" class="options" alt="options">
      </div>
      <div class="crf_heading"> 
      <span class="crf-form-icon-title"><?php _e( 'General', $textdomain ); ?></span> 
      <span class="crf_description"><?php _e( 'Form look, Default pages, Attachment settings etc.', $textdomain ); ?></span>
      </div>
      
      </a>
      </div>
      
      <div class="rf-form-icon">
      <a href="admin.php?page=crf_antispam_settings"> 
      <div class="crf_setting_image">
      <img src="<?php echo $path;?>images/security.png" class="options" alt="options">
      </div>
      <div class="crf_heading"> 
      <span class="crf-form-icon-title"><?php _e( 'Security', $textdomain ); ?></span> 
      <span class="crf_description"><?php _e( 'reCAPTCHA placement, Google reCAPTCHA keys', $textdomain ); ?></span>
      </div>
      
      </a>
      </div>
      
      <div class="rf-form-icon">
      <a href="admin.php?page=crf_user_settings"> 
      <div class="crf_setting_image">
      <img src="<?php echo $path;?>images/usersettings.png" class="options" alt="options">
      </div>
      <div class="crf_heading"> 
      <span class="crf-form-icon-title"><?php _e( 'User Accounts', $textdomain ); ?></span> 
      <span class="crf_description"><?php _e( 'Password behavior, Manual approvals etc.', $textdomain ); ?></span>
      </div>
      
      </a>
      </div>
      
      <div class="rf-form-icon">
      <a href="admin.php?page=crf_autoresponder_settings"> 
      <div class="crf_setting_image">
      <img src="<?php echo $path;?>images/autoresponder.png" class="options" alt="options">
      </div>
      <div class="crf_heading"> 
      <span class="crf-form-icon-title"><?php _e( 'Email Notifications', $textdomain ); ?></span> 
      <span class="crf_description"><?php _e( 'Admin notifications, multiple email notifications, From email', $textdomain ); ?></span>
      </div>
      
      </a>
      </div>
      
      <div class="rf-form-icon">
      <a href="admin.php?page=crf_thirdparty_settings"> 
      <div class="crf_setting_image">
      <img src="<?php echo $path;?>images/third_party.png" class="options" alt="options">
      </div>
      <div class="crf_heading"> 
      <span class="crf-form-icon-title"><?php _e( 'Third Party Integrations', $textdomain ); ?></span> 
      <span class="crf_description"><?php _e( 'Facebook, MailChimp (more coming soon!)', $textdomain ); ?></span>
      </div>
      
      </a>
      </div>
      
      <div class="rf-form-icon">
      <a href="admin.php?page=crf_payment_settings"> 
      <div class="crf_setting_image">
      <img src="<?php echo $path;?>images/payments.png" class="options" alt="options">
      </div>
      <div class="crf_heading"> 
      <span class="crf-form-icon-title"><?php _e( 'Payments', $textdomain ); ?></span> 
      <span class="crf_description"><?php _e( 'Currency, Symbol Position, Checkout Page etc.', $textdomain ); ?></span>
      </div>
      
      </a>
      </div>
      
    </div>
  </div>
</div>
