<?php $textdomain = 'custom-registration-form-builder-with-submission-manager';?>
<script>
// JavaScript Document	
function frontend_validation(form)
//jQuery('#crf_contact_form').submit(function () 
{
	//email validation start for custom field	
	var email_val = "";
	var formid = form.id;
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	jQuery('.custom_error').html('');
	jQuery('.custom_error').hide();
	jQuery('.customcrferror').html('');
	
	var form_type = jQuery('#'+formid+' input[name=form_type]').val();
	if(form_type == 'reg_form')
	{
		var password = jQuery('#'+formid+' #inputPassword').val();
		var confirmpassword = jQuery('#'+formid+' #user_confirm_password').val();
		var passwordlength = password.length;
		if(password !="")
		{
			if(passwordlength < 7)
			{
				jQuery('#'+formid+' .crf_password').children('.custom_error').html('<?php _e('Your password should be at least 7 characters long.',$textdomain);?>');
				jQuery('#'+formid+' .crf_password').children('.custom_error').show();
			}
			if(password != confirmpassword)
			{
				jQuery('#'+formid+' .crf_confirmpassword').children('.custom_error').html('<?php _e('Password and confirm password do not match.',$textdomain);?>');
				jQuery('#'+formid+' .crf_confirmpassword').children('.custom_error').show();
			}
		}
	}
	
	jQuery('#'+formid+' .crf_email').each(function (index, element) {
		var email = jQuery(this).children('input').val();
		var isemail = regex.test(email);
		if (isemail == false && email != "") {
			jQuery(this).children('.custom_error').html('<?php _e('Please enter a valid e-mail address.',$textdomain);?>');
			jQuery(this).children('.custom_error').show();
		}
	});
	
	jQuery('#'+formid+' .crf_number').each(function (index, element) { //Validation for number type custom field
		var number = jQuery(this).children('input').val();
		var isnumber = jQuery.isNumeric(number);
		if (isnumber == false && number != "") {
			jQuery(this).children('.custom_error').html('<?php _e('Please enter a valid number.',$textdomain);?>');
			jQuery(this).children('.custom_error').show();
		}
	});
	
	jQuery('#'+formid+' .crf_datepicker').each(function (index, element) { //Validation for number type custom field
	
		var date = jQuery(this).children('input').val();
		var pattern = /^([0-9]{4})-([0-9]{2})-([0-9]{2})$/;
    	if (date != "" && !pattern.test(date)) {
       		jQuery(this).children('.custom_error').html('<?php _e('Please enter a valid date (yyyy-mm-dd format)',$textdomain);?>');
	   		jQuery(this).children('.custom_error').show();
    	}
	});
	
	jQuery('#'+formid+' .crf_required').each(function (index, element) { //Validation for number type custom field
		var value = jQuery(this).children('input').val();
		var value2 = jQuery.trim(value);
		if (value == "" || value2== "") {
			jQuery(this).children('.custom_error').html('<?php _e('This is a required field.',$textdomain);?>');
			jQuery(this).children('.custom_error').show();
		}
	});
	
	jQuery('#'+formid+' .crf_select_required').each(function (index, element) { //Validation for number type custom field
		var value = jQuery(this).children('select').val();
		var value2 = jQuery.trim(value);
		if (value == "" || value2== "") {
			jQuery(this).children('.custom_error').html('<?php _e('This is a required field.',$textdomain);?>');
			jQuery(this).children('.custom_error').show();
		}
	});
	
	jQuery('#'+formid+' .crf_textarearequired').each(function (index, element) { //Validation for number type custom field
		var value = jQuery(this).children('textarea').val();
		var value2 = jQuery.trim(value);
		if (value == "" || value2== "") {
			jQuery(this).children('.custom_error').html('<?php _e('This is a required field.',$textdomain);?>');
			jQuery(this).children('.custom_error').show();
		}
	});
	
	jQuery('#'+formid+' .crf_checkboxrequired').each(function (index, element) { //Validation for number type custom field
	var checkboxlenght = jQuery(this).children('input[type="checkbox"]:checked');
	
	var atLeastOneIsChecked = checkboxlenght.length > 0;
	if (atLeastOneIsChecked == true) {
	}else{
			jQuery(this).children('.custom_error').html('<?php _e('This is a required field.',$textdomain);?>');
			jQuery(this).children('.custom_error').show();
		}
	
	});
	
	jQuery('#'+formid+' .crf_termboxrequired').each(function (index, element) { //Validation for number type custom field
	var checkboxlenght = jQuery(this).children('input[type="checkbox"]:checked');
	
	var atLeastOneIsChecked = checkboxlenght.length > 0;
	if (atLeastOneIsChecked == true) {
	}else{
			jQuery(this).children('.custom_error').html('<?php _e('This is a required field.',$textdomain);?>');
			jQuery(this).children('.custom_error').show();
		}
	
	});
	
	jQuery('#'+formid+' .crf_file').each(function (index, element) {
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
	
	jQuery('#'+formid+' .crf_radiorequired').each(function (index, element) { //Validation for number type custom field
	var radiolenght = jQuery(this).children('input[type="radio"]:checked');
	
	var atLeastOneIsChecked = radiolenght.length > 0;
	if (atLeastOneIsChecked == true) {
	}else{
			jQuery(this).children('.custom_error').html('<?php _e('This is a required field.',$textdomain);?>');
			jQuery(this).children('.custom_error').show();
		}
	
	});
	
	var b = '';
	b = jQuery('#'+formid+' .custom_error').each(function () {
		var a = jQuery(this).html();
		b = a + b;
		jQuery('#'+formid+' .customcrferror').html(b);
	});
	var error = jQuery('#'+formid+' .customcrferror').html();
	if (error == '') {
		return true;
	} else {
		return false;
	}
}
//);
jQuery('.input-box').addClass('crf_input');
jQuery('.lable-text').addClass('crf_label');
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
	jQuery(a).parent('.crf_checkbox').children('.otherbx').toggle(500);
}
</script>