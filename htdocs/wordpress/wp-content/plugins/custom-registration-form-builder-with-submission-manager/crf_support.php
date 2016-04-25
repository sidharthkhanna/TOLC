<?php
/*Controls custom field creation in the dashboard area*/
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_forms =$wpdb->prefix."crf_forms";
$path =  plugin_dir_url(__FILE__); 
?>
<div id="support-feature">
<div class="support-top">
    <div class="hedding-boder">
    <h1 class="support-hedding-icon"><?php _e('Support, Feature Requests and Feedback',$textdomain);?></h1>
    </div>
</div>
<div class="support-available">
  <h3><?php _e('For support, please fill in the support form with relevant details.',$textdomain);?></h3>
  <div class="link">
  <ul>
  <li><a href="http://registrationmagic.com/#contact" target="_blank"><?php _e('SUPPORT FORM',$textdomain);?></a></li>
  </ul>
  </div>
  
</div>
</div>