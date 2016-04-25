<?php
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_forms =$wpdb->prefix."crf_forms";
$path =  plugin_dir_url(__FILE__); 
$options = 'a:18:{s:19:"submit_button_label";s:6:"Submit";s:19:"submit_button_color";s:0:"";s:21:"submit_button_bgcolor";s:0:"";s:14:"mailchimp_list";s:0:"";s:12:"auto_expires";N;s:11:"expiry_type";N;s:16:"submission_limit";s:0:"";s:11:"expiry_date";s:0:"";s:14:"expiry_message";s:0:"";s:9:"user_role";s:0:"";s:15:"let_user_decide";N;s:15:"user_role_label";s:0:"";s:17:"user_role_options";N;s:20:"mailchimp_emailfield";s:0:"";s:20:"mailchimp_firstfield";s:0:"";s:19:"mailchimp_lastfield";s:0:"";s:9:"optin_box";N;s:14:"optin_box_text";s:0:"";}';
$qry = "insert into $crf_forms values('','".$_POST['name']."','','contact_form','','','','','none','0','','0','".$options."')";
$form_id = $wpdb->query($qry);
if(isset($form_id) && $form_id!=0)
{
	echo 'form created';	
}
?>