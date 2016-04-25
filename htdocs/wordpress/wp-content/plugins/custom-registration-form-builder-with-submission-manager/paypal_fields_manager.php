<?php
/*Controls manage fields in the dashboard area*/
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_paypal_fields =$wpdb->prefix."crf_paypal_fields";
$path =  plugin_dir_url(__FILE__);
if(isset($_POST['remove']))
{	
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'delete_crf_fields' ) ) die( 'Failed security check' );
	$ids = implode(',',$_POST['selected']);
	$query = "delete from $crf_paypal_fields where Id in($ids)";
	$wpdb->get_results($query);
}
$count = $wpdb->get_var("select count(*) from $crf_paypal_fields");

?>
<form name="field_list" id="field_list" method="post">
  <div class="form_fields_container">
    <div id="fields-contain">
      <div class="crf-main-form">
        <div class="crf-main-form-heading-h">
          <div class="crf-form-name-heading">
            <h1>
              <?php _e( 'Price Fields', $textdomain ); ?>
            </h1>
          </div>
          <div class="crf-form-name-buttons">
            <div class="crf-setting"></div>
          </div>
        </div>
        <div  class="crf-add-remove-field">
          <div class="crf-add-field">
            <?php _e( 'Add Field', $textdomain ); ?>
          </div>
          <div class="crf-remove-field grayout_buttons">
            <input type="submit" name="remove" id="remove" value="Remove" disabled />
          </div>
        </div>
        <div class="fields_container">
          <div class="standard_fields"> <span>
            <?php _e( 'Price Field type', $textdomain ); ?>
            </span>
            <?php if($count<1): ?>
            <input type="button" id="single" name="single" value="Fixed" class="button" onClick="add_field('single')">
            <?php else: ?>
            <input type="button" id="single" name="single" value="Fixed" class="button crf_lock_field" disabled>
            <?php endif;?>
            <input type="button" id="checkbox" name="checkbox" value="Multi-Select" class="button crf_lock_field" disabled>
            <input type="button" id="dropdown" name="dropdown" value="Dropdown" class="button crf_lock_field" disabled>
            <input type="button" id="userdefine" name="userdefine" value="User Defined" class="button crf_lock_field" disabled>
          </div>
        </div>
      </div>
      <div id="users">
        <div class="crf-main-sortable">
          <ul id="sortable">
            <?php
      	$qry1 = "select * from $crf_paypal_fields order by ordering asc"; 
		$row1 = $wpdb->get_results($qry1);
		if(!empty($row1))
		{
		foreach($row1 as $result)
		{
			switch($result->Type)
			{
				case 'single':
					$type = 'Fixed';
					break;
				case 'checkbox':
					$type = 'Multi-Select';
					break;
				case 'dropdown':
					$type = 'Dropdown';
					break;
				case 'userdefine':
					$type = 'User Defined';
					break;
					default:
					$type = '';
					break;
			}
		?>
            <li class="rows result" id="<?php echo $result->Id; ?>"> <span></span>
              <div class="cols check-box">
                <input type="checkbox" name="selected[]" value="<?php echo $result->Id; ?>">
              </div>
              <div class="cols type"><?php echo $type; ?></div>
              <div class="cols name">
                <?php 
		$fieldnamelength = strlen($result->Name);
if($fieldnamelength<=15){echo $result->Name;}
else
{
$fieldnamehalf = substr($result->Name, 0, 15);
echo $fieldnamehalf.'...';
}?>
              </div>
              <div class="cols edit-button" >
                <input type="button" class="edit_button" value="Edit" name="edit_field" id="edit_field" onClick="manage_fields('<?php echo $result->Id; ?>','edit')">
              </div>
              <div class="cols delete-button" >
                <input type="button" class="del_button" value="Delete" name="delete_field" id="delete_field" onclick="manage_fields('<?php echo $result->Id; ?>','delete')">
              </div>
            </li>
            <?php }
		}
		else
		{
			echo '<ul id="sortable" class="crf_entries">
            <li class="rows">
        <div class="cols">'.__('There are no fields yet. Why don’t you chose one from above and start building your form now!',$textdomain).'</div>
      </li>
          </ul>';	
		}
		 ?>
          </ul>
        </div>
      </div>
      
      <div class="crf_price_field_upgrade_banner"><div class="crf_price_field_upgrade_banner_inner">Multiple Price Field Types are only available in Pro Edition. <a href="http://registrationmagic.com/?download_id=317&edd_action=add_to_cart">Click here to upgrade</a></div></div>
    </div>
  </div>
  <?php wp_nonce_field('delete_crf_fields'); ?>
</form>
<script type="text/javascript">
    jQuery(function () {
        jQuery('#sortable').sortable({
            axis: 'y',
            opacity: 0.7,
            handle: 'span',
            update: function (event, ui) {
                var list_sortable = jQuery(this).sortable('toArray').toString();
                // change order in the database using Ajax
                jQuery.post('<?php echo get_option('siteurl').'/wp-admin/admin-ajax.php';?>?action=set_paypal_field_order&cookie=encodeURIComponent(document.cookie)', {
                        'list_order': list_sortable
                    },
                    function (data) {});
            }
        });
    });
</script> 
<script>
    function manage_fields(id, action) {
		if(action=='delete')
		{
			 <?php $nonce= wp_create_nonce('delete_crf_field'); ?>
       		 window.location = 'admin.php?page=crf_add_paypal_field&id=' + id + '&action=' + action + '&_wpnonce=<?php echo $nonce ?>';
		}
		else
		{
			 window.location = 'admin.php?page=crf_add_paypal_field&id=' + id + '&action=' + action;	
		}
    }
</script> 
<script>
    function add_field(type) {
        window.location = 'admin.php?page=crf_add_paypal_field&type=' + type ;
    }
</script>