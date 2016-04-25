<?php
/*Controls registration form behavior on the front end*/
global $wpdb;
$form_fields = new crf_basic_fields;
$path =  plugin_dir_url(__FILE__);
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_forms =$wpdb->prefix."crf_forms";
$crf_fields =$wpdb->prefix."crf_fields";
$crf_option=$wpdb->prefix."crf_option";
$crf_submissions =$wpdb->prefix."crf_submissions";
$from_email_address = $form_fields->crf_get_from_email();
$qry = "select * from $crf_submissions where submission_id='".$_REQUEST['id']."'";
$entry = $form_fields->crf_get_all_fields_from_submission($_REQUEST['id']);
$form_name = $form_fields->crf_get_form_option_value('form_name',$entry[0]->form_id);
$form_type =$form_fields->crf_submision_field_value($_REQUEST['id'],'form_type');
$user_approval = $form_fields->crf_submision_field_value($_REQUEST['id'],'user_approval');
$user_name = $form_fields->crf_submision_field_value($_REQUEST['id'],'user_name'); // receiving username
$user_email = $form_fields->crf_submision_field_value($_REQUEST['id'],'user_email'); // receiving email address
$inputPassword = $form_fields->crf_submision_field_value($_REQUEST['id'],'user_pass'); // receiving password
$role = $form_fields->crf_submision_field_value($_REQUEST['id'],'role');
if(isset($_REQUEST['file']))
{
	
	$file = get_attached_file($_REQUEST['file']);
	$form_fields->crf_download_file($file);	exit;	
}
if(isset($_REQUEST['delete_entry']) && isset($_REQUEST['id']))
{
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'delete_crf_entry' ) ) die( 'Failed security check' );
	$qry = "delete from $crf_submissions where submission_id =".$_REQUEST['id'];
	$wpdb->query($qry);
	wp_redirect('admin.php?page=crf_entries&form_id='.$entry[0]->form_id);exit;
}
if(isset($_REQUEST['user_enable']) && isset($_REQUEST['id']))
{
		$retrieved_nonce = $_REQUEST['_wpnonce'];
		if (!wp_verify_nonce($retrieved_nonce, 'approve_crf_entry' ) ) die( 'Failed security check' );
		$uid = $form_fields->crf_create_new_user($_REQUEST['id']);
		if(!is_wp_error($uid))
		{
			$form_fields->crf_insert_user_meta($_REQUEST['id'],$uid);
			$form_fields->crf_create_user_notification($_REQUEST['id']);
		}
		$form_fields->crf_update_submission($_REQUEST['id'],'user_approval','yes');
	  	wp_redirect('admin.php?page=crf_entries&form_id='.$entry[0]->form_id);exit;
}
?>
<div class="crf-main-form">
  <div class="crf-main-form-top-area">
    <div class="crf-form-name-heading">
      <h1><?php echo $form_name; ?></h1>
    </div>
    <div class="crf-form-name-buttons">
      <div class="crf-setting"><a href="#"></a></div>
    </div>
  </div>
  <div class="crf-new-buttons"><span class="crf-back-button">
    <input name="Back" type="submit" autofocus id="Back" title="Back" value="Back" onClick="redirectform(<?php echo $entry[0]->form_id;?>,'crf_entries')">
    </span> 
    
     
    
   
    
    <span class="crf-duplicate-button">
    <form action="admin.php?page=crf_view_entry">
    <?php wp_nonce_field('delete_crf_entry'); ?>
      <input type="submit" value="Delete" name="delete_entry">
      <input type="hidden" value="<?php echo $_REQUEST['id'];?>" name="id" />
      <input type="hidden" value="crf_view_entry" name="page" >
    </form>
    </span>
    
     <div class="crf-add-new-button" style="float:left;">
        <a class="crf_lock_feature_link" onClick="return false">Add Note</a>
      </div>
      
       <div class="crf-add-new-button" style="float:left;">
        <a class="crf_lock_feature_link" onClick="return false">Download as PDF</a>
      </div>
    
     <span class="crf-Approve-Registration-button">
    <?php
if($form_type=='reg_form' && $user_approval=='no')
{
	$paymentstatus = $form_fields->crf_submision_field_value($_REQUEST['id'],'payment_status');
	//echo $paymentstatus;die;
	if(isset($paymentstatus) && trim($paymentstatus)=="pending"):?>
    <div class="crf-add-new-button" style="float:left;">
        <a class="crf_lock_feature_link" onClick="return false">Approve WP Registration</a>
      </div>
    <?php
	endif;
	
	if(!isset($paymentstatus) || $paymentstatus==""):?>
    <div class="crf-add-new-button" style="float:left;">
        <a class="crf_lock_feature_link" onClick="return false">Approve WP Registration</a>
      </div>
    <?php
	endif;
}
?>
    </span></div>
</div>
<div class="crf-signle-entry-form entry" id="crf_submission">
  <div class="crf-single-entry-content">
    <div class="entry_html">
      <?php echo $form_fields->crf_get_entry_details($entry[0]->form_id,$_REQUEST['id']);?>
    </div>
  </div>
</div>
<?php echo $form_fields->crf_get_entry_notes($_REQUEST['id']);?>
    