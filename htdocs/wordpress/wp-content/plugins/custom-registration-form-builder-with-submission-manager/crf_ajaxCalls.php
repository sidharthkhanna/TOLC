<?php
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_forms =$wpdb->prefix."crf_forms";
$path =  plugin_dir_url(__FILE__); 
$f_name= $_REQUEST['function'];
     switch($f_name)
     {
        case 'validateUser': echo validateUser($_REQUEST['name']); break;
        case 'validateEmail': echo validateEmail($_REQUEST['email']); 
     }
/*Cross checks if the username already exists during registration process*/
     function validateUser($name)
     {
		 
        $userid =  username_exists( $name );
		if($userid>0 )
		{
			if(is_multisite() && !is_user_member_of_blog($userid))
			{
				return "false";	
			}
			else
			{
				return "true";	
			}
		}
		else
		{
			return "false";	
		}
		
     }
/*Cross checks if the email already exists during registration process*/
     function validateEmail($email)
     {  
        $userid =  email_exists( $email ); 
		if($userid>0 )
		{
			if(is_multisite() && !is_user_member_of_blog($userid))
			{
				return "false";	
			}
			else
			{
				return "true";	
			}
		}
		else
		{
			return "false";	
		}
     }
	 
     die;
?>