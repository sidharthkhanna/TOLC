<?php

global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_submissions =$wpdb->prefix."crf_submissions";
$crf_fields =$wpdb->prefix."crf_fields";
$crf_forms =$wpdb->prefix."crf_forms";
$path =  plugin_dir_url(__FILE__); 
include 'classes/class_basic_fields-backend.php';
$form_fields = new crf_basic_fields_backend;
$crf_userid = $_REQUEST['id'];

$user_info = get_userdata($crf_userid);
//print_r($user_info );
$roles = get_editable_roles();
$userrole = $form_fields->crf_get_userrole_name($crf_userid);
$avatar = get_avatar($user_info->user_email, 300 );
$submissions = $form_fields->crf_submision_get_id_with_field('user_email',$user_info->user_email);
//print_r($submissions);
$formid = array();
foreach($submissions as $submission)
{
	$formid[] = $submission->form_id;	
}

if(isset($_GET['status']))
{
	update_user_meta($crf_userid,'crf_user_status',$_GET['status']);
	wp_redirect('admin.php?page=crf_view_wp_user&id='.$crf_userid);exit;
}

if(isset($_GET['action']) && $_GET['action']=='delete')
{
	wp_delete_user($crf_userid);
	wp_redirect('admin.php?page=crf_user_manager');exit;
}

if(isset($_POST['submit'])) // Checks if the submit button is pressed or not
{
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'view_crf_form' ) ) die( 'Failed security check' );
	$form_fields->crf_update_backend_user_meta($formid,$crf_userid,$_POST,$_FILES,$_SERVER);
	wp_redirect('admin.php?page=crf_view_wp_user&id='.$crf_userid);exit;
}
?>
<div id="crf-form"  class="crf-main-form">
<div class="crf-form-heading">
<h1>Edit User</h1>
</div>
  <form enctype="multipart/form-data" method="post" action="" id="crf_contact_form" name="crf_contact_form">
  <input type="hidden" name="userid" value="<?php echo $crf_userid;?>" />
  <?php wp_nonce_field('view_crf_form'); ?>
   <div class="crf_contact_form">
      <?php 

	  $form_fields->crf_field_creation($formid,$crf_userid);
	  
       ?>
      <br class="clear">
    </div>
    
    <div class="customcrferror crf_error_text" style="display:none"></div>
    
    <div class="UltimatePB-Button-area crf_input crf_input_submit form-submit crf-form-footer" >
    <div class="crf-form-button">
      <input type="submit" value="Save" class="crf_contact_submit primary" id="submit" name="submit">
   <input type="button" value="Cancel" class="crf-back-button cancel_button" name="field_back" id="field_back" onclick="redirectuser(<?php echo $crf_userid;?>,'crf_view_wp_user')">
      </div>
     
    </div>
  </form>
</div>

<script>
// JavaScript Document	
jQuery('#crf_contact_form').submit(function () 
{
	//email validation start for custom field	
	var email_val = "";
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	jQuery('.custom_error').html('');
	jQuery('.custom_error').hide();
	jQuery('.customcrferror').html('');
	
	var password = jQuery('#inputPassword').val();
	var confirmpassword = jQuery('#user_confirm_password').val();
	var passwordlength = password.length;
	if(password !="")
	{
		if(passwordlength < 7)
		{
			jQuery('.crf_password').children('.custom_error').html('<?php _e('Your password should be at least 7 characters long.',$textdomain);?>');
			jQuery('.crf_password').children('.custom_error').show();
		}
		if(password != confirmpassword)
		{
			jQuery('.crf_confirmpassword').children('.custom_error').html('<?php _e('Password and confirm password do not match.',$textdomain);?>');
			jQuery('.crf_confirmpassword').children('.custom_error').show();
		}
	}
	
	jQuery('.crf_email').each(function (index, element) {
		var email = jQuery(this).children('input').val();
		var isemail = regex.test(email);
		if (isemail == false && email != "") {
			jQuery(this).children('.custom_error').html('<?php _e('Please enter a valid e-mail address.',$textdomain);?>');
			jQuery(this).children('.custom_error').show();
		}
	});
	
	jQuery('.crf_number').each(function (index, element) { //Validation for number type custom field
		var number = jQuery(this).children('input').val();
		var isnumber = jQuery.isNumeric(number);
		if (isnumber == false && number != "") {
			jQuery(this).children('.custom_error').html('<?php _e('Please enter a valid number.',$textdomain);?>');
			jQuery(this).children('.custom_error').show();
		}
	});
	
	jQuery('.crf_datepicker').each(function (index, element) { //Validation for number type custom field
	
		var date = jQuery(this).children('input').val();
		var pattern = /^([0-9]{4})-([0-9]{2})-([0-9]{2})$/;
    	if (date != "" && !pattern.test(date)) {
       		jQuery(this).children('.custom_error').html('<?php _e('Please enter a valid date (yyyy-mm-dd format)',$textdomain);?>');
	   		jQuery(this).children('.custom_error').show();
    	}
	});
	
	/*jQuery('.crf_required').each(function (index, element) { //Validation for number type custom field
		var value = jQuery(this).children('input').val();
		var value2 = jQuery.trim(value);
		if (value == "" || value2== "") {
			jQuery(this).children('.custom_error').html('This is a required field.');
			jQuery(this).children('.custom_error').show();
		}
	});*/
	
	/*jQuery('.crf_select_required').each(function (index, element) { //Validation for number type custom field
		var value = jQuery(this).children('select').val();
		var value2 = jQuery.trim(value);
		if (value == "" || value2== "") {
			jQuery(this).children('.custom_error').html('This is a required field.');
			jQuery(this).children('.custom_error').show();
		}
	});
	*/
	/*jQuery('.crf_textarearequired').each(function (index, element) { //Validation for number type custom field
		var value = jQuery(this).children('textarea').val();
		var value2 = jQuery.trim(value);
		if (value == "" || value2== "") {
			jQuery(this).children('.custom_error').html('This is a required field.');
			jQuery(this).children('.custom_error').show();
		}
	});*/
	
	/*jQuery('.crf_checkboxrequired').each(function (index, element) { //Validation for number type custom field
	var checkboxlenght = jQuery(this).children('label').children('input[type="checkbox"]:checked');
	
	var atLeastOneIsChecked = checkboxlenght.length > 0;
	if (atLeastOneIsChecked == true) {
	}else{
			jQuery(this).children('.custom_error').html('This is a required field.');
			jQuery(this).children('.custom_error').show();
		}
	
	});*/
	
	/*jQuery('.crf_termboxrequired').each(function (index, element) { //Validation for number type custom field
	var checkboxlenght = jQuery(this).children('label').children('input[type="checkbox"]:checked');
	
	var atLeastOneIsChecked = checkboxlenght.length > 0;
	if (atLeastOneIsChecked == true) {
	}else{
			jQuery(this).children('.custom_error').html('This is a required field.');
			jQuery(this).children('.custom_error').show();
		}
	
	});*/
	
	jQuery('.crf_file').each(function (index, element) {
			var val = jQuery(this).children('input').val().toLowerCase();
			var allowextensions = jQuery(this).children('input').attr('data-filter-placeholder');
			if(allowextensions=='')
			{
				allowextensions = '<?php echo get_option('ucf_allowfiletypes','jpg|jpeg|png|gif|doc|pdf|docx|txt|psd'); ?>';
			}
			
			allowextensions = allowextensions.toLowerCase();
			var regex = new RegExp("(.*?)\.(" + allowextensions + ")$");
			if(!(regex.test(val)) && val!="") {
			
				jQuery(this).children('.custom_error').html('<?php _e('This file type is not allowed.',$textdomain);?>');
                jQuery(this).children('.custom_error').show();
			}
        });
	
	/*jQuery('.crf_radiorequired').each(function (index, element) { //Validation for number type custom field
	var radiolenght = jQuery(this).children('label').children('input[type="radio"]:checked');
	
	var atLeastOneIsChecked = radiolenght.length > 0;
	if (atLeastOneIsChecked == true) {
	}else{
			jQuery(this).children('.custom_error').html('This is a required field.');
			jQuery(this).children('.custom_error').show();
		}
	
	});*/
	
	var b = '';
	b = jQuery('.custom_error').each(function () {
		var a = jQuery(this).html();
		b = a + b;
		jQuery('.customcrferror').html(b);
	});
	var error = jQuery('.customcrferror').html();
	if (error == '') {
		return true;
	} else {
		return false;
	}
});
jQuery('.input-box').addClass('crf_input');
jQuery('.lable-text').addClass('crf_label');
jQuery('.crf_estric').hide();
/*jQuery('.crf_required').parent('.formtable').children('.crf_label').children('label').append('<sup class="crf_estric">*</sup>');
jQuery('.crf_select_required').parent('.formtable').children('.crf_label').children('label').append('<sup class="crf_estric">*</sup>');
jQuery('.crf_radiorequired').parent('.formtable').children('.crf_label').children('label').append('<sup class="crf_estric">*</sup>');
jQuery('.crf_checkboxrequired').parent('.formtable').children('.crf_label').children('label').append('<sup class="crf_estric">*</sup>');
jQuery('.crf_textarearequired').parent('.formtable').children('.crf_label').children('label').append('<sup class="crf_estric">*</sup>');*/
function addextrafile(a) 
{
	var b = jQuery(a).parents('.fileinput').clone();
	jQuery(a).parents('.filewrapper .crf_contact_attachment').append(b);
}
function removeextrafile(a)
{
	jQuery(a).parents('.fileinput').remove();
}
function addextratext(a) 
{
	var b = jQuery(a).parents('.fileinput').clone();
	jQuery(b).children('input').val('');
	jQuery(a).parents('.textwrapper .crf_contact_textfield').append(b);
}
function removeextratext(a)
{
	jQuery(a).parents('.fileinput').remove();
}
function showotherbox(a)
{
	jQuery(a).parent('label').parent('.crf_checkbox').children('.otherbx').toggle(500);
}
</script>