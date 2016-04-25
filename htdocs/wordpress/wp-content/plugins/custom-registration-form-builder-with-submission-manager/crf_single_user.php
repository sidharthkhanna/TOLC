<?php
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_submissions =$wpdb->prefix."crf_submissions";
$crf_fields =$wpdb->prefix."crf_fields";
$crf_forms =$wpdb->prefix."crf_forms";
$path =  plugin_dir_url(__FILE__); 
$form_fields = new crf_basic_fields;
$crf_userid = $_REQUEST['id'];
$user_info = get_userdata($crf_userid);
$current_user = wp_get_current_user();
if ($crf_userid == $current_user->ID )
{
	$class="rm_current_user";				
}
else
{
	$class="";
} 

//print_r($user_info );
$userrole = $form_fields->crf_get_userrole_name($crf_userid);
$avatar = get_avatar($user_info->user_email, 300 );
$submissions = $form_fields->crf_submision_get_id_with_field('user_email',$user_info->user_email);
$crf_last_login = get_user_meta($crf_userid,'crf_last_login',true);
if(!empty($crf_last_login))
{
$lastlogin = date('Hi',$crf_last_login).' hrs, ' .date('jS M, Y',$crf_last_login);
}
else
{
	$lastlogin = 'N/A';	
}
$userstatus = get_user_meta($crf_userid, 'crf_user_status', true );	
?>
<script>
 jQuery(function() {
    jQuery( "#tabs" ).tabs();
  });
</script>

<div class="rmuser">
  <div class="col-1">
    <a href="admin.php?page=crf_user_manager"><div class="goback"></div></a>
    <div class="rm_header">
    <div class="title"><?php echo $user_info->user_login;?><span class="userrole"><?php echo $userrole;?></span></div>
    </div>
    <div class="profileimg"><?php echo $avatar;?></div>
    <div class="userstats">Last Seen: <?php echo $lastlogin;?></div>
    <div class="field">
      <div class="label">Username</div>
      <div class="value"><?php echo $user_info->user_login;?></div>
    </div>
    <div class="field">
      <div class="label">First Name</div>
      <div class="value"><?php echo $user_info->first_name;?></div>
    </div>
    <div class="field">
      <div class="label">Last Name</div>
      <div class="value"><?php echo $user_info->last_name;?></div>
    </div>
    <div class="field">
      <div class="label">Nikname</div>
      <div class="value"><?php echo $user_info->user_nicename;?></div>
    </div>
    <div class="field">
      <div class="label">Email</div>
      <div class="value"><?php echo $user_info->user_email;?></div>
    </div>
    <div class="nav">
      <ul>
        <li><a href="admin.php?page=crf_edit_wp_user&id=<?php echo $crf_userid;?>">Edit</a></li>
        <li class="<?php echo $class;?>">
          <div class="tooltip"> <a href="admin.php?page=crf_edit_wp_user&id=<?php echo $crf_userid;?>&action=delete">delete</a>
            <div class="tooltiptext">This will permanently delete this user. It cannot be undone.</div>
          </div>
        </li>
        <?php if ($userstatus == 'deactivate' ): ?>
        <li class="rm_current_user">
          <div class="tooltip"> <a href="">Activate</a>
            <div class="tooltiptext">This will activate this user. You can deactivate it later.</div>
          </div>
        </li>
        <?php else: ?>
        <li class="rm_current_user">
          <div class="tooltip"> <a href="">Deactivate</a>
            <div class="tooltiptext">This will deactivate this user. You can activate it later.</div>
          </div>
        </li>
        <?php endif;?>
      </ul>
    </div>
  </div>
  <div class="col-seperator"></div>
  
  <!----------User Information Block----------->
  
  <div class="col-2 tabs" id="tabs">
    <div class="title"> User Information </div>
    <div class="nav2 tab">
      <ul>
        <li><a href="#customfields">Custom Fields</a></li>
        <li><a href="#submissions">Submissions</a></li>
        <li><a href="#invoices">Invoices</a></li>
      </ul>
    </div>
    <div class="usercontent" id="customfields"> <div class="crf_price_field_upgrade_banner"><div class="crf_price_field_upgrade_banner_inner" style="width:540px;">Ability to display custom form fields on user pages is available in Pro Editions. <a href="http://registrationmagic.com/?download_id=317&edd_action=add_to_cart">Upgrade is available here.</a></div></div> </div>
    <!--------Submissions Table----------->
    
    <div class="usercontent" id="submissions">
      <?php
		if(!empty($submissions)): ?>
      <?php 
			$form_fields->crf_get_short_submissions($submissions); 
		endif;?>
    </div>
    <div class="usercontent" id="invoices">
      <?php
		if(!empty($submissions)): ?>
      <?php 
			$form_fields->crf_get_short_payment_details($submissions); 
		endif;?>
    </div>
  </div>
</div>
<script>
jQuery('.rm_current_user a').removeAttr('href');
jQuery('.rm_current_user a').css('color','#999f9d');
jQuery('.rm_current_user .tooltiptext').css('display','none');

</script>

 <div class="crf_price_field_upgrade_banner"><div class="crf_price_field_upgrade_banner_inner" style="width:540px;">Option to deactivate Users is only available in Pro Editions. <a href="http://registrationmagic.com/?download_id=317&edd_action=add_to_cart">Click here to upgrade</a></div></div>