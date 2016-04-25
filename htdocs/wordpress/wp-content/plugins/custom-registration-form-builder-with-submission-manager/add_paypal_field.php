<?php
global $wpdb;
$textdomain = 'custom-registration-form-pro-with-submission-manager';
$crf_paypal_fields =$wpdb->prefix."crf_paypal_fields";
$path =  plugin_dir_url(__FILE__); 
$qrylastrow = "select count(*) from $crf_paypal_fields"; 
$lastrow = $wpdb->get_var($qrylastrow);
$ordering = $lastrow+1;
if(isset($_REQUEST['type'])) $str = $_REQUEST['type'];
if(isset($_REQUEST['action']) && $_REQUEST['action']=='delete')
{
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'delete_crf_field' ) ) die( 'Failed security check' );
	$qry = "delete from $crf_paypal_fields where Id=".$_REQUEST['id'];	
	$reg = $wpdb->query($qry);	
	wp_redirect('admin.php?page=crf_manage_paypal_fields');exit;
}
if(isset($_REQUEST['id']))
{
	$qry="select * from $crf_paypal_fields where Id=".$_REQUEST['id'];	
	$reg = $wpdb->get_row($qry);
	$str = $reg->Type;
	$extra_option =  maybe_unserialize($reg->extra_options);
	if(isset($extra_option['field_visible'])) $visible = $extra_option['field_visible'];
}
if(isset($_POST['field_submit']) && empty($_POST['field_id']))/*Saves the field after clicking save button*/
{
	
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'save_crf_add_paypal_field' ) ) die( 'Failed security check' );
	if($lastrow>=1)
	{
		wp_redirect('admin.php?page=crf_manage_paypal_fields');exit;	
	}
	$options = array();
	$options['field_visible'] = $_POST['field_visible'];
	$extra_options = maybe_serialize($options);
	
	if($_POST['select_type']=='dropdown' || $_POST['select_type']=='checkbox')
	{
		$finaloptionlabels =  rtrim(implode(',',$_POST['optionlabel']),',');
		$finaloptionprices =  rtrim(implode(',',$_POST['optionprice']),',');
		$i =0;
		foreach($_POST['optionlabel'] as $optionlabel)
		{	
			$prices[] = $optionlabel.'_'.$_POST['optionprice'][$i];
			$i++;
		}
		$finaloptionvalue =  rtrim(implode(',',$prices),',_');
		
	}
	else
	{
		$finaloptionlabels = $_POST['optionlabel'];
		$finaloptionprices = $_POST['optionprice'];
		$finaloptionvalue = $_POST['optionlabel'].'_'.$_POST['optionprice'];
	}
$qry = "insert into $crf_paypal_fields values('','single','".$_POST['field_name']."','".$_POST['field_value']."','".$_POST['field_class']."','".$finaloptionlabels."','".$finaloptionprices."','".$finaloptionvalue."','".$_POST['field_Des']."','".$_POST['field_require']."','".$ordering."','".$extra_options."')";
$row = $wpdb->query($qry);	
wp_redirect('admin.php?page=crf_manage_paypal_fields');exit;
}
if(isset($_POST['field_submit']) && !empty($_POST['field_id']))
{
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'save_crf_add_paypal_field' ) ) die( 'Failed security check' );
	$options = array();
	$options['field_visible'] = $_POST['field_visible'];
	$extra_options = maybe_serialize($options);
	
	if($_POST['select_type']=='dropdown' || $_POST['select_type']=='checkbox')
	{
		$finaloptionlabels =  rtrim(implode(',',$_POST['optionlabel']),',');
		$finaloptionprices =  rtrim(implode(',',$_POST['optionprice']),',');
		$i =0;
		foreach($_POST['optionlabel'] as $optionlabel)
		{	
			$prices[] = $optionlabel.'_'.$_POST['optionprice'][$i];
			$i++;
		}
		$finaloptionvalue =  rtrim(implode(',',$prices),',_');
		
	}
	else
	{
		$finaloptionlabels = $_POST['optionlabel'];
		$finaloptionprices = $_POST['optionprice'];
		$finaloptionvalue = $_POST['optionlabel'].'_'.$_POST['optionprice'];
	}
	$qry = "update $crf_paypal_fields set Type ='".$_POST['select_type']."',Name ='".$_POST['field_name']."',`Value` ='".$_POST['field_value']."',Class='".$_POST['field_class']."',Option_Price='".$finaloptionprices."',Option_Label='".$finaloptionlabels."',Option_Value='".$finaloptionvalue."',Description='".$_POST['field_Des']."',`Require`='".$_POST['field_require']."',`extra_options`='".$extra_options."' where Id='".$_POST['field_id']."'";
$row = $wpdb->query($qry);	
wp_redirect('admin.php?page=crf_manage_paypal_fields');exit;
}
?>
<style>
.form_field p {
	display: none;
}
#selectfieldtype {
	display: block;
}
</style>
<script type="text/javascript">
jQuery(document).ready(function () {
        // Handler for .ready() called.
        get_paypal_field('<?php echo $str;?>');
    });
</script>
<div class="crf-main-form">
  <div class="crf-form-heading">
  <?php if(isset($_REQUEST['id'])):?>
    <h1><?php _e( 'Edit Field:', $textdomain ); ?></h1>
    <?php else: ?>
    <h1><?php _e( 'New Field:', $textdomain ); ?></h1>
    <?php endif; ?>
  </div>
  
  <!--HTML for custom field creation-->
  
  <form method="post" action="" id="add_field_form">
    <div class="crf-form-setting" id="selectfieldtype">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Select Type:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
        <select name="select_type" id="select_type" onChange="get_paypal_field(this.value)">
        <option value=""><?php _e('Select Price Type',$textdomain);?></option>
          <option value="single" <?php if(isset($str) && $str=='single') echo 'selected'; ?>><?php _e( 'Fixed Price', $textdomain ); ?></option>
        </select>
        <div class="custom_error"></div>
        
      </div>
    </div>
    <div class="crf-form-setting" id="namefield">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Name:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area crf_required">
        <input type="text" name="field_name" id="field_name" value="<?php if(!empty($reg)) echo $reg->Name; ?>" onKeyUp="check('<?php if(!empty($reg)){echo $reg->Name;}else { echo 'new';} ?>','<?php if(isset($_REQUEST['formid'])) echo $_REQUEST['formid']?>')">
        <div id="user-result"></div>
        <div class="custom_error"></div>
      </div>
    </div>
    
    <div class="crf-form-setting" id="desfield">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Description:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
        <textarea type="text" name="field_Des" id="field_Des" cols="25" rows="5"><?php if(!empty($reg)) echo $reg->Description; ?></textarea>
      </div>
    </div>
    
    <div class="crf-form-setting" id="valuefield">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Price:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area crf_number">
        <input type="text" name="field_value" id="field_value" value="<?php if(!empty($reg)) echo $reg->Value; ?>">
        <div class="custom_error"></div>
      </div>
    </div>
    
    <div class="crf-form-setting" id="optionsfield2">
      <div class="crf-form-left-area">
        <div class="crf-label"><?php _e( 'Options:', $textdomain ); ?></div>
      </div>
      <div class="crf-form-right-area">
      <ul id="sortablefield" class="optionsfieldwrapper">
      <?php
	  $optionvalues = @explode(',',$reg->Option_Label);
	  $optionprices = @explode(',',$reg->Option_Price);
	 // print_r($optionprices);die;
	  $i=0;
	  foreach($optionvalues as $optionvalue)
	  {
		  ?>
          <li class="optioninputfield">
          <span class="handle"></span>
          	<input type="text" name="optionlabel[]" placeholder="<?php _e('Option',$textdomain);?>" value="<?php if(!empty($optionvalue)) echo esc_attr($optionvalue); ?>" style=" width:200px !important;">
            <input type="text" name="optionprice[]" class="crf_number" placeholder="<?php _e('Price',$textdomain);?>" value="<?php if(!empty($optionprices)) echo esc_attr($optionprices[$i]); ?>" style=" width:130px !important;"><span class="removefield" onClick="removefield(this)">Delete</span>
            <div class="custom_error"></div>
            
            </li>
          <?php
		  $i++;
	  }
	  ?>
      </ul>
      <input type="text" value="" placeholder="<?php _e('Click to add option',$textdomain);?>" maxlength="0" onClick="add_paypal_field()" onKeyUp="add_paypal_field()">
  
      </div>
    </div>
    
    <div class="crf-form-setting" id="visiblefield">
        <div class="crf-form-left-area">
          <div class="crf-label">
            <?php _e( 'Is Visible on Form:', $textdomain ); ?>
          </div>
        </div>
        <div class="crf-form-right-area">
          <input type="checkbox" name="field_visible" id="field_visible" value="1" style="width:auto;" <?php if(!empty($visible) && $visible==1) echo 'checked'; ?>/>
        </div>
      </div>
    
    <div class="crf-form-setting" style="display:none !important;">
      <div class="toggle_button crf-form-left-area">
        <div class="crf-label"><?php _e( 'Advance Options', $textdomain ); ?></div>
        <span class="show_hide" id="plus"></span></div>
    </div>
    <div class="slidingDiv" style="display:none !important;">
      <div class="crf-form-setting" id="classfield">
        <div class="crf-form-left-area">
          <div class="crf-label">
            <?php _e( 'CSS Class Attribute:', $textdomain ); ?>
          </div>
        </div>
        <div class="crf-form-right-area">
          <input type="text" name="field_class" id="field_class" value="<?php if(!empty($reg)) echo $reg->Class; ?>">
        </div>
      </div>
      
   <div class="crf-form-setting">
        <p class="rules" id="rulesfield" style="width:100%;"><?php _e( 'Rules', $textdomain ); ?></p>
      </div>
      
     
      
      
      <div class="crf-form-setting" id="requirefield">
        <div class="crf-form-left-area">
          <div class="crf-label">
            <?php _e( 'Is Required:', $textdomain ); ?>
          </div>
        </div>
        <div class="crf-form-right-area">
          <input type="checkbox" name="field_require" id="field_require" value="1" style="width:auto;" <?php if(!empty($reg) && $reg->Require==1) echo 'checked'; ?>/>
        </div>
      </div>
      
      
      
    </div>
    <div class="crf-form-footer">
      <div class="crf-form-button">
      	<?php wp_nonce_field('save_crf_add_paypal_field'); ?>
        <input type="hidden" name="field_id" id="field_id" value="<?php if(isset($_REQUEST['id'])) echo $_REQUEST['id']?>" />
        <input type="submit" value="Save" name="field_submit" id="field_submit" />
        <input type="button" value="Cancel" class="crf-back-button" name="field_back" id="field_back" onClick="redirectform('','crf_manage_paypal_fields')"  />
      </div>
      <div class="customcrferror" style="display:none;"></div>
    </div>
  </form>
</div>
<script type="text/javascript">
function add_paypal_field() {
var b = '<li class="optioninputfield newfield"><span class="handle"></span><input type="text" name="optionlabel[]" placeholder="<?php _e('Option',$textdomain);?>" value="" style=" width:200px !important;"><input type="text" name="optionprice[]" class="crf_number" placeholder="<?php _e('Price',$textdomain);?>" value="" style=" width:130px !important;"><span class="removefield" onClick="removefield(this)">Delete</span></li>';
jQuery('.optionsfieldwrapper').append(b);
jQuery('.newfield:last input:first').focus();
}
</script>
<script type="text/javascript">
    function check(prev) {
        name = jQuery("#field_name").val();
        jQuery.post('<?php echo get_option('siteurl').'/wp-admin/admin-ajax.php';?>?action=check_crf_paypal_field_name&cookie=encodeURIComponent(document.cookie)', {
                'name': name,
                'prev': prev
            },
            function (data) {
                //make ajax call to check_username.php
				//alert(data)
                if (jQuery.trim(data) == "") {
                    jQuery("#user-result").html('');
                    jQuery("#field_submit").show();
					return true;
                } else {
                    jQuery("#user-result").html(data);
                    jQuery("#field_submit").hide();
					return false;
                }
                //dump the data received from PHP page
            });
    }
</script>
