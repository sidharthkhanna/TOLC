<?php
/*Controls custom field creation in the dashboard area*/
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_forms =$wpdb->prefix."crf_forms";
$path =  plugin_dir_url(__FILE__); 
require_once('crf_functions.php');
$crf_basic_options = new crf_basic_options;
if(isset($_POST['submit_form']) && trim($_POST['form_name'])!="")
{
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'save_crf_add_form' ) ) die( 'Failed security check' );
	$formoptions = array();
	$formoptions['submit_button_label'] = $_POST['submit_button_label'];
	$formoptions['submit_button_color'] = $_POST['submit_button_color'];
	$formoptions['submit_button_bgcolor'] = $_POST['submit_button_bgcolor'];
	$formoptions['mailchimp_list'] = $_POST['mailchimp_list'];
	$formoptions['auto_expires'] = $_POST['auto_expires'];
	$formoptions['expiry_type'] = $_POST['expiry_type'];
	$formoptions['submission_limit'] = $_POST['submission_limit'];
	$formoptions['expiry_date'] = $_POST['expiry_date'];
	$formoptions['expiry_message'] = $_POST['expiry_message'];
	$formoptions['mailchimp_emailfield'] = $_POST['mailchimp_emailfield'];
	$formoptions['mailchimp_firstfield'] = $_POST['mailchimp_firstfield'];
	$formoptions['mailchimp_lastfield'] = $_POST['mailchimp_lastfield'];
	$formoptions['optin_box'] = $_POST['optin_box'];
	$formoptions['optin_box_text'] = $_POST['optin_box_text'];
	
	if(isset($_POST['form_type']))
	{
		$formtype = $_POST['form_type'];	
	}
	else
	{
		$formtype = 'contact_form';
	}
	
	$options = maybe_serialize($formoptions);
	
	if(isset($_POST['form_id']) && $_POST['form_id']==0)
	{
	$qry = "insert into $crf_forms values('','".$_POST['form_name']."','".$_POST['form_des']."','".$formtype."','".$_POST['form_custom_text']."','".$_POST['welcome_email_subject']."','".$_POST['success_message']."','".$_POST['welcome_email_message']."','".$_POST['redirect_option']."','".$_POST['page_id']."','".$_POST['redirect_url']."','".$_POST['send_email']."','".$options."')";	
	}
	else
	{
	$qry = "update $crf_forms set form_name = '".$_POST['form_name']."',form_desc='".$_POST['form_des']."',form_type='".$formtype."',custom_text='".$_POST['form_custom_text']."',crf_welcome_email_subject='".$_POST['welcome_email_subject']."',success_message='".$_POST['success_message']."',crf_welcome_email_message='".$_POST['welcome_email_message']."',redirect_option='".$_POST['redirect_option']."',redirect_page_id='".$_POST['page_id']."',redirect_url_url='".$_POST['redirect_url']."',send_email='".$_POST['send_email']."',form_option='".$options."' where id='".$_POST['form_id']."'";	
	}
	$wpdb->query($qry);
	wp_redirect('admin.php?page=crf_manage_forms');exit;
}
if(isset($_REQUEST['id']))
{
	$qry = "select * from $crf_forms where id =".$_REQUEST['id'];
	$row = $wpdb->get_row($qry);
	$form_options = maybe_unserialize($row->form_option);
	if(isset($form_options['submit_button_label']))
	$submit_button_label = $form_options['submit_button_label'];
	if(isset($form_options['submit_button_color']))
	$submit_button_color = $form_options['submit_button_color'];
	if(isset($form_options['showtoken']))
	$showtoken = $form_options['showtoken'];
	
	if(isset($form_options['submit_button_bgcolor']))
	$submit_button_bgcolor = $form_options['submit_button_bgcolor'];
	
	$auto_expires = $form_options['auto_expires'];
	$expiry_type = $form_options['expiry_type'];
	$submission_limit = $form_options['submission_limit'];
	$expiry_date = $form_options['expiry_date'];
	$expiry_message = $form_options['expiry_message'];
	if(isset($form_options['mailchimp_list']))
	$mailchimp_list_id = $form_options['mailchimp_list'];
	if(isset($form_options['user_role']))
	$user_role = $form_options['user_role'];
	if(isset($form_options['let_user_decide']))
	$let_user_decide = $form_options['let_user_decide'];
	if(isset($form_options['user_role_label']))
	$user_role_label = $form_options['user_role_label'];
	if(isset($form_options['user_role_options']))
	$user_role_options = $form_options['user_role_options'];
	if(isset($form_options['mailchimp_emailfield']))
	$mailchimp_emailfield = $form_options['mailchimp_emailfield'];
	if(isset($form_options['mailchimp_firstfield']))
	$mailchimp_firstfield = $form_options['mailchimp_firstfield'];
	if(isset($form_options['mailchimp_lastfield']))
	$mailchimp_lastfield = $form_options['mailchimp_lastfield'];
	if(isset($form_options['optin_box']))
	$optin_box = $form_options['optin_box'];
	if(isset($form_options['optin_box_text']))
	$optin_box_text = $form_options['optin_box_text'];
	
	
}
else
{
	$_REQUEST['id']=0;
}
$countfields = $crf_basic_options->crf_count_fields($_REQUEST['id']);
if($countfields==0) $crf_basic_options->crf_null_field_notice();
//echo $countfields;
?>
<div class="crf-main-form">
  <div class="crf-form-heading">
    <?php if(isset($_REQUEST['id']) && $_REQUEST['id']==0): ?>
    <h1><?php _e( 'New Form', $textdomain ); ?></h1>
    <?php else: ?>
    <h1><?php _e( 'Edit Form', $textdomain ); ?></h1>
    <?php endif; ?>
  </div>
  <form name="crf_form" id="crf_form" method="post">
  
   <div class="crf-form-setting" >
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Title:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area crf_required">
        <input type="text" name="form_name" id="form_name" value="<?php if(!empty($row)) echo esc_attr($row->form_name); ?>"  onKeyUp="check('<?php if(!empty($row)){echo esc_attr($row->form_name);}else { echo 'new';} ?>')"/>
        <div id="user-result"></div>
        <div class="custom_error"></div>
      </div>
    </div>
    <div class="crf-form-setting">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Description:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
        <textarea name="form_des" id="form_des" ><?php if(!empty($row))echo esc_attr($row->form_desc); ?></textarea>
      </div>
    </div>
  
  <div class="crf-form-setting">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Also create WP User account? ',$textdomain);?> <small><?php _e('(This will add Username and Password fields to this form):', $textdomain ); ?></small>
        </div>
      </div>
      <div class="crf-form-right-area">
        <input name="form_type" id="form_type" type="checkbox"  class="upb_toggle" value="reg_form" <?php if (!empty($row) && $row->form_type=='reg_form'){ echo "checked";}?> style="display:none;" />
        <label for="form_type"></label>
      </div>
    </div>
    
    <div id="userrolegroup" style=" <?php if(!empty($row) && $row->form_type=='reg_form'){echo 'display:block;';} else { echo 'display:none;';} ?>">
    <div class="crf-form-setting crf_lock_feature" id="userrolehtml" style=" <?php if(empty($let_user_decide) || $let_user_decide==''){echo 'display:block;';} else { echo 'display:none;';} ?>">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Automatically Assigned WP User Role:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area crf_lock_feature_rightside_message">
      <select name="user_role" id="user_role" disabled >
      <option value="">Subscriber</option>
            </select>
            <span class="crf_pro_features"><?php _e('Only Available in Pro Editions.',$textdomain);?> <a href="http://registrationmagic.com/?download_id=317&edd_action=add_to_cart"><?php _e('Click here',$textdomain);?></a><?php _e(' to upgrade.',$textdomain);?></span>
      </div>
    </div>
    
    <div class="crf-form-setting crf_lock_feature" id="letuserdecidehtml" style=" <?php if(!empty($row) && $row->form_type=='reg_form'){echo 'display:block;';} else { echo 'display:none;';} ?>">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Or Let Users Pick Their Role:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
        <input name="let_user_decide" id="let_user_decide" type="checkbox"  class="upb_toggle" value="" style="display:none;" disabled />
        <label for="let_user_decide"></label>
        <span class="crf_pro_features"><?php _e('Only Available in Pro Editions.',$textdomain);?> <a href="http://registrationmagic.com/?download_id=317&edd_action=add_to_cart"><?php _e('Click here',$textdomain);?></a><?php _e(' to upgrade.',$textdomain);?></span>
      </div>
    </div>
    
    <div class="crf-form-setting" id="userrolelabelhtml" style=" <?php if(!empty($let_user_decide) && $let_user_decide=='1'){echo 'display:block;';} else { echo 'display:none;';} ?>">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'WP User Role Field Label:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
        <input name="user_role_label" id="user_role_label" type="text"  value="<?php if (!empty($user_role_label)){ echo esc_attr($user_role_label);}?>" />
        <div class="custom_error"></div>
      </div>
    </div>
    
    <div class="crf-form-setting" id="userroleoptionhtml" style=" <?php if(!empty($let_user_decide) && $let_user_decide=='1'){echo 'display:block;';} else { echo 'display:none;';} ?>">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Allowed WP Roles for Users:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
      <?php
	  $roles = get_editable_roles();
			  foreach($roles as $key=>$role)
			  {?>
              <label style="float:left;">
          <input type="checkbox" name="user_role_options[]" id="user_role_options[]" value="<?php echo $key;?>"  <?php if(!empty($user_role_options) && in_array($key,$user_role_options)) echo 'checked';?>>
          <span><?php _e($role['name'], $textdomain ); ?></span></label>
			  <?php } ?>
              <div class="custom_error"></div>
      </div>
    </div>
    </div>
    
    <div class="crf-form-setting">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Content Above The Form', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
      <?php if(isset($row))$custom_text = $row->custom_text; else $custom_text = ''; wp_editor( $custom_text, 'form_custom_text' );?>
      </div>
    </div>
    
    <div class="crf-form-setting">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Success Message:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
      <?php if(isset($row))$success_message = $row->success_message; else $success_message = ''; wp_editor( $success_message, 'success_message' );?>
      </div>
    </div>
    
    <div class="crf-form-setting crf_lock_feature">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Show Unique Token Number:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
        <input name="show_token" id="show_token" type="checkbox"  class="upb_toggle" value="1" style="display:none;" disabled/>
        <label for="show_token"></label>
        <span class="crf_pro_features"><?php _e('Only Available in Pro Editions.',$textdomain);?> <a href="http://registrationmagic.com/?download_id=317&edd_action=add_to_cart"><?php _e('Click here',$textdomain);?></a><?php _e(' to upgrade.',$textdomain);?></span>
      </div>
    </div>
   
    <div class="crf-form-setting">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'After Submission, Redirect User to:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area crf_radiorequired">
        <label>
          <input type="radio" name="redirect_option" id="redirect_option_page" value="none" onClick="showhideredirect(this.value)" <?php if(!empty($row) && $row->redirect_option=='none') echo 'checked'; ?> >
          <span><?php _e( 'None', $textdomain ); ?></span></label>
        <label>
          <input type="radio" name="redirect_option" id="redirect_option_page" value="page"  onClick="showhideredirect(this.value)" <?php if(!empty($row) && $row->redirect_option=='page') echo 'checked'; ?>>
          <span><?php _e( 'Page', $textdomain ); ?></span></label>
        <label>
          <input type="radio" name="redirect_option" id="redirect_option_url" value="url"  onClick="showhideredirect(this.value)" <?php if(!empty($row) && $row->redirect_option=='url') echo 'checked'; ?>>
          <span><?php _e( 'URL', $textdomain ); ?></span></label>
          <div class="custom_error"></div>
      </div>
    </div>
    <div class="crf-form-setting" id="page_html" style=" <?php if(!empty($row) && $row->redirect_option=='page'){echo 'display:block;';} else { echo 'display:none;';} ?>">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Page:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
        <?php 
if(!empty($row->redirect_page_id))
{
	$selected = $row->redirect_page_id;
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
    'name'             => 'page_id'); ?>
        <?php wp_dropdown_pages($args); ?>
        <div class="custom_error"></div>
      </div>
    </div>
    <div class="crf-form-setting" id="url_html" style=" <?php if(!empty($row) && $row->redirect_option=='url'){ echo 'display:block;';} else{ echo 'display:none;';} ?>">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'URL:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
        <input type="text" id="redirect_url" name="redirect_url"  value="<?php if(!empty($row)) echo $row->redirect_url_url; ?>" />
        <div class="custom_error"></div>
      </div>
    </div>
    
    <div class="crf-form-setting">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Auto-Reply the User:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
        <input name="send_email" id="send_email" type="checkbox"  class="upb_toggle" value="1" <?php if (!empty($row) && $row->send_email==1){ echo "checked";}?> style="display:none;" />
        <label for="send_email"></label>
      </div>
    </div>
    <div class="crf-form-setting autoresponder"  style="display:<?php if (!empty($row) && $row->send_email==1){ echo "block";}else {echo 'none';}?>">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Auto-Reply Email Subject:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
        <input type="text" name="welcome_email_subject" id="welcome_email_subject"  value="<?php if(!empty($row)) echo esc_attr($row->crf_welcome_email_subject); ?>" />
      </div>
    </div>
    <div class="crf-form-setting autoresponder" style="display:<?php if (!empty($row) && $row->send_email==1){ echo "block";}else {echo 'none';}?>">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Auto-Reply Email Body:', $textdomain ); ?><small><?php _e('(Mail Merge and HTML Supported):', $textdomain ); ?></small>
        </div>
      </div>
      <div class="crf-form-right-area">
      <?php
	   add_action('media_buttons', array($crf_basic_options,'add_crf_fields_list'));
	   if(!empty($row))$welcome_email_message =  $row->crf_welcome_email_message; else $welcome_email_message ='';
	    wp_editor( $welcome_email_message, 'welcome_email_message');?>
      </div>
    </div>
    
    
    <div class="crf-form-setting">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Submit Button Label:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area crf_submit_label">
       <input type="text" name="submit_button_label" id="submit_button_label" placeholder="Submit"  value="<?php if(isset($submit_button_label)) echo esc_attr($submit_button_label);?>" />
       <div class="custom_error"></div>
      </div>
    </div>
    
    <div class="crf-form-setting">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Submit Button Label Color:', $textdomain ); ?>
          <small><?php _e('(Does not works with Classic form style):', $textdomain ); ?></small>
        </div>
      </div>
      <div class="crf-form-right-area">
      <input type="text" value="<?php if(isset($submit_button_color)) echo $submit_button_color;?>" class="crf-color-field" name="submit_button_color" id="submit_button_color" />
      </div>
    </div>
    
    <div class="crf-form-setting">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Submit Button Background Color:', $textdomain ); ?>
          <small><?php _e('(Does not works with Classic form style):', $textdomain ); ?></small>
        </div>
      </div>
      <div class="crf-form-right-area">
      <input type="text" value="<?php if(isset($submit_button_bgcolor)) echo $submit_button_bgcolor;?>" class="crf-color-field" name="submit_button_bgcolor" id="submit_button_bgcolor"  />
      </div>
    </div>
    
    <?php
    $enable_mailchimp = $crf_basic_options->crf_get_global_option_value('enable_mailchimp');
		if($enable_mailchimp=='yes'):
		?>
	<div class="crf-form-setting">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Send To MailChimp List:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
      
      <?php
	  	
		$api_key = $crf_basic_options->crf_get_global_option_value('mailchimp_key');
		require('Mailchimp.php');
		$MailChimp = new Mailchimp( $api_key);
		$list_arg = array('count'=>500);
		$mailchimplist = $MailChimp->get('lists',$list_arg);
		?>
        <select name="mailchimp_list" id="mailchimp_list" >
        <option value="">Select a List</option>
        <?php
		foreach($mailchimplist['lists'] as $list)
		{
			?>
			<option value="<?php echo $list['id'];?>"<?php if(isset($mailchimp_list_id)) selected($mailchimp_list_id, $list['id'] ); ?>><?php echo $list['name'];?></option>
                    
                    <?php
		}
		
		 
		?>
       </select>
      </div>
    </div>
    
    <div class="crf-form-setting">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Map With MailChimp Email Field:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
     	<?php if(isset($row) && $row->form_type=='reg_form'){$disable = 'disabled'; $newoption = '<option value="user_email" selected="selected">user_email</option>';}?>
         <select name="mailchimp_emailfield" id="mailchimp_emailfield" <?php if(isset($disable))echo $disable;?> >
       <?php
	   if(isset($newoption))echo $newoption;
	   $crf_basic_options->crf_fields_dropdown_options($_REQUEST['id'],$mailchimp_emailfield);?>
        </select>
      </div>
    </div>
    
    <div class="crf-form-setting">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Map With MailChimp First Name Field:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
        <select name="mailchimp_firstfield" id="mailchimp_firstfield" >
       <?php $crf_basic_options->crf_fields_dropdown_options($_REQUEST['id'],$mailchimp_firstfield);?>
        </select>
      </div>
    </div>
    
    <div class="crf-form-setting">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Map With MailChimp Last Name Field:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
        <select name="mailchimp_lastfield" id="mailchimp_lastfield" >
       <?php $crf_basic_options->crf_fields_dropdown_options($_REQUEST['id'],$mailchimp_lastfield);?>
        </select>
      </div>
    </div>
     <div class="crf-form-setting">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Show Opt-In Checkbox:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
        <input name="optin_box" id="optin_box" type="checkbox"  class="upb_toggle" value="1" <?php if(isset($optin_box) && $optin_box==1){ echo "checked";}?> style="display:none;" />
        <label for="optin_box"></label>
      </div>
    </div>
    
    <div class="crf-form-setting optin_box_html" style="display:<?php if(isset($optin_box) && $optin_box==1){ echo "block";}else {echo 'none';}?>">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Opt-In Box Text:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
        <textarea name="optin_box_text" id="optin_box_text" ><?php if(!empty($optin_box_text)) echo esc_attr($optin_box_text); ?></textarea>
        <label for="optin_box_text"></label>
      </div>
    </div>
    
    <?php endif;?>
    
    
    <div class="crf-form-setting">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( ' Auto Expires?:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
        <input name="auto_expires" id="auto_expires" type="checkbox"  class="upb_toggle" value="1" <?php if(isset($auto_expires) && $auto_expires==1){ echo "checked";}?> style="display:none;" />
        <label for="auto_expires"></label>
      </div>
    </div>
	
    <div class="auto_expires_html"  style="display:<?php if(isset($auto_expires) && $auto_expires==1){ echo "block";}else {echo 'none';}?>">
    <div class="crf-form-setting" id="auto_expire_option_html">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Expiry Type:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
        <label>
          <input type="radio" name="expiry_type" id="expiry_type_submission" value="submission" onClick="showhidelimitation(this.value)" <?php if(isset($expiry_type) && $expiry_type=='submission') echo 'checked'; ?> >
          <span><?php _e( 'By Submissions', $textdomain ); ?></span></label>
        <label>
          <input type="radio" name="expiry_type" id="expiry_type_date" value="date"  onClick="showhidelimitation(this.value)" <?php if(isset($expiry_type) && $expiry_type=='date') echo 'checked'; ?>>
          <span><?php _e( 'By Date', $textdomain ); ?></span></label>
        <label>
          <input type="radio" name="expiry_type" id="expiry_type_both" value="both"  onClick="showhidelimitation(this.value)" <?php if(isset($expiry_type) && $expiry_type=='both') echo 'checked'; ?>>
          <span><?php _e( 'Set Both <small>(Whichever is earlier)</small>', $textdomain ); ?></span></label>
          <div class="custom_error"></div>
      </div>
    </div>
    
    <div class="crf-form-setting" id="limitation_submission_html" style="display:<?php if(isset($expiry_type) && ($expiry_type=='submission' || $expiry_type=='both')) echo 'block'; else echo 'none';?>;">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Submissions Limit:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area crf_number">
         <input type="text" name="submission_limit" id="submission_limit"  value="<?php if(isset($submission_limit)) echo esc_attr($submission_limit); ?>" />
         <div class="custom_error"></div>
      </div>
    </div>
    
    <div class="crf-form-setting" id="limitation_date_html" style="display:<?php if(isset($expiry_type) && ($expiry_type=='date' || $expiry_type=='both')) echo 'block'; else echo 'none';?>;">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Expiry Date:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area crf_datepicker">
         <input type="text" name="expiry_date" id="expiry_date"  value="<?php if(isset($expiry_date)) echo esc_attr($expiry_date); ?>" class="crf_date rm_tcal" />
         <div class="custom_error"></div>
      </div>
    </div>
    
    <div class="crf-form-setting" id="expiry_message_html">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Display Message in Place of the Form After Expiry', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
         <textarea name="expiry_message" id="expiry_message" ><?php if(isset($expiry_message)) echo esc_attr($expiry_message); ?></textarea>
         <div class="custom_error"></div>
      </div>
    </div>
    
    </div>
    <div class="crf-form-footer">
      <div class="crf-form-button">
        <input type="hidden" name="form_id" id="form_id" value="<?php if(isset($_REQUEST['id'])) echo $_REQUEST['id']?>" />
        <?php wp_nonce_field('save_crf_add_form'); ?>
        <input type="submit" value="<?php _e('Save',$textdomain);?>" name="submit_form" id="submit_form"  />
        <a href="admin.php?page=crf_manage_forms" class="cancel_button"><?php _e('Cancel',$textdomain);?></a>
      </div>
      <div class="customcrferror" style="display:none;"></div>
    </div>
  </form>
</div>
<script type="text/javascript">
    function check(prev) {
        name = jQuery("#form_name").val();
        jQuery.post('<?php echo get_option('siteurl').'/wp-admin/admin-ajax.php';?>?action=check_crf_form_name&cookie=encodeURIComponent(document.cookie)', {
                'name': name,
                'prev': prev
            },
            function (data) {
                //make ajax call to check_username.php
                if (trim(data) == "") {
                    jQuery("#user-result").html('');
                    jQuery("#submit_form").show();
                } else {
                    jQuery("#user-result").html(data);
                    jQuery("#submit_form").hide();
                }
                //dump the data received from PHP page
            });
    }
</script>
