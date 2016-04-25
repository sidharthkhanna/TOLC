<?php
/*Controls manage fields in the dashboard area*/
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$path =  plugin_dir_url(__FILE__);
$roles = get_editable_roles();

if(isset($_REQUEST['action']) && $_REQUEST['action']=='delete' && isset($_REQUEST['role']))
{	
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'manage_crf_role' ) ) die( 'Failed security check' );
		remove_role($_REQUEST['role']); 
		wp_redirect('admin.php?page=crf_user_role');exit;
}

if(isset($_POST['remove']))
{	
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'manage_crf_role' ) ) die( 'Failed security check' );
	foreach($_POST['selected'] as $userrole)
	{
		remove_role($userrole); 	
	}
	wp_redirect('admin.php?page=crf_user_role');exit;
	 
}
if(isset($_POST['field_submit']))
{
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'manage_crf_role' ) ) die( 'Failed security check' );
	$parentrole = get_role( $_POST['parent_user_role'] );	
	if(!empty($_POST['role_name']))
	{
	  $a = add_role( str_replace(" ","_",$_POST['role_name']), $_POST['role_name'], $parentrole->capabilities );
	  if($a!="")
	  {
		  $message = 'New Role "'.$_POST['role_name'].'" successfully created!';
		  $error = "";
	  }
	  else
	  {
		  $error = "Warning! User Role already exists. Please choose a unique Role.";
	  }
	}
	if($error=="")
	{
	wp_redirect('admin.php?page=crf_user_role');exit;
	}	
	else
	{
		?>
       <style>
	   .crf-user-role-tab{ display:block !important;}
	   </style>
        <?php	
	}
}
?>
<form name="field_list" id="field_list" method="post">
  <div class="form_fields_container">
    <div id="fields-contain">
      <div class="crf-main-form">
 <div class="crf-main-form-heading-h">
        <div class="crf-form-name-heading">
          <h1><?php _e( 'User Roles', $textdomain ); ?></h1>
        </div>
         </div>
        <div  class="crf-add-remove-field">
          <div class="crf-add-field crf-add-role-toggle"><?php _e( 'Add Role', $textdomain ); ?></div>
          <div class="crf-remove-field grayout_buttons">
            <input type="submit" name="remove" id="remove" value="Remove" disabled />
          </div>
          
        </div>
        </div>
        <div id="tabs" class="custom-crf-tabs crf-user-role-tab" style="display:none;">        
		
        <div class="crf-form-setting" id="profilefield">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Role Name:', $textdomain ); ?>
		</div>
      </div>
      <div class="crf-form-right-area userrolefield">
        <input type="text" name="role_name" id="role_name" value="">
        <div class="custom_error" style="color:red;"><?php if(isset($error)) echo $error;?></div>
      </div>
    </div>
    
    
    <div class="crf-form-setting" id="profilefield">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Permission Level:', $textdomain ); ?>
         </div>
      </div>
      <div class="crf-form-right-area">
        <select name="parent_user_role" id="parent_user_role">
		<?php
          foreach($roles as $key=>$role)
          {
              echo '<option value="'.$key.'">'.$role['name'].'</option>';
          }
        ?>
        </select>
        <div class="custom_error"></div>
      </div>
    </div>
    
    <div class="crf-form-footer crf-form-footer-inner">
      <div class="crf-form-button">
        <input type="submit" value="Save" name="field_submit" id="field_submit" onClick="return check_user_role()" />
      </div>
      <div class="customcrferror" style="display:none;"></div>
    </div>
    

        </div>
      </div>
      <div id="users">
        <div class="crf-main-sortable crf_user_role_page">
          <ul id="sortable">
            <?php
		if(!empty($roles))
		{
		foreach($roles as $key=>$role)
		{
		?>
            <li class="rows result" id="<?php echo $key; ?>"> <span></span>
            <?php if($key == 'administrator' || $key == 'editor' || $key == 'subscriber' || $key == 'contributor' || $key=='author') :?>
            <div class="cols check-box">
                <input type="checkbox" name="selected[]" value="<?php echo $key; ?>" disabled>
              </div>
            <?php else: ?>
            <div class="cols check-box">
                <input type="checkbox" name="selected[]" value="<?php echo $key; ?>">
              </div>
            <?php endif; ?>
              
              <div class="cols type"><?php echo $key; ?></div>
              <div class="cols name">
                <?php 
		$fieldnamelength = strlen($role['name']);
if($fieldnamelength<=15){echo $role['name'];}
else
{
$fieldnamehalf = substr($role['name'], 0, 15);
echo $fieldnamehalf.'...';
}?>
              </div>
              <?php if($key == 'administrator' || $key == 'editor' || $key == 'subscriber' || $key == 'contributor' || $key=='author') :?>
              <div class="cols delete-button grayout_buttons" >
                <input type="button" class="del_button" value="Delete" name="delete_field" id="delete_field" disabled>
              </div>
              <?php else: ?>
              <div class="cols delete-button" >
                <input type="button" class="del_button" value="Delete" name="delete_field" id="delete_field" onclick="manage_role('<?php echo $key; ?>','delete')">
              </div>
              <?php endif; ?>
            </li>
            <?php }
		}
		 ?>
          </ul>
        </div>
      </div>
    </div>
  <?php wp_nonce_field('manage_crf_role'); ?>
</form>

<div class="crf_price_field_upgrade_banner"><div class="crf_price_field_upgrade_banner_inner" style="width:780px;">You can assign user roles created here to registered users. If you want to auto-assign forms to users or allow users to chose roles from a predefined list, please upgrade to Silver Edition.  <a href="http://registrationmagic.com/?download_id=317&edd_action=add_to_cart">Click here to upgrade.</a></div></div>
<script type="text/javascript">

    function manage_role(role,action) 
	{
			 <?php $nonce= wp_create_nonce('manage_crf_role'); ?>
       		 window.location = 'admin.php?page=crf_user_role&role=' + role + '&action=' + action + '&_wpnonce=<?php echo $nonce ?>';
    }
	
	function check_user_role()
	{
		var role = jQuery('#role_name').val();
		var reg = /^[a-zA-Z0-9 ]*$/;
		if(!reg.test(role))
		{
			jQuery('.userrolefield .custom_error').html('<?php _e("Please Enter Valid Role Name (only a-z,A-Z,0-9 allowed)",$textdomain)?>');
			return false;	
		}
	}
	
	 jQuery(".crf-add-role-toggle").click(function () {
		 jQuery("#tabs").show(500);
	 });
</script>