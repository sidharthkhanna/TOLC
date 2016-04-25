<?php
/*Controls custom field creation in the dashboard area*/
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_submissions =$wpdb->prefix."crf_submissions";
$crf_fields =$wpdb->prefix."crf_fields";
$crf_forms =$wpdb->prefix."crf_forms";
$path =  plugin_dir_url(__FILE__); 
$form_fields = new crf_basic_fields;
$roles = get_editable_roles();


if(isset($_REQUEST['form_id']))
{
	$form_id = $_REQUEST['form_id'];		
}
else
{
	$form_id ='';
}

if(isset($_GET['status']) && isset($_GET['id']))
{
	update_user_meta($_GET['id'],'crf_user_status',$_GET['status']);
	wp_redirect('admin.php?page=crf_user_manager');exit;
}

if(!empty($_POST['selected']) && isset($_POST['remove']))
{
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'manage_crf_entries' ) ) die( 'Failed security check' );
	
	//print_r($_POST['selected']);
	foreach($_POST['selected'] as $userid)
	{
		wp_delete_user($userid);	
	}

}

if(!empty($_POST['selected']) && isset($_POST['deactivate']))
{
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'manage_crf_entries' ) ) die( 'Failed security check' );
	
	//print_r($_POST['selected']);
	foreach($_POST['selected'] as $userid)
	{
		update_user_meta($userid,'crf_user_status','deactivate');	
	}

}

//include 'search_users.php';
?>
<form name="field_list" id="field_list" method="post">
<?php wp_nonce_field('manage_crf_entries'); ?>
<div class="rmcontainer">
        
<!-----Operationsbar Starts----->
    
    <div class="operationsbar">
        <div class="rmtitle"><?php _e('Users Manager',$textdomain);?></div>
        <?php /*?><div class="icons">
        <img src="<?php echo $path;?>images/icon.png">
        <img src="<?php echo $path;?>images/icon2.png">
        </div><?php */?>
        <div class="nav">
        <ul class="rm_disabled_btn rm_action_btn">
        <li><a href="user-new.php"><?php _e('New User');?></a></li>
        <li> <a class="crf_lock_feature_link" onClick="return false"><?php _e('Deactivate',$textdomain);?></a></li>
        <li><input type="submit" name="remove" id="remove" value="Delete" onClick="return popup()" disabled/></li>
        </ul>
            <select name="form_id" id="form_id" onChange="redirectform(this.value,'crf_user_manager')">
      <option value=""><?php _e('All Users',$textdomain);?></option>
        <?php
          foreach($roles as $key=>$role)
          {
			  ?>
              <option value="<?php echo $key;?>" <?php if(isset($_REQUEST['form_id'])) selected($_REQUEST['form_id'], $key ); ?>><?php echo $role['name'];?></option>
              <?php
          }
        ?>
      </select>
        
        </div>
        
        </div>
<!--------Operationsbar Ends----->
        
<!-------Contentarea Starts----->


 <?php
	$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
	$limit = 20; // number of rows in page
	$offset = ( $pagenum - 1 ) * $limit;
	
	// WP_User_Query arguments
$args = array (
    'role' => $form_id,
    'order' => 'ASC',
    'orderby' => 'ID',
	'offset'       => $offset,
	'number'       => $limit
);

if(isset($_GET['crf_filter_result']))
{
	if(!empty($_GET['search_string']))
	{
		$args['search'] = '*'.esc_attr( $_GET['search_string'] ).'*';
	}
	if(!empty($_GET['field_name']) && !empty($_GET['field_value']))
	{
		 $args['meta_query'] = array(
			'relation' => 'OR',
			array(
				'key'     => $_GET['field_name'],
				'value'   => $_GET['field_value'],
				'compare' => '='
			)
		);
	}
}
//$wp_user_query = new WP_User_Query($args);
// Get the results
//$users = $wp_user_query->get_results();

// WP_User_Query arguments
$arg = array (
    'role' => $form_id,
    'order' => 'ASC',
    'orderby' => 'ID'
   
);
if(isset($_GET['crf_filter_result']))
{
	if(!empty($_GET['search_string']))
	{
		$arg['search'] = '*'.esc_attr( $_GET['search_string'] ).'*';
	}
	if(!empty($_GET['field_name']) && !empty($_GET['field_value']))
	{
		 $arg['meta_query'] = array(
			'relation' => 'OR',
			array(
				'key'     => $_GET['field_name'],
				'value'   => $_GET['field_value'],
				'compare' => '='
			)
		);
	}
	
}

$total = count(get_users($arg));
$users = get_users($args);
$num_of_pages = ceil( $total / $limit );
?>


        
<table>
<?php
if(empty($users))
{
?>
      <tr>
        <td><?php _e('No user found associated with this user role.', $textdomain ); ?></td>
      </tr>
      <?php
}
else
{
	$form_fields->crf_get_wp_users($users,$pagenum);
}
?>            
</table>
        
     <?php
	if(isset($num_of_pages))
	{
		$form_fields->crf_get_pagination($num_of_pages,$pagenum);
	}
	?>
    </div>
    </form>
    
    <div class="crf_price_field_upgrade_banner"><div class="crf_price_field_upgrade_banner_inner" style="width:540px;">Option to deactivate Users is only available in Pro Editions. <a href="http://registrationmagic.com/?download_id=317&edd_action=add_to_cart">Click here to upgrade</a></div></div>
    
<script>
 jQuery('input[name="selected[]"]').click(function () {
        var atLeastOneIsChecked = jQuery('input[name="selected[]"]:checked').length > 0;
        if (atLeastOneIsChecked == true) {
			jQuery('.rm_action_btn').removeClass('rm_disabled_btn');
            jQuery('.rm_action_btn input').removeAttr('disabled');
        } else {
			jQuery('.rm_action_btn').addClass('rm_disabled_btn');
            jQuery('.rm_action_btn input').attr('disabled','disabled');
        }
    });	
	/*jQuery('.rm_current_user input').attr('disabled','disabled');*/
	jQuery('.rm_current_user .rm_action_link a').removeAttr('href');
	jQuery('.rm_current_user .rm_action_link a').css('color','#999f9d');
	jQuery('.rm_current_user input').css('cursor','default');
</script>
