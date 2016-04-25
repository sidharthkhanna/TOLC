<?php
/*Controls manage fields in the dashboard area*/
wp_enqueue_script( 'jquery-ui-tabs' );
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_forms =$wpdb->prefix."crf_forms";
$crf_fields =$wpdb->prefix."crf_fields";
$path =  plugin_dir_url(__FILE__);
$form_id = $_REQUEST['form_id'];
if(isset($_POST['remove']))
{	
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'delete_crf_fields' ) ) die( 'Failed security check' );
	$ids = implode(',',$_POST['selected']);
	$query = "delete from $crf_fields where Id in($ids)";
	$wpdb->get_results($query);
}
?>
<form name="field_list" id="field_list" method="post">
  <div class="form_fields_container">
    <div id="fields-contain">
      <div class="crf-main-form">
 <div class="crf-main-form-heading-h">
        <div class="crf-form-name-heading">
          <h1><?php _e( 'Form Fields', $textdomain ); ?></h1>
        </div>
        <div class="crf-form-name-buttons">
          <div class="crf-setting"><a href="admin.php?page=crf_add_form&id=<?php echo $_REQUEST['form_id'];?>"><img src="<?php echo $path; ?>images/editform.png"></a></div>
        </div> </div>
        <div  class="crf-add-remove-field">
          <div class="crf-add-field"><?php _e( 'Add Field', $textdomain ); ?></div>
          <div class="crf-remove-field grayout_buttons">
            <input type="submit" name="remove" id="remove" value="Remove" disabled />
          </div>
          <select name="form_id" id="form_id" onChange="redirectform(this.value,'crf_manage_form_fields')">
            <?php
$qry = "select * from $crf_forms";
$reg = $wpdb->get_results($qry);
if(!empty($reg))
{
	foreach($reg as $row)
	{
		?>
           <option value="<?php echo $row->id;?>" <?php if($_REQUEST['form_id']==$row->id) echo 'selected';?>>
            <?php 
		$length = strlen($row->form_name);
if($length<=15){echo $row->form_name;}
else
{
$Valuehalf = substr($row->form_name, 0, 15);
echo $Valuehalf.'...';
}?>
            </option>
            <?php
	}
}
?>
         </select>
        </div>
        </div>
        <div id="tabs" class=" custom-crf-tabs">
  <ul class=" custom-crf-tabs-ul">
    <li><a href="#tabs-1"><?php _e( 'Common Fields', $textdomain ); ?></a></li>
    <li><a href="#tabs-2"><?php _e( 'Special Fields', $textdomain ); ?></a></li>
    <li><a href="#tabs-3"><?php _e( 'Profile Fields', $textdomain ); ?></a></li>
  </ul>
  <div id="tabs-1" class="custom-crf-tab-content">
<div class="custom-fields-button">
    <input type="button" id="text" name="text" value="Text" class="button" onClick="add_field('text')">
            <input type="button" id="select" name="select" value="Drop Down" class="button" onClick="add_field('select')">
            <input type="button" id="radio" name="radio" value="Radio" class="button" onClick="add_field('radio')">
            <input type="button" id="textarea" name="textarea" value="Text Area" class="button" onClick="add_field('textarea')">
             <input type="button" id="checkbox" name="checkbox" value="Checkbox" class="button" onClick="add_field('checkbox')">
             <input type="button" id="heading" name="heading" value="Heading" class="button" onClick="add_field('heading')">
            <input type="button" id="paragraph" name="paragraph" value="Paragraph" class="button" onClick="add_field('paragraph')">
  </div>  </div>
  <div id="tabs-2" class="custom-crf-tab-content">
<div class="custom-fields-button">
   <input type="button" id="DatePicker" name="DatePicker" value="Date" class="button" onClick="add_field('DatePicker')">
            <input type="button" id="email" name="email" value="Email" class="button" onClick="add_field('email')">
            <input type="button" id="number" name="number" value="Number" class="button" onClick="add_field('number')">
            <input type="button" id="country" name="country" value="Country" class="button" onClick="add_field('country')">
            <input type="button" id="timezone" name="timezone" value="Timezone" class="button" onClick="add_field('timezone')">
            <input type="button" id="term_checkbox" name="term_checkbox" value="T&C Checkbox" class="button" onClick="add_field('term_checkbox')">
            <input type="button" id="file" name="file" value="File" class="button crf_lock_field" disabled>
            <input type="button" id="pricing" name="pricing" value="Pricing" class="button" onClick="add_field('pricing')">
            <input type="button" id="pricing" name="pricing" value="Repeatable Text" class="button crf_lock_field" disabled>
    
  </div></div>
  <div id="tabs-3" class="custom-crf-tab-content">
<div class="custom-fields-button">
            <input type="button" id="first_name" name="first_name" value="First Name" class="button" onClick="add_field('first_name')">
            <input type="button" id="last_name" name="last_name" value="Last Name" class="button" onClick="add_field('last_name')">
            <input type="button" id="description" name="description" value="Biographical Info" class="button" onClick="add_field('description')">
  </div> </div>
        
        </div>
      </div>
      <div id="users">
        <div class="crf-main-sortable">
          <ul id="sortable">
            <?php
      	$qry1 = "select * from $crf_fields where Form_Id = '".$_REQUEST['form_id']."' order by ordering asc"; 
		$row1 = $wpdb->get_results($qry1);
		if(!empty($row1))
		{
		foreach($row1 as $result)
		{
		?>
            <li class="rows result" id="<?php echo $result->Id; ?>"> <span></span>
              <div class="cols check-box">
                <input type="checkbox" name="selected[]" value="<?php echo $result->Id; ?>">
              </div>
              <div class="cols type"><?php echo $result->Type; ?></div>
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
        <div class="cols">'.__('There are no fields yet. Why don’t you choose one from above and start building your form now!',$textdomain).'</div>
      </li>
          </ul>';	
		}
		 ?>
          </ul>
        </div>
      </div>
      <div class="crf_price_field_upgrade_banner"><div class="crf_price_field_upgrade_banner_inner" style="width:610px;">File Upload and Repeatable Text fields are only available in Pro Edition. <a href="http://registrationmagic.com/?download_id=317&edd_action=add_to_cart">Click here to upgrade</a></div></div>
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
                jQuery.post('<?php echo get_option('siteurl').'/wp-admin/admin-ajax.php';?>?action=set_field_order&cookie=encodeURIComponent(document.cookie)', {
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
       		 window.location = 'admin.php?page=crf_add_field&formid=' + <?php echo $form_id; ?> +'&id=' + id + '&action=' + action + '&_wpnonce=<?php echo $nonce ?>';
		}
		else
		{
			 window.location = 'admin.php?page=crf_add_field&formid=' + <?php echo $form_id; ?> +'&id=' + id + '&action=' + action;	
		}
    }
</script>
<script>
    function add_field(type) {
        window.location = 'admin.php?page=crf_add_field&type=' + type + '&formid=' + <?php echo $form_id; ?> ;
    }
</script>
<script>
jQuery(function() {
    jQuery( "#tabs" ).tabs().addClass( "ui-tabs-vertical ui-helper-clearfix" );
    jQuery( "#tabs li" ).removeClass( "ui-corner-top" ).addClass( "ui-corner-left" );
  });
  </script>
  <style>
  .ui-tabs-vertical { width: 55em; }
  .ui-tabs-vertical .ui-tabs-nav { padding: .2em .1em .2em .2em; float: left; width: 12em; }
  .ui-tabs-vertical .ui-tabs-nav li { clear: left; width: 100%; border-bottom-width: 1px !important; border-right-width: 0 !important; margin: 0 -1px .2em 0; }
  .ui-tabs-vertical .ui-tabs-nav li a { display:block; }
  .ui-tabs-vertical .ui-tabs-nav li.ui-tabs-active { padding-bottom: 0; padding-right: .1em; border-right-width: 1px; }
  .ui-tabs-vertical .ui-tabs-panel { padding: 1em; float: right; width: 40em;}
  </style>