<?php
/*Controls registration form behavior on the front end*/
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
include_once('crf_functions.php');
$form_fields = new crf_basic_fields;
$crf_theme = $form_fields->crf_get_global_option_value('crf_theme');
wp_enqueue_style( 'crf-style-default', plugin_dir_url(__FILE__) . 'css/crf-style-'.$crf_theme.'.css');
$enable_captcha = $form_fields->crf_get_global_option_value('enable_captcha');
$publickey = $form_fields->crf_get_global_option_value('public_key');
$privatekey = $form_fields->crf_get_global_option_value('private_key');
if($enable_captcha=='yes' &&  get_option('ucf_enable_captcha_login','no')=='yes')
{
	if(isset($_POST['g-recaptcha-response']))
	{
		require_once('autoload.php');
		$recaptcha = new \ReCaptcha\ReCaptcha($privatekey,new \ReCaptcha\RequestMethod\CurlPost());
		$resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
		if ($resp->isSuccess()) 
		{
			$submit = 1;
		} 
		else 
		{
			$errors = $resp->getErrorCodes();
			$form_fields->crf_field_captcha_error($errors);
			$submit = 0;
		}
	}
	
}
else
{
	$submit=1;
}
if (isset($_POST['submit']) && $submit==1) {
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'view_crf_login_form' ) ) die( 'Failed security check' );
	
    crf_user_authentication($_POST['user_login'], $_POST['user_pass'],$_POST['rememberme']);
}
function crf_user_authentication( $username, $password ,$remember ) 
{
	global $user;
	$creds = array();
	$creds['user_login'] = $username;
	$creds['user_password'] =  $password;
	if($remember==1)$creds['remember'] = true;else $creds['remember'] = false;
	$user = wp_signon( $creds, false );
	if ( is_wp_error($user) ) {
		echo '<div id="crf_login_error">';
	echo $user->get_error_message();
	echo '</div>';
	}
	if ( !is_wp_error($user) ) {
		$redirect = get_option('ucf_redirect_after_login');
		if($redirect==0)
		{
			wp_redirect(home_url('wp-admin'));
		}
		else
		{
			
			wp_redirect(get_permalink($redirect));
		}
	}
}
?>
<div id="crf-form">
  <form enctype="multipart/form-data" method="post" action="" id="crf_contact_form" name="crf_contact_form">
  <?php wp_nonce_field('view_crf_login_form'); ?>
   <div class="crf_contact_form">
      <div class="formtable">
        <div class="crf_label">
          <label for="user_login"><?php _e('Username',$textdomain);?>
          </label>
        </div>
        <div class="crf_input crf_required">
          <input name="user_login" class="input" id="user_login" type="text" size="20" required>
          <div class="reg_frontErr crf_error_text custom_error" style="display:none;" id="nameErr"></div>
        </div>
      </div>
      
    <div class="formtable">
        <div class="crf_label">
          <label for="user_password"><?php _e('Password',$textdomain);?>
          </label>
        </div>
        <div class="crf_input crf_required crf_password">
          <input id="inputPassword" name="user_pass" type="password" required/>
          <div class="reg_frontErr crf_error_text custom_error" style="display:none;"></div>
          
        </div>
      </div>  
      
    
      
      <div class="formtable">
       <div class="crf_label"></div>
			  <div class="crf_input">
				<input name="rememberme" id="rememberme" type="checkbox" value="1">
	   			<label for="rememberme"><?php _e('Remember Me',$textdomain);?></label>
			  </div>
			</div>
      
      <?php
			
		   if($enable_captcha=='yes' &&  get_option('ucf_enable_captcha_login','no')=='yes')
		   {
			   	  $publickey = $form_fields->crf_get_global_option_value('public_key');
				  $form_fields->crf_field_captcha($publickey);
		   }
	  ?>
      
      
    </div>
    
    <div class="customcrferror crf_error_text" style="display:none"></div>
    
    <div class="UltimatePB-Button-area crf_input crf_input_submit form-submit" >
    <input type="hidden" value="<?php echo $form_type;?>" name="form_type" id="form_type" class="crf_form_type"/>
      <input type="submit" value="<?php _e('Login',$textdomain);?>" class="crf_contact_submit primary" id="submit" name="submit">
    </div>
    <?php
		crf_integrate_facebook_login();
	?>
    
  </form>
</div>