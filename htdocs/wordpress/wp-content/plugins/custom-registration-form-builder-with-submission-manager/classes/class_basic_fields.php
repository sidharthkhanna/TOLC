<?php
class crf_basic_fields extends crf_basic_options
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
	
	
	public function crf_get_registration_form_field($id)
	{
	   $this->crf_get_custom_form_field_let_user_decide($id);
	   $this->crf_get_custom_form_field_user_name();
	   $this->crf_get_custom_form_field_user_email();
	   $this->crf_get_custom_form_field_user_password();
	 	
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
	public function crf_field_creation($id,$form_type)
	{
		   global $wpdb;
		   if(isset($_POST['field_front_data'])){
		   	
   	?>		
		   	<script>
		   		var formFrontData = '<?php echo $_POST["field_front_data"] ?>';
		   		var formDataObj = jQuery.parseJSON(formFrontData);
		   		var i;
		   		
		   		jQuery(document).ready(function(){
		   			for(i=0;i < formDataObj.length;i++){
		   				jQuery('#'+formDataObj[i].field).val(formDataObj[i].value);
		   			}
		   		});
		   	</script>
		  <?php }
		   $textdomain = 'custom-registration-form-builder-with-submission-manager';
		   $crf_fields =$wpdb->prefix."crf_fields";
		   
		   if($form_type=='reg_form') $this->crf_get_registration_form_field($id); 
		   
		   $qry1 = "select * from $crf_fields where Form_Id = '".$id."' order by ordering asc";
		   $reg1 = $wpdb->get_results($qry1);
		   foreach($reg1 as $row1)
		   {
				  $key = $this->crf_get_field_key($row1);
				  $value = $row1->Value;
				  $this->crf_get_custom_form_fields($row1,$value);
		   }
		   
		   
		   $enable_mailchimp = $this->crf_get_global_option_value('enable_mailchimp');
		   $form_options = $this->crf_get_form_option_value('form_option',$id);
		   $form_option = maybe_unserialize($form_options);
		   $optin_box = $form_option['optin_box'];
		   if($enable_mailchimp =='yes' && $optin_box==1)
		   {
		   		  $this->crf_get_custom_form_field_optin_box($id);
		   }
		   
		   $enable_captcha = $this->crf_get_global_option_value('enable_captcha');
		   if($enable_captcha=='yes')
		   {
			   	  $publickey = $this->crf_get_global_option_value('public_key');
				  $this->crf_field_captcha($publickey);
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
        <div class="formtable">
        <div class="crf_label">
          <label for="user_login"><?php _e('Username',$textdomain);?><sup class="crf_estric">*</sup>
          </label>
        </div>
        <div class="crf_input crf_required">
          <input type="text" size="20" onblur="javascript:validete_userName(this);" onkeyup="javascript:validete_userName(this);" onfocus="javascript:validete_userName(this);" onchange="javascript:validete_userName(this);" value="<?php echo (!empty($_POST['user_name']))?  $_POST['user_name']: ''; ?>" class="input" id="user_name" name="user_name">
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
        <div class="formtable">
        <div class="crf_label">
          <label for="user_email"><?php _e('E-mail',$textdomain);?><sup class="crf_estric">*</sup>
          </label>
        </div>
        <div class="crf_input crf_required crf_email">
          <input type="text" onblur="javascript:validete_email(this);" onkeyup="javascript:validete_email(this);" onfocus="javascript:validete_email(this);" onchange="" size="25" value="<?php echo (!empty($_POST['user_email']))?  $_POST['user_email']: ''; ?>" class="input" id="user_email" name="user_email">
          <div class="reg_frontErr crf_error_text custom_error" style="display:none;" id="emailErr"></div>
        </div>
      </div>
        <?php
	}
	
	public function crf_get_custom_form_field_let_user_decide($id)
	{
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
        <div class="formtable">
          <div class="crf_label">
            <label for="user_role"><?php echo $user_role_label;?><sup class="crf_estric">*</sup></label>
          </div>
          <div class="crf_input crf_radiorequired">
            <?php 
									
									foreach($user_role_options as $radio)
									{?>
            
            <input type="radio" class="regular-text" value="<?php echo $radio;?>" id="user_role" name="user_role">
            <label><?php echo $radio; ?></label>
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
        <div class="formtable">
        <div class="crf_label">
          <label for="user_password"><?php _e('Password',$textdomain);?><sup class="crf_estric">*</sup>
          </label>
        </div>
        <div class="crf_input crf_required crf_password">
          <input id="inputPassword" name="user_pass" type="password" onfocus="javascript:document.getElementById('user_confirm_password').value = '';" />
          <div id="complexity" class="default" style="display:none;"></div>
          <div id="password_info" class="password-pro"><?php _e('At least 7 characters please!',$textdomain);?></div>
          <div class="reg_frontErr crf_error_text custom_error" style="display:none;"></div>
          
        </div>
      </div>
      <div class="formtable">
        <div class="crf_label">
          <label for="user_confirm_password"><?php _e('Confirm Password',$textdomain);?><sup class="crf_estric">*</sup>
          </label>
        </div>
        <div class="crf_input crf_required crf_confirmpassword">
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
		<div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input <?php if($row1->Require==1)echo 'crf_required';?>">
				<input type="text" class="regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" value="" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'readonly';?> placeholder="<?php echo $value;?>">
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php
		
	}
	
	public function crf_get_custom_form_field_first_name($row1,$value)
	{
		$key = $this->crf_get_field_key($row1);
		?>
		<div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input <?php if($row1->Require==1)echo 'crf_required';?>">
				<input type="text" class="regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" value="" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'readonly';?> placeholder="<?php echo $value;?>">
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php	
	}
	
	public function crf_get_custom_form_field_last_name($row1,$value)
	{
		$key = $this->crf_get_field_key($row1);
		?>
		<div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input <?php if($row1->Require==1)echo 'crf_required';?>">
				<input type="text" class="regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" value="" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'readonly';?> placeholder="<?php echo $value;?>">
				<div class="reg_frontErr custom_error crf_error_text" style="display:none;"></div>
			  </div>
			</div>
		<?php	
	}
	
	public function crf_get_custom_form_field_description($row1,$value)
	{
		$key = $this->crf_get_field_key($row1);
		?>
	   <div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input <?php if($row1->Require==1)echo 'crf_textarearequired';?>">
				<textarea  class="regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" cols="<?php echo $row1->Cols;  ?>" rows="<?php echo $row1->Rows;  ?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'readonly';?> placeholder="<?php echo $value; ?>"></textarea>
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
		global $wpdb;
		 $textdomain = 'custom-registration-form-builder-with-submission-manager';
		 $crf_paypal_fields =$wpdb->prefix."crf_paypal_fields";
		 $qry1 = "select * from $crf_paypal_fields where Id = '".$value."'";
		 $row2 = $wpdb->get_row($qry1);
		 $function = 'crf_get_custom_form_paypal_field_'.$row2->Type;
		 $this->$function($row1,$row2,$row2->Value);	
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
		<div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?></label>
			  </div>
			  <div class="crf_input">
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
		
	}
	
	public function crf_get_custom_form_paypal_field_checkbox($row1,$row2,$value)
	{
	
	}
	
	public function crf_get_custom_form_paypal_field_userdefine($row1,$row2,$value)
	{
	
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
	   <div class="formtable">
       <div class="crf_label"></div>
			  <div class="crf_input <?php if($row1->Require==1)echo 'crf_termboxrequired';?>">
				<input type="checkbox" value="<?php echo 'yes';?>" id="<?php echo $key;?>" name="<?php echo $key;?>"  class="regular-text <?php echo $row1->Class;?>">
	   <label for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1)echo '<sup class="crf_estric">*</sup>';?></label>
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
	   <div class="formtable">
       <div class="crf_label"></div>
			  <div class="crf_input">
				<input type="checkbox" value="<?php echo 'yes';?>" id="crf_optin_box" name="crf_optin_box"  class="regular-text">
	   <label for="crf_optin_box"><?php echo $option_text;?></label>
				
				
			  </div>
			</div>
		<?php
	}
	public function crf_get_custom_form_field_DatePicker($row1,$value)
	{
		$key = $this->crf_get_field_key($row1);
		?>
		<div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf_datepicker <?php if($row1->Require==1)echo 'crf_required';?>">
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
	   <div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf_email <?php if($row1->Require==1)echo 'crf_required';?>">
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
		<div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf_number <?php if($row1->Require==1)echo 'crf_required';?>">
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
	   <div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input <?php if($row1->Require==1)echo 'crf_textarearequired';?>">
				<textarea  class="regular-text <?php echo $row1->Class;?>" maxlength="<?php echo $row1->Max_Length;?>" cols="<?php echo $row1->Cols;  ?>" rows="<?php echo $row1->Rows;  ?>" id="<?php echo $key;?>" name="<?php echo $key;?>" <?php if($row1->Readonly==1)echo 'readonly';?> placeholder="<?php echo $value; ?>"></textarea>
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
			<div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input <?php if($row1->Require==1)echo 'crf_radiorequired';?>">
				<?php 
										$arr_radio = explode(',',$row1->Option_Value);
										foreach($arr_radio as $radio)
										{?>
				
				<input type="radio" class="regular-text  <?php echo $row1->Class;?>" value="<?php echo $radio;?>" <?php if($value!=""){if(in_array($radio,$array_value))echo 'checked';} ?> id="<?php echo $key;?>" name="<?php echo $key;?>"  <?php if($row1->Readonly==1)echo 'disabled';?>>
                <label><?php echo $radio; ?></label>
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
			<div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name; ?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf_checkbox <?php if($row1->Require==1)echo 'crf_checkboxrequired';?>">
				<?php 
				$arr_radio = explode(',',$row1->Option_Value);
				$radio_count = 1;
				foreach($arr_radio as $radio)
				{
					if($radio=='chl_other')
					{
						?>
						<input type="checkbox" class="regular-text <?php echo $row1->Class;?>" value="<?php echo $radio;?>" id="<?php echo $key;?>" onClick="showotherbox(this)">
                        <label><?php echo 'Other'; ?></label>
                        <div class="otherbx" style="display:none;">
				<input type="text" class="regular-text <?php echo $row1->Class;?>" value="" id="<?php echo $key;?>"  name="<?php echo $key.'[]';?>">
                </div>
                 
						<?php
						continue;
					}
					
					?>
				
				<input type="checkbox" class="regular-text <?php echo $row1->Class;?>" value="<?php echo $radio;?>" id="<?php echo $key;?>"  name="<?php echo $key.'[]';?>" <?php if($value!=""){if(in_array($radio,$array_value))echo 'checked';} ?> <?php if($row1->Readonly==1)echo 'disabled';?>>
                <label><?php echo $radio; ?></label>
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
	
	}
	
	public function crf_get_custom_form_field_repeatable_text($row1,$value)
	{
		
	}
	public function crf_get_custom_form_field_select($row1,$value)
	{
		$key = $this->crf_get_field_key($row1);
		?>
	   <div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf_select <?php if($row1->Require==1)echo 'crf_select_required';?>">
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
	   <div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf_select crf_country <?php if($row1->Require==1)echo 'crf_select_required';?>">
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
	   <div class="formtable">
			  <div class="crf_label">
				<label for="<?php echo $key;?>"><?php echo $row1->Name;?><?php if($row1->Require==1):?><sup class="crf_estric">*</sup><?php endif;?></label>
			  </div>
			  <div class="crf_input crf_select crf_country <?php if($row1->Require==1)echo 'crf_select_required';?>">
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