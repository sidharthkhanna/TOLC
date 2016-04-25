<?php
class crf_basic_fields_backend extends crf_basic_options
{
	function __construct() {
	
	}
	
	public function crf_field_captcha_error($errors)
	{
		//print_r($errors);
		$textdomain = 'custom-registration-form-builder-with-submission-manager';
		?>
         <!--HTML for showing error when recaptcha does not matches-->
		<div class="crf_captcha_error" align="center">
         <?php  _e( 'Sorry, you didn\'t enter the correct captcha code.', $textdomain ); ?> 
         
         </div>
        <br />
        <br />
        <br />
		<?php	
	}
	
	public function crf_field_captcha($publickey)
	{
		?>
        
         <div class="formtablee" align="center">
      <div class="crf_input crf_input_captcha">
      <div class="g-recaptcha" data-sitekey="<?php echo $publickey; ?>"></div>
      </div>
      </div>
      <style>
	  @media(max-width: 390px) {
    .g-recaptcha {
        margin: 1px;
    }
}
	  
	  </style>
      <script>
	  
	  jQuery(window).load(function() {
    var recaptcha = jQuery(".g-recaptcha");
	
    if(jQuery(window).width() < 391 ) {
        var newScaleFactor = recaptcha.parent().innerWidth() / 304;
        recaptcha.css('transform', 'scale(' + newScaleFactor + ')');
        recaptcha.css('transform-origin', '0 0');
    }
    else {
        recaptcha.css('transform', 'scale(1)');
        recaptcha.css('transform-origin', '0 0');
    }
});
	  
	  jQuery(window).resize(function() {
    var recaptcha = jQuery(".g-recaptcha");
    if(recaptcha.css('margin') == '1px') {
        var newScaleFactor = recaptcha.parent().innerWidth() / 304;
        recaptcha.css('transform', 'scale(' + newScaleFactor + ')');
        recaptcha.css('transform-origin', '0 0');
    }
    else {
        recaptcha.css('transform', 'scale(1)');
        recaptcha.css('transform-origin', '0 0');
    }
});
	  </script>
        
        <?php
			
	}
	
	
	public function crf_get_registration_form_field($crf_userid)
	{
	  global $wpdb;
	  global $wp_roles;
	  $textdomain = 'custom-registration-form-builder-with-submission-manager';
	  $user_info = get_userdata($crf_userid);
	  $userrole = $user_info->roles;
	  $roles = get_editable_roles();
		?>
        <div class="crf-form-setting">
        <div class="crf-form-left-area">
          <label for="user_login" class="crf-label"><?php _e('Username',$textdomain);?><sup class="crf_estric">*</sup>
          </label>
        </div>
        <div class="crf-form-right-area crf_required">
          <input type="text" size="20" value="<?php echo $user_info->user_login; ?>" class="input" id="user_name" name="user_name" disabled>
          <div class="reg_frontErr crf_error_text custom_error" style="display:none;" id="nameErr"></div>
        </div>
      </div>
      
      <div class="crf-form-setting">
        <div class="crf-form-left-area">
          <label for="user_email" class="crf-label"><?php _e('E-mail',$textdomain);?><sup class="crf_estric">*</sup>
          </label>
        </div>
        <div class="crf-form-right-area  crf_input crf_required crf_email">
          <input type="text" class="input" id="user_email" name="user_email" disabled value="<?php echo $user_info->user_email; ?>">
          <div class="reg_frontErr crf_error_text custom_error" style="display:none;" id="emailErr"></div>
        </div>
      </div>
      
      <div class="crf-form-setting">
        <div class="crf-form-left-area">
          <label for="user_password" class="crf-label"><?php _e('Password',$textdomain);?>
          </label>
        </div>
        <div class="crf-form-right-area crf_input crf_password">
          <input id="inputPassword" name="user_pass" type="password" onfocus="javascript:document.getElementById('user_confirm_password').value = '';" />
          <div id="complexity" class="default" style="display:none;"></div>
          <div id="password_info" class="password-pro" style="display:none;"><?php _e('At least 7 characters please!',$textdomain);?></div>
          <div class="reg_frontErr crf_error_text custom_error" style="display:none;"></div>
          
        </div>
      </div>
      <div class="crf-form-setting">
        <div class="crf-form-left-area">
          <label for="user_confirm_password" class="crf-label"><?php _e('Confirm Password',$textdomain);?>
          </label>
        </div>
        <div class="crf-form-right-area crf_input crf_confirmpassword">
          <input id="user_confirm_password" name="user_confirm_password" type="password"/>
          <div class="reg_frontErr crf_error_text custom_error" style="display:none;"></div>
          <!--<div class="reg_frontErr crf_error_text" id="divuser_confirm_password" style="display:none;"><?php _e('Enter the password again to confirm',$textdomain);?></div>-->
        </div>
      </div>
      
      <div class="crf-form-setting">
        <div class="crf-form-left-area">
          <label for="user_role" class="crf-label"><?php _e('Role',$textdomain);?><sup class="crf_estric">*</sup>
          </label>
        </div>
        <div class="crf-form-right-area crf_input crf_userrole">
          <select name="user_role" id="user_role">
          <option value="">None</option>
		<?php
		$currentrole = '';
		foreach($userrole as $crole)
		{
			$currentrole = $crole;
			break;
		}
          foreach($roles as $key=>$role)
          {
             ?>
             <option value="<?php echo $key;?>" <?php selected($currentrole,$key);?>><?php echo $role['name'];?></option>
             <?php
          }
        ?>
        </select>
          <div class="reg_frontErr crf_error_text custom_error" style="display:none;"></div>
          <!--<div class="reg_frontErr crf_error_text" id="divuser_confirm_password" style="display:none;"><?php _e('Enter the password again to confirm',$textdomain);?></div>-->
        </div>
      </div>
      
        <?php
	  
	 	
	}
	
	
	public function crf_check_pricing_field($id)
	{
		   global $wpdb;
		   $textdomain = 'custom-registration-form-builder-with-submission-manager';
		   $crf_fields =$wpdb->prefix."crf_fields";
		   $qry1 = "select count(*) from $crf_fields where Form_Id = '".$id."' and Type = 'pricing' order by ordering asc";
		   $reg1 = $wpdb->get_var($qry1);
		   if($reg1<1)
		   {
				return $reg1;   
		   }
		   else
		   {
				return 1;   
		   }
		   		   
	}
	public function crf_field_creation($formid,$crf_userid)
	{
		   global $wpdb;
		   $textdomain = 'custom-registration-form-builder-with-submission-manager';
		   $crf_fields =$wpdb->prefix."crf_fields";
		   
		  $this->crf_get_registration_form_field($crf_userid); 
		  
		  if(isset($formid))
			{
			$formids = implode(',',$formid);
			}
			else
			{
			$formids = 0;	
			}
//echo $formids;die;
		  
		   
		   $qry1 = "select * from $crf_fields where Form_Id in(".$formids.") and Visibility = '2' order by ordering asc";
		   $reg1 = $wpdb->get_results($qry1);
		   //echo $qry1;die;
			foreach($reg1 as $row1)
			{
				  $key = $this->crf_get_field_key($row1);
				  $value = get_user_meta($crf_userid, $key,true);
				  if($row1->Type=='pricing')
				  {
						$value = $row1->Value;  
				  }
				  $this->crf_get_custom_form_fields($row1,$value);
			}
		   
	}
	
	public function crf_update_backend_user_meta($formid,$userid,$post,$files,$server)
	{
		
			if(isset($formid))
			{
			$formids = implode(',',$formid);
			}
			else
			{
			$formids = 0;	
			}
		
		 global $wpdb;
		   $textdomain = 'custom-registration-form-builder-with-submission-manager';
		   $crf_fields =$wpdb->prefix."crf_fields"; 
		   $qry1 = "select * from $crf_fields where Form_Id in(".$formids.") order by ordering asc";
		   $reg1 = $wpdb->get_results($qry1);
		   if(isset($post['user_role']))
		   {
				wp_update_user( array( 'ID' => $userid, 'role' =>$post['user_role'] ) );;   
		   }
		   if(isset($post['user_pass']) && !empty($post['user_pass']))
		   {
				wp_set_password($post['user_pass'],$userid);   
		   }
		   foreach($reg1 as $row1)
		   {
				if(!empty($row1))
				{
					/*file addon start */
					$Customfield = $this->crf_get_field_key($row1);
					if ($row1->Type=='file') 
					{
						$allowfieldstypes ='';
						if(trim($row1->Option_Value)!="")
						{
							$allowfieldstypes = strtolower(trim($row1->Option_Value));
						}
						else
						{
							$allowfieldstypes =  strtolower(get_option('ucf_allowfiletypes','jpg|jpeg|png|gif|doc|pdf|docx|txt|psd'));	
						}
						//echo $Customfield;die;
						$filefield = $files[$Customfield];
						
						if(is_array($filefield))
						{
									
							for( $i =0; $i<count($filefield['name']); $i++ ) 
							{
								$file = array(
											  'name'     => $filefield['name'][$i],
											  'type'     => $filefield['type'][$i],
											  'tmp_name' => $filefield['tmp_name'][$i],
											  'error'    => $filefield['error'][$i],
											  'size'     => $filefield['size'][$i]
											);
											
								if ($filefield['error'][$i] === 0)
								{
												
									  if ( ! function_exists( 'wp_handle_upload' ) )
									  {
										  require_once( ABSPATH . 'wp-admin/includes/file.php' );
									  }
									  
									  $upload_overrides = array( 'test_form' => false );
									  $movefile = wp_handle_upload( $file, $upload_overrides );
									  
									  if ( $movefile )
									  {
										  // $filename should be the path to a file in the upload directory.
										  $filename = $movefile['file'];
										  // The ID of the post this attachment is for.
										  $parent_post_id = 0;
										  // Check the type of tile. We'll use this as the 'post_mime_type'.
										  $filetype = wp_check_filetype( basename( $filename ), null );
										  $current_file_type = strtolower($filetype['ext']);
										  if(strpos($allowfieldstypes,$current_file_type)===false)
										  {
											  continue;
										  }
										 // print_r($filetype);die;
										 
										  // Get the path to the upload directory.
										  $wp_upload_dir = wp_upload_dir();
										  // Prepare an array of post data for the attachment.
										  $attachment = array(
				  
											  'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ), 
				  
											  'post_mime_type' => $filetype['type'],
				  
											  'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				  
											  'post_content'   => '',
				  
											  'post_status'    => 'inherit'
				  
										  );
										  // Insert the attachment.
										  $attach_id[] = wp_insert_attachment( $attachment, $filename, $parent_post_id );
										  
										  
									  }
								  
								  
							 }
								
									
							}
						}
						if(isset($attach_id)):
						$attach_ids = implode(',',$attach_id);
						update_user_meta( $userid, $Customfield, $attach_ids );
						unset($attach_id);
						endif;
					}
					else
					if(isset($post[$Customfield]))
					{	
						if(is_array($post[$Customfield]))
						{
							$value = sanitize_text_field(rtrim(implode(',',$post[$Customfield]),','));	
						}
						else
						{
							if ($row1->Type=='email')
							{
								$value = sanitize_email($post[$Customfield]);
							}
							else
							{
								$value = sanitize_text_field($post[$Customfield]);	
							}
							
						}
						update_user_meta( $userid, $Customfield, $value );
						//$entry[$Customfield] =  $post[$Customfield];
					}
					/*file addon end */
				}
				
		   }
		   
		   
			 
	}
	
	public function crf_get_custom_form_fields($row,$value)
	{
		$function = 'crf_get_custom_form_field_'.$row->Type;
		$this->$function($row,$value);	
	}
	
	public function crf_get_custom_form_field_user_name()
	{
		$textdomain = 'custom-registration-form-builder-with-submission-manager';
		?>
        <div class="crf-form-setting">
        <div class="crf-form-left-area">
          <label for="user_login" class="crf-label"><?php _e('Username',$textdomain);?><sup class="crf_estric">*</sup>
          </label>
        </div>
        <div class="crf_input crf_required crf-form-right-area">
          <input type="text" size="20" onblur="javascript:validete_userName();" onkeyup="javascript:validete_userName();" onfocus="javascript:validete_userName();" onchange="javascript:validete_userName();" value="<?php echo (!empty($_POST['user_name']))?  $_POST['user_name']: ''; ?>" class="input" id="user_name" name="user_name">
          <div class="reg_frontErr crf_error_text custom_error" style="display:none;" id="nameErr"></div>
        </div>
      </div>
        <?php
	}
	
	public function crf_get_custom_form_field_captcha()
	{
		?>
        <div class="formtablee" align="center"><div class="crf_input crf_input_captcha"> <?php echo recaptcha_get_html($publickey, $error); ?> </div></div>
      <div class="reg_frontErr custom_error crf_error_text" id="divrecaptcha_response_field" style="display:none;"></div>
        <?php
		
	}
	
	public function crf_get_custom_form_field_user_email()
	{
		$textdomain = 'custom-registration-form-builder-with-submission-manager';
		?>
        <div class="formtable crf-form-setting">
        <div class="crf_label crf-form-left-area">
          <label class="crf-label" for="user_email"><?php _e('E-mail',$textdomain);?><sup class="crf_estric">*</sup>
          </label>
        </div>
        <div class="crf_input crf-form-right-area crf_required crf_email">
          <input type="text" onblur="javascript:validete_email();" onkeyup="javascript:validete_email();" onfocus="javascript:validete_email();" onchange="" size="25" value="<?php echo (!empty($_POST['user_email']))?  $_POST['user_email']: ''; ?>" class="input" id="user_email" name="user_email">
          <div class="reg_frontErr crf_error_text custom_error" style="display:none;" id="emailErr"></div>
        </div>
      </div>
        <?php
	}
	
	public function crf_get_custom_form_field_let_user_decide()
	{
		global $wp_roles;
		$form_options = $this->crf_get_form_option_value('form_option',$id);
		$form_option = maybe_unserialize($form_options);
	   if(isset($form_option['user_role_label']))
	   $user_role_label = $form_option['user_role_label'];
	   if(isset($form_option['user_role_options']))
	   $user_role_options = $form_option['user_role_options'];
	   if(isset($form_option['let_user_decide']))
	   $let_user_decide = $form_option['let_user_decide'];
	   if(isset($let_user_decide) && $let_user_decide==1 && isset($user_role_options)):
		?>
        <div class="formtable crf-form-setting">
          <div class="crf_label crf-form-left-area">
            <label class="crf-label" for="user_role"><?php echo $user_role_label;?><sup class="crf_estric">*</sup></label>
          </div>
          <div class="crf_input crf-form-right-area crf_radiorequired">
            <?php 
									
									foreach($user_role_options as $radio)
									{
										$role_label = isset($wp_roles->role_names[$radio]) ? translate_user_role($wp_roles->role_names[$radio] ) : false;
										?>
            <label>
            <input type="radio" class="regular-text" value="<?php echo $radio;?>" id="user_role redirect_option_page" name="user_role">
            <span><?php echo $role_label; ?></span></label>

            <?php } ?>
            <div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
          </div>
        </div>
        <?php	
		endif;
	}
	
	public function crf_get_custom_form_field_analytics($key)
	{
		?>
        <input type="hidden" value="<?php echo time();?>" name="crf_timestamp" />
  		<input type="hidden" value="<?php echo $key;?>" name="crf_key" />
        <?php
	}
	
	public function crf_get_custom_form_field_user_password()
	{
		$textdomain = 'custom-registration-form-builder-with-submission-manager';
		$pwd_show = $this->crf_get_global_option_value('autogeneratedepass');
		if($pwd_show=='yes'):
		$random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
		?>
          <input id="inputPassword" name="user_pass" type="hidden" value="<?php echo $random_password; ?>" />             
          <input id="user_confirm_password" name="user_confirm_password" value="<?php echo $random_password; ?>" type="hidden"/>
          <div id="complexity" class="default" style="display:none !important; visibility:hidden; height:0px;"></div>
          <div id="password_info" class="password-pro" style="display:none !important;"><?php _e('At least 7 characters please!',$textdomain);?></div>
		<?php
		else:
		?>
        <div class="formtable crf-form-setting">
        <div class="crf_label crf-form-left-area">
          <label class="crf-label" for="user_password"><?php _e('Password',$textdomain);?><sup class="crf_estric">*</sup>
          </label>
        </div>
        <div class="crf_input crf-form-right-area crf_required crf_password">
          <input id="inputPassword" name="user_pass" type="password" onfocus="javascript:document.getElementById('user_confirm_password').value = '';" />
          <div id="complexity" class="default" style="display:none;"></div>
          <div id="password_info" class="password-pro"><?php _e('At least 7 characters please!',$textdomain);?></div>
          <div class="reg_frontErr crf_error_text custom_error" style="display:none;"></div>
          
        </div>
      </div>
      <div class="formtable crf-form-setting">
        <div class="crf_label crf-form-left-area">
          <label class="crf-label" for="user_confirm_password"><?php _e('Confirm Password',$textdomain);?><sup class="crf_estric">*</sup>
          </label>
        </div>
        <div class="crf_input crf-form-right-area crf_required crf_confirmpassword">
          <input id="user_confirm_password" name="user_confirm_password" type="password"/>
          <div class="reg_frontErr crf_error_text custom_error" style="display:none;"></div>
          <!--<div class="reg_frontErr crf_error_text" id="divuser_confirm_password" style="display:none;"><?php _e('Enter the password again to confirm',$textdomain);?></div>-->
        </div>
      </div>
        <?php
		endif;
	}
	public function crf_get_custom_form_field_text($row1,$value)
	{
		$key = $this->crf_get_field_key($row1);
		?>
		<div class="formtable crf-form-setting">
			  <div class="crf_label crf-form-left-area">
				<label class="crf-label" for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf-form-right-area <?php if($row1->Require==1)echo 'crf_required';?>">
				<input type="text" class="regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" value="<?php echo $value;?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'readonly';?>>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
		
	}
	
	public function crf_get_custom_form_field_first_name($row1,$value)
	{
		$key = $this->crf_get_field_key($row1);
		?>
		<div class="formtable crf-form-setting">
			  <div class="crf_label crf-form-left-area">
				<label class="crf-label" for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf-form-right-area <?php if($row1->Require==1)echo 'crf_required';?>">
				<input type="text" class="regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" value="<?php echo $value;?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'readonly';?> placeholder="">
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php	
	}
	
	public function crf_get_custom_form_field_last_name($row1,$value)
	{
		$key = $this->crf_get_field_key($row1);
		?>
		<div class="formtable crf-form-setting">
			  <div class="crf_label crf-form-left-area">
				<label class="crf-label" for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf-form-right-area <?php if($row1->Require==1)echo 'crf_required';?>">
				<input type="text" class="regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" value="<?php echo $value;?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'readonly';?> placeholder="">
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php	
	}
	
	public function crf_get_custom_form_field_description($row1,$value)
	{
		$key = $this->crf_get_field_key($row1);
		?>
	   <div class="formtable crf-form-setting">
			  <div class="crf_label crf-form-left-area">
				<label class="crf-label" for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf-form-right-area <?php if($row1->Require==1)echo 'crf_textarearequired';?>">
				<textarea  class="regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" cols="<?php echo $row1->Cols;  ?>" rows="<?php echo $row1->Rows;  ?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'readonly';?> placeholder=""><?php echo $value; ?></textarea>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
	}
	
	public function crf_get_custom_form_field_heading($row1,$value)
	{
		$key = $this->crf_get_field_key($row1);
		?>
		<div class="formtable crf_heading">
			  <h1 name="<?php echo $key;?>" class="<?php echo $row1->Class;?>"><?php echo $row1->Value;?></h1>
			</div>
		<?php
	}
	
	public function crf_get_custom_form_field_pricing($row1,$value)
	{
		/*global $wpdb;
		 $textdomain = 'custom-registration-form-builder-with-submission-manager';
		 $crf_paypal_fields =$wpdb->prefix."crf_paypal_fields";
		 $qry1 = "select * from $crf_paypal_fields where Id = '".$value."'";
		 $row2 = $wpdb->get_row($qry1);
		 $function = 'crf_get_custom_form_paypal_field_'.$row2->Type;
		 $this->$function($row1,$row2,$row2->Value);	*/
	}
	
	public function crf_get_custom_form_paypal_field_single($row1,$row2,$value)
	{
		$key = $this->crf_get_field_key($row1);
		$extra_option =  maybe_unserialize($row2->extra_options);
		if(isset($extra_option['field_visible']))
		{
			$visibility = $extra_option['field_visible'];
		}
		else
		{
			$visibility = 0;
		}
		
		if($visibility==1):
		?>
		<div class="formtable crf-form-setting">
			  <div class="crf_label crf-form-left-area">
				<label class="crf-label" for="<?php echo $key;?>"><?php echo $row1->Name;?></label>
			  </div>
			  <div class="crf_input crf-form-right-area">
              <p><?php if(get_option('crf_currency_position','before')=='before'):?><?php echo $this->crf_get_currency_symbol();?><?php endif;?><?php echo $value;?><?php if(get_option('crf_currency_position','before')=='after'):?><?php echo $this->crf_get_currency_symbol();?><?php endif;?></p>
				
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
		endif;
		?>
        <input type="hidden" class="regular-text <?php echo $row1->Class;?>" value="<?php echo $value;?>" id="<?php echo $key;?>" name="<?php echo $key;?>">
        <?php
		
	}
	
	public function crf_get_custom_form_paypal_field_dropdown($row1,$row2,$value)
	{
		$key = $this->crf_get_field_key($row1);
		?>
	   <div class="formtable crf-form-setting">
			  <div class="crf_label crf-form-left-area">
				<label class="crf-label" for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf-form-right-area crf_select <?php if($row1->Require==1)echo 'crf_select_required';?>">
				<select class="regular-text <?php echo $row1->Class;?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'disabled';?>>
				  <?php
				$arr_radio_value = explode(',',$row2->Option_Value);
				$arr_radio_options = explode(',',$row2->Option_Label);
				$arr_radio_price = explode(',',$row2->Option_Price);
				$radio_count = 0;
				  foreach($arr_radio_options as $ar)
				  {
					  ?>
				  <option value="<?php echo $arr_radio_value[$radio_count];?>"><?php echo $ar;?> (<span class="price"><?php if(get_option('crf_currency_position','before')=='before'):?><?php echo $this->crf_get_currency_symbol();?><?php endif;?><?php echo $arr_radio_price[$radio_count]; ?><?php if(get_option('crf_currency_position','before')=='after'):?><?php echo $this->crf_get_currency_symbol();?><?php endif;?></span>)</option>
				  <?php
				  $radio_count++;	
				  }
				  ?>
				</select>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
	}
	
	public function crf_get_custom_form_paypal_field_checkbox($row1,$row2,$value)
	{
		$key = $this->crf_get_field_key($row1);
		$array_value = explode(',',$value);
			   ?>
			<div class="formtable crf-form-setting">
			  <div class="crf_label crf-form-left-area">
				<label class="crf-label" for="<?php echo $key;?>"><?php echo $row1->Name; ?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_checkbox <?php if($row1->Require==1)echo 'crf_checkboxrequired';?>">
				<?php 
				$arr_radio_value = explode(',',$row2->Option_Value);
				$arr_radio_options = explode(',',$row2->Option_Label);
				$arr_radio_price = explode(',',$row2->Option_Price);
				$radio_count = 0;
				foreach($arr_radio_options as $radio)
				{
					?>
				<input type="checkbox" class="regular-text <?php echo $row1->Class;?>" value="<?php echo $arr_radio_value[$radio_count];?>" id="<?php echo $key;?>"  name="<?php echo $key.'[]';?>">
                <label><?php echo $radio; ?> (<span class="price"><?php if(get_option('crf_currency_position','before')=='before'):?><?php echo $this->crf_get_currency_symbol();?><?php endif;?><?php echo $arr_radio_price[$radio_count]; ?><?php if(get_option('crf_currency_position','before')=='after'):?><?php echo $this->crf_get_currency_symbol();?><?php endif;?></span>)</label>
				<?php $radio_count++; 
				} 
					?>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
	   
		<?php
	
	}
	
	public function crf_get_custom_form_paypal_field_userdefine($row1,$row2,$value)
	{
		
		$key = $this->crf_get_field_key($row1);
		?>
	   <div class="formtable crf-form-setting">
			  <div class="crf_label crf-form-left-area">
				<label class="crf-label" for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf-form-right-area crf_number crf_price <?php if($row1->Require==1)echo 'crf_required';?>">
              <?php if(get_option('crf_currency_position','before')=='before'):?>
              <span style="float:left;"><?php echo $this->crf_get_currency_symbol();?></span>
              <?php endif;?>
              
				<input type="text" class="regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" value="" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'readonly';?>>
                
                
                 <?php if(get_option('crf_currency_position','before')=='after'):?>
              <span style="float:right;"><?php echo $this->crf_get_currency_symbol();?></span>
              <?php endif;?>
                
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
		
	}
	public function crf_get_currency_symbol()
	{
		$currency = get_option('crf_currency','USD');
		switch ($currency)
		{
			case 'USD':
				$sign = '&#36;';
				break;
			case 'EUR':
				$sign = '&#0128;';
				break;
			case 'GBP':
				$sign = '&#163;';
				break;
			case 'AUD':
				$sign = '&#36;';
				break;
			case 'BRL':
				$sign = 'R&#36;';
				break;
			case 'CAD':
				$sign = '&#36;';
				break;
			case 'HKD':
				$sign = '&#36;';
				break;
			case 'ILS':
				$sign = '&#8362;';
				break;
			case 'JPY':
				$sign = '&#165;';
				break;
			case 'MXN':
				$sign = '&#36;';
				break;
			case 'NZD':
				$sign = '&#36;';
				break;
			case 'SGD':
				$sign = '&#36;';
				break;
			case 'THB':
				$sign = '&#3647;';
				break;
			case 'INR':
				$sign = '&#8377;';
				break;
			case 'TRY':
				$sign = '&#8378;';
				break;
			default:
				$sign = $currency;
		}
		return $sign;
		
	}
	public function crf_get_custom_form_field_paragraph($row1,$value)
	{
		$key = $this->crf_get_field_key($row1);
		?>
	   <div class="formtable crf_paragraph">
	
			  <p name="<?php echo $key;?>" class="<?php echo $row1->Class;?>"><?php echo $row1->Option_Value;?></p>
			</div>
		<?php
	}
	public function crf_get_custom_form_field_term_checkbox($row1,$value)
	{
		$key = $this->crf_get_field_key($row1);
		?>
	   <div class="formtable crf-form-setting">
       <div class="crf_label crf-form-left-area">.</div>
			  <div class="crf_input crf-form-right-area <?php if($row1->Require==1)echo 'crf_termboxrequired';?>">
              <label class="termsandcondition" >
				<input type="checkbox" value="<?php echo 'yes';?>" id="<?php echo $key;?>" name="<?php echo $key;?>"  class="regular-text <?php echo $row1->Class;?>">
	   <span class="crf-label" for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1)echo '<sup class="crf_estric">*</sup>';?></span></label>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
				<textarea disabled rows="4" class="textareaa"><?php echo $row1->Description;?></textarea>
				
			  </div>
			</div>
		<?php
	}
	
	public function crf_get_custom_form_field_optin_box($id)
	{
		$form_options = $this->crf_get_form_option_value('form_option',$id);
		$form_option = maybe_unserialize($form_options);
		$option_text = $form_option['optin_box_text'];
		?>
	   <div class="formtable crf-form-setting">
       <div class="crf_label crf-form-left-area"></div>
			  <div class="crf_input crf-form-right-area">
				<input type="checkbox" value="<?php echo 'yes';?>" id="crf_optin_box" name="crf_optin_box"  class="regular-text">
	   <label class="crf-label" for="crf_optin_box"><?php echo $option_text;?></label>
				
				
			  </div>
			</div>
		<?php
	}
	public function crf_get_custom_form_field_DatePicker($row1,$value)
	{
		$key = $this->crf_get_field_key($row1);
		?>
		<div class="crf-form-setting">
			  <div class="crf-form-left-area">
				<label class="crf-label" for="<?php echo $key;?>"> <?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label> 
			  </div> 
			  <div class="crf_input crf-form-right-area crf_datepicker <?php if($row1->Require==1)echo 'crf_required';?>">
				<input type="text" class="rm_tcal regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" value="<?php echo $value;?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'readonly';?>>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
	}
	public function crf_get_custom_form_field_email($row1,$value)
	{
		$key = $this->crf_get_field_key($row1);
		?>
	   <div class="formtable crf-form-setting">
			  <div class="crf_label crf-form-left-area">
				<label class="crf-label" for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf-form-right-area crf_email <?php if($row1->Require==1)echo 'crf_required';?>">
				<input type="text" class="regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" value="<?php echo $value;?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'readonly';?>>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
	}
	public function crf_get_custom_form_field_number($row1,$value)
	{
		$key = $this->crf_get_field_key($row1);
		?>
		<div class="formtable crf-form-setting">
			  <div class="crf_label crf-form-left-area">
				<label class="crf-label" for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf-form-right-area crf_number <?php if($row1->Require==1)echo 'crf_required';?>">
				<input type="text" class="crf_number regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" value="<?php echo $value;?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'readonly';?>>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
	}
	public function crf_get_custom_form_field_textarea($row1,$value)
	{
		$key = $this->crf_get_field_key($row1);
		?>
	   <div class="formtable crf-form-setting">
			  <div class="crf_label crf-form-left-area">
				<label class="crf-label" for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf-form-right-area <?php if($row1->Require==1)echo 'crf_textarearequired';?>">
				<textarea  class="regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" cols="<?php echo $row1->Cols;  ?>" rows="<?php echo $row1->Rows;  ?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'readonly';?> placeholder=""><?php echo $value; ?></textarea>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
	}
	public function crf_get_custom_form_field_radio($row1,$value)
	{
		$key = $this->crf_get_field_key($row1);
		$array_value = explode(',',$value);
				?>
			<div class="formtable crf-form-setting">
			  <div class="crf_label crf-form-left-area">
				<label class="crf-label" for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf-form-right-area <?php if($row1->Require==1)echo 'crf_radiorequired';?>">
				<?php 
										$arr_radio = explode(',',$row1->Option_Value);
										foreach($arr_radio as $radio)
										{?>
				 <label>
				<input type="radio" class="regular-text  <?php echo $row1->Class;?>" value="<?php echo $radio;?>" <?php if($value!=""){if(in_array($radio,$array_value))echo 'checked';} ?> id="<?php echo $key;?>" name="<?php echo $key;?>"  <?php if($row1->Readonly==1)echo 'disabled';?>>
               <span><?php echo $radio; ?></span></label>
				<?php 
				if(!empty($row1->Class) && strpos($row1->Class,'rm_row')!==false){echo '<br/>';}
				
				} ?>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
	}
	public function crf_get_custom_form_field_checkbox($row1,$value)
	{
		$key = $this->crf_get_field_key($row1);
		/*if($value == $row1->Value)
		{
			$arr_value = $value;	
		}*/
		$array_value = explode(',',$value);
		
			   ?>
			<div class="formtable crf-form-setting">
			  <div class="crf_label crf-form-left-area">
				<label class="crf-label" for="<?php echo $key;?>"><?php echo $row1->Name; ?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf-form-right-area crf_checkbox <?php if($row1->Require==1)echo 'crf_checkboxrequired';?>">
				<?php 
				$arr_radio = explode(',',$row1->Option_Value);
				$chl_other = array_diff($array_value,$arr_radio);
				$radio_count = 1;
				foreach($arr_radio as $radio)
				{
					if($radio=='chl_other')
					{
						if(!empty($chl_other))
						{
							foreach($chl_other as $other)
							{
								?><label>
                                <input type="checkbox" class="regular-text <?php echo $row1->Class;?>" value="<?php echo $radio;?>" id="<?php echo $key;?>" checked onClick="showotherbox(this)">
                        <span><?php echo 'Other'; ?></span></label>
                        <div class="otherbx">
				<input type="text" class="regular-text <?php echo $row1->Class;?>" value="<?php echo $other;?>" id="<?php echo $key;?>"  name="<?php echo $key.'[]';?>">
                </div>
                                <?php	
							}
							
						}else
						{
						?>
                        <label>
						<input type="checkbox" class="regular-text <?php echo $row1->Class;?>" value="<?php echo $radio;?>" id="<?php echo $key;?>" onClick="showotherbox(this)">
                        <span><?php echo 'Other'; ?></span></label>
                        <div class="otherbx" style="display:none;">
				<input type="text" class="regular-text <?php echo $row1->Class;?>" value="" id="<?php echo $key;?>"  name="<?php echo $key.'[]';?>">
                </div>
                 
						<?php
						}
						continue;
					}
					
					
					?>
				<label>
				<input type="checkbox" class="regular-text <?php echo $row1->Class;?>" value="<?php echo $radio;?>" id="<?php echo $key;?>"  name="<?php echo $key.'[]';?>" <?php if($value!=""){if(in_array($radio,$array_value))echo 'checked';} ?> <?php if($row1->Readonly==1)echo 'disabled';?>>
                <span><?php echo $radio; ?></span></label>
				<?php 
				if(!empty($row1->Class) && strpos($row1->Class,'rm_row')!==false){echo '<br/>';}
				
				$radio_count++; 
				} ?>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
	   
		<?php
	}
	public function crf_get_custom_form_field_file($row1,$value)
	{
		$textdomain = 'custom-registration-form-builder-with-submission-manager';
		$key = $this->crf_get_field_key($row1);
		?>
	   <div class="formtable filewrapper crf-form-setting">
				<div class="crf_label crf-form-left-area">
				  <label class="crf-label" for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
				</div>
				<div class="crf_input crf-form-right-area crf_contact_attachment">
				  <div class="fileinput crf_file <?php if($row1->Require==1)echo 'crf_required';?>">
				  <input type="file" class="regular-text <?php echo $row1->Class;?>" name="<?php echo $key.'[]';?>" data-filter-placeholder="<?php echo trim($row1->Option_Value); ?>" />
				  <?php if(get_option('ucf_repeatfilefields')=='yes'): ?>
				  <a><span class="add" onClick="addextrafile(this)"><?php _e('Add', $textdomain ); ?></span></a><a class="removebutton"><span class="remove" onClick="removeextrafile(this)"><?php _e('Remove', $textdomain ); ?></span></a>
				  <?php endif; ?>
				  <div class="reg_frontErr crf_error_text custom_error" style="display:none;"></div>
				  </div>
				</div>
			  </div>
		<?php
	}
	
	public function crf_get_custom_form_field_repeatable_text($row1,$value)
	{
		$textdomain = 'custom-registration-form-builder-with-submission-manager';
		$key = $this->crf_get_field_key($row1);
		$values = explode(',',$value);
		?>
	   <div class="formtable textwrapper crf-form-setting">
				<div class="crf_label crf-form-left-area">
				  <label class="crf-label" for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
				</div>
				<div class="crf_input crf-form-right-area crf_contact_textfield">
                <?php foreach($values as $val): ?>
				  <div class="fileinput crf_input <?php if($row1->Require==1)echo 'crf_required';?>">
				  <input type="text" class="regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" value="<?php echo $val;?>" name="<?php echo $key.'[]';?>" <?php if($row1->Readonly==1)echo 'readonly';?> placeholder="">
			
				  <a><span class="add" onClick="addextratext(this)"><?php _e('Add', $textdomain ); ?></span></a><a class="removebutton"><span class="remove" onClick="removeextratext(this)"><?php _e('Remove', $textdomain ); ?></span></a>
			
				  <div class="reg_frontErr crf_error_text custom_error" style="display:none;"></div>
				  </div>
                  <?php endforeach;?>
                  
				</div>
			  </div>
		<?php
	}
	public function crf_get_custom_form_field_select($row1,$value)
	{
		$key = $this->crf_get_field_key($row1);
		?>
	   <div class="formtable crf-form-setting">
			  <div class="crf_label crf-form-left-area">
				<label class="crf-label" for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf-form-right-area crf_select <?php if($row1->Require==1)echo 'crf_select_required';?>">
				<select class="regular-text <?php echo $row1->Class;?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'disabled';?>>
				  <?php
				  $arr = explode(',',$row1->Option_Value);
				  foreach($arr as $ar)
				  {
					  ?>
				  <option value="<?php echo $ar;?>" <?php if($ar==$value)echo 'selected';?>><?php echo $ar;?></option>
				  <?php	
				  }
				  ?>
				</select>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
	}
	public function crf_get_custom_form_field_country($row1,$value)
	{
		
		$key = $this->crf_get_field_key($row1);
		?>
	   <div class="formtable crf-form-setting">
			  <div class="crf_label crf-form-left-area">
				<label class="crf-label" for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf-form-right-area crf_select crf_country <?php if($row1->Require==1)echo 'crf_select_required';?>">
				<select class="regular-text <?php echo $row1->Class;?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'disabled';?>>
				<?php include 'country_option_list.php'; ?>
				</select>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
	}
	public function crf_get_custom_form_field_timezone($row1,$value)
	{
		
		$key = $this->crf_get_field_key($row1);
		?>
	   <div class="formtable crf-form-setting">
			  <div class="crf_label crf-form-left-area">
				<label class="crf-label" for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf_select crf-form-right-area crf_country <?php if($row1->Require==1)echo 'crf_select_required';?>">
				<select class="regular-text <?php echo $row1->Class;?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'disabled';?>>
				<?php include 'time_zone_option_list.php'; ?>
				</select>
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
	}
	
}
?>