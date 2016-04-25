<?php
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_forms =$wpdb->prefix."crf_forms";
$crf_fields =$wpdb->prefix."crf_fields";
$path =  plugin_dir_url(__FILE__); 
$crf_basic_options = new crf_basic_options;
$qrylastrow = "select count(*) from $crf_fields where Form_Id = '".$_REQUEST['formid']."'"; 
$lastrow = $wpdb->get_var($qrylastrow);
$ordering = $lastrow+1;
if(isset($_REQUEST['type'])) $str = $_REQUEST['type'];
if(isset($_REQUEST['action']) && $_REQUEST['action']=='delete')
{
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'delete_crf_field' ) ) die( 'Failed security check' );
	$qry = "delete from $crf_fields where Id=".$_REQUEST['id'];	
	$reg = $wpdb->query($qry);	
	wp_redirect('admin.php?page=crf_manage_form_fields&form_id='.$_REQUEST['formid']);exit;
}
if(isset($_REQUEST['id']))
{
	$qry="select * from $crf_fields where Id=".$_REQUEST['id'];	
	$reg = $wpdb->get_row($qry);
	$str = $reg->Type;
}
if(isset($_POST['field_submit']) && empty($_POST['field_id']))/*Saves the field after clicking save button*/
{
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'save_crf_add_field' ) ) die( 'Failed security check' );
	
	if($_POST['select_type']=='radio' || $_POST['select_type']=='checkbox')
	{
		$finaloptionvalues =  rtrim(implode(',',$_POST['optionvalue']),',');
	}
	else
	{
	    $finaloptionvalues = $_POST['field_Options'];
	}
	if($_POST['select_type']=='pricing')
	{
		$_POST['field_value'] = $_POST['field_Pricing'];	
	}
	if($_POST['select_type']=='profile')
	{
		$_POST['field_value'] = $_POST['field_profile'];	
	}
	
$id = $wpdb->query( $wpdb->prepare( 
	"INSERT INTO $crf_fields (`Form_Id`,`Type`,`Name`,`Value`,`Class`,`Max_Length`,`Cols`,`Rows`,`Option_Value`,`Description`,`Require`,`Readonly`,`Visibility`,`Ordering`)
		VALUES ( %d, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d )", 
        array(
		$_POST['form_id'], 
		$_POST['select_type'], 
		sanitize_text_field($_POST['field_name']), 
		$_POST['field_value'], 
		sanitize_html_class($_POST['field_class']), 
		sanitize_text_field($_POST['field_maxLenght']), 
		sanitize_text_field($_POST['field_cols']), 
		sanitize_text_field($_POST['field_rows']), 
		$finaloptionvalues,  
		sanitize_text_field($_POST['field_Des']), 
		sanitize_text_field($_POST['field_require']), 
		sanitize_text_field($_POST['field_readonly']), 
		sanitize_text_field($_POST['field_visibility']), 
		$ordering 
	) 
) );

$lastid = $wpdb->get_var("select Id from $crf_fields order by Id desc limit 1");
$array = array('Type'=>$_POST['select_type'], 'Name'=>sanitize_text_field($_POST['field_name']),'Id'=>$lastid);
$new_field = (object) $array;
$key = $crf_basic_options->crf_get_field_key($new_field);
$wpdb->query($wpdb->prepare("update $crf_fields set Field_Key=%s where Id=%d",array($key,$lastid)));

wp_redirect('admin.php?page=crf_manage_form_fields&form_id='.$_POST['form_id']);exit;
}
if(isset($_POST['field_submit']) && !empty($_POST['field_id']))
{
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'save_crf_add_field' ) ) die( 'Failed security check' );
	
	if($_POST['select_type']=='radio' || $_POST['select_type']=='checkbox')
	{
		$finaloptionvalues =  implode(',',$_POST['optionvalue']);
	}
	else
	{
	    $finaloptionvalues = $_POST['field_Options'];
	}
	
	if($_POST['select_type']=='pricing')
	{
		$_POST['field_value'] = $_POST['field_Pricing'];	
	}
	 $wpdb->query($wpdb->prepare("update $crf_fields set Form_Id=%d,Type =%s,Name =%s,`Value` =%s,Class=%s,Max_Length=%s,Cols=%s,Rows=%s,Option_Value=%s,Description=%s,`Require`=%s,Readonly=%s,Visibility=%s where Id=%d",array(
		$_POST['form_id'], 
		$_POST['select_type'], 
		sanitize_text_field($_POST['field_name']), 
		$_POST['field_value'], 
		sanitize_html_class($_POST['field_class']), 
		sanitize_text_field($_POST['field_maxLenght']), 
		sanitize_text_field($_POST['field_cols']), 
		sanitize_text_field($_POST['field_rows']), 
		$finaloptionvalues,  
		sanitize_text_field($_POST['field_Des']), 
		sanitize_text_field($_POST['field_require']), 
		sanitize_text_field($_POST['field_readonly']), 
		sanitize_text_field($_POST['field_visibility']), 
		sanitize_text_field($_POST['field_id'])
	) 
) );
	
wp_redirect('admin.php?page=crf_manage_form_fields&form_id='.$_POST['form_id']);exit;
}
?>
<script>
    jQuery(document).ready(function () {
        // Handler for .ready() called.
        getfields('<?php echo $str;?>');
    });
</script>
<style>
.form_field p {
	display: none;
}
#selectfieldtype {
	display: block;
}
</style>
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
      <div class="crf-form-right-area crf_select_required">
        <select name="select_type" id="select_type" onChange="getfields(this.value)">
          <option value=""><?php _e( 'Select A Field', $textdomain ); ?></option>
          <option value="heading" <?php if(isset($str) && $str=='heading') echo 'selected'; ?>><?php _e( 'Heading', $textdomain ); ?></option>
          <option value="paragraph" <?php if(isset($str) && $str=='paragraph') echo 'selected'; ?>><?php _e( 'Paragraph', $textdomain ); ?></option>
          <option value="text" <?php if(isset($str) && $str=='text') echo 'selected'; ?>><?php _e( 'Text', $textdomain ); ?></option>
          <option value="select" <?php if(isset($str) && $str=='select') echo 'selected'; ?>><?php _e( 'Drop Down', $textdomain ); ?></option>
          <option value="radio" <?php if(isset($str) && $str=='radio') echo 'selected'; ?>><?php _e( 'Radio Button', $textdomain ); ?></option>
          <option value="textarea" <?php if(isset($str) && $str=='textarea') echo 'selected'; ?>><?php _e( 'Text Area', $textdomain ); ?></option>
          <option value="checkbox" <?php if(isset($str) && $str=='checkbox') echo 'selected'; ?>><?php _e( 'Check Box', $textdomain ); ?></option>
          <option value="DatePicker" <?php if(isset($str) && $str=='DatePicker') echo 'selected'; ?>><?php _e( 'Date', $textdomain ); ?></option>
          <option value="email" <?php if(isset($str) && $str=='email') echo 'selected'; ?>><?php _e( 'Email', $textdomain ); ?></option>
          <option value="number" <?php if(isset($str) && $str=='number') echo 'selected'; ?>><?php _e( 'Number', $textdomain ); ?></option>
           <option value="country" <?php if(isset($str) && $str=='country') echo 'selected'; ?>><?php _e( 'Country', $textdomain ); ?></option>
           <option value="timezone" <?php if(isset($str) && $str=='timezone') echo 'selected'; ?>><?php _e( 'Timezone', $textdomain ); ?></option>
          <option value="term_checkbox" <?php if(isset($str) && $str=='term_checkbox') echo 'selected'; ?>><?php _e( 'T&C Checkbox', $textdomain ); ?></option>
            <option value="pricing" <?php if(isset($str) && $str=='pricing') echo 'selected'; ?>><?php _e( 'Pricing', $textdomain ); ?></option>
            <option value="first_name" <?php if(isset($str) && $str=='first_name') echo 'selected'; ?>><?php _e( 'First Name', $textdomain ); ?></option>
            <option value="last_name" <?php if(isset($str) && $str=='last_name') echo 'selected'; ?>><?php _e( 'Last Name', $textdomain ); ?></option>
            <option value="description" <?php if(isset($str) && $str=='description') echo 'selected'; ?>><?php _e('Biographical Info', $textdomain ); ?></option>
        </select>
        <div class="custom_error"></div>
      </div>
    </div>
    <div class="crf-form-setting" id="namefield">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Label:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area crf_required">
        <input type="text" name="field_name" id="field_name" value="<?php if(!empty($reg)) echo esc_attr($reg->Name); ?>" onKeyUp="check('<?php if(!empty($reg)){echo $reg->Name;}else { echo 'new';} ?>','<?php if(isset($_REQUEST['formid'])) echo $_REQUEST['formid']?>')">
        <div id="user-result"></div>
        <div class="custom_error"></div>
      </div>
    </div>
    <div class="crf-form-setting" id="optionsfield">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Options:', $textdomain ); ?>
          <small style="float:left;"><?php _e( '(value seprated by comma ",")', $textdomain ); ?></small> </div>
      </div>
      <div class="crf-form-right-area">
        <textarea type="text" name="field_Options" id="field_Options" cols="25" rows="5"><?php if(!empty($reg)) echo esc_attr($reg->Option_Value); ?></textarea>
        <div class="custom_error"></div>
      </div>
    </div>
    
    
    <div class="crf-form-setting" id="profilefield">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Profile Field:', $textdomain ); ?>
          <small style="float:left;"><?php _e( '(value seprated by comma ",")', $textdomain ); ?></small> </div>
      </div>
      <div class="crf-form-right-area">
        <select name="field_profile" id="field_profile">
        <option value=""></option>
        <option value="nickname" <?php if(isset($reg))selected(esc_attr($reg->Value),'nickname'); ?>>Nick Name</option>
        <option value="first_name" <?php if(isset($reg)) selected(esc_attr($reg->Value),'first_name'); ?>>First Name</option>
        <option value="last_name" <?php if(isset($reg)) selected(esc_attr($reg->Value),'last_name'); ?>>Last Name</option>
        </select>
        <div class="custom_error"></div>
      </div>
    </div>
    
    
    <div class="crf-form-setting" id="pricingfield">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Price Field:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
      <select name="field_Pricing" id="field_Pricing">
      <?php
	  if(isset($reg))$selected = $reg->Value;else $selected ='';
	  $crf_basic_options->crf_paypal_fields_dropdown_options($selected);
	  	?>
        </select>
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
	  $optionvalues = @explode(',',$reg->Option_Value);
	  foreach($optionvalues as $optionvalue)
	  {
		  if($optionvalue=='chl_other') continue;
		  ?>
          <li class="optioninputfield">
          <span class="handle"></span>
          	<input type="text" name="optionvalue[]" value="<?php if(!empty($optionvalue)) echo esc_attr($optionvalue); ?>"><span class="removefield" onClick="removefield(this)">Delete</span>
            <div class="custom_error"></div>
            
            </li>
          <?php
	  }
	  ?>
      </ul>
      <input type="text" value="" placeholder="Click to add option" maxlength="0" onClick="addoption()" onKeyUp="addoption()">
      <?php if(!in_array('chl_other',$optionvalues)): ?> 
      <span class="addother" onClick="addother()"> or Add "Other"</span>
      <?php else: ?>
      <div class="optioninputfield" style=" margin-top:12px;"><input type="text" name="optionvalue[]" id="optionvalue[]" value="Their answer" disabled><span class="removefield" onClick="removeother(this)">Delete</span><input type="hidden" name="optionvalue[]" id="optionvalue[]" value="chl_other" /></div>
      <?php endif; ?>
      
      </div>
    </div>
    
    
    
    <div class="crf-form-setting" id="desfield">
      <div class="crf-form-left-area">
        <div class="crf-label">
          <?php _e( 'Description:', $textdomain ); ?>
        </div>
      </div>
      <div class="crf-form-right-area">
        <textarea type="text" name="field_Des" id="field_Des" cols="25" rows="5"><?php if(!empty($reg)) echo esc_textarea($reg->Description); ?></textarea>
      </div>
    </div>
    <div class="crf-form-setting">
      <div class="toggle_button crf-form-left-area">
        <div class="crf-label"><?php _e( 'Advance Options', $textdomain ); ?></div>
        <span class="show_hide" id="plus"></span></div>
    </div>
    <div class="slidingDiv">
      <div class="crf-form-setting" id="valuefield">
        <div class="crf-form-left-area">
          <div class="crf-label">
            <?php _e( 'Default Value:', $textdomain ); ?>
          </div>
        </div>
        <div class="crf-form-right-area">
          <input type="text" name="field_value" id="field_value" value="<?php if(!empty($reg)) echo esc_attr($reg->Value); ?>">
        </div>
      </div>
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
      <div class="crf-form-setting" id="maxlenghtfield">
        <div class="crf-form-left-area">
          <div class="crf-label">
            <?php _e( 'Maximum Length:', $textdomain ); ?>
          </div>
        </div>
        <div class="crf-form-right-area crf_number">
          <input type="text" name="field_maxLenght" id="field_maxLenght" value="<?php if(!empty($reg)) echo esc_attr($reg->Max_Length); ?>">
          <div class="custom_error"></div>
        </div>
      </div>
      <div class="crf-form-setting" id="colsfield">
        <div class="crf-form-left-area">
          <div class="crf-label">
            <?php _e( 'Columns:', $textdomain ); ?>
          </div>
        </div>
        <div class="crf-form-right-area crf_number">
          <input type="text" name="field_cols" id="field_cols" value="<?php if(!empty($reg)) echo esc_attr($reg->Cols); ?>">
          <div class="custom_error"></div>
        </div>
      </div>
      <div class="crf-form-setting" id="rowsfield">
        <div class="crf-form-left-area">
          <div class="crf-label">
            <?php _e( 'Rows:', $textdomain ); ?>
          </div>
        </div>
        <div class="crf-form-right-area crf_number">
          <input type="text" name="field_rows" id="field_rows" value="<?php if(!empty($reg)) echo esc_attr($reg->Rows); ?>">
          <div class="custom_error"></div>
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
      <div class="crf-form-setting" id="readonlyfield">
        <div class="crf-form-left-area">
          <div class="crf-label">
            <?php _e( 'Is Read Only:', $textdomain ); ?>
          </div>
        </div>
        <div class="crf-form-right-area">
          <input type="checkbox" name="field_readonly" id="field_readonly" value="1" style="width:auto;" <?php if(!empty($reg) && $reg->Readonly==1) echo 'checked'; ?> />
        </div>
      </div>
      <div class="crf-form-setting crf_lock_feature" id="visibilityfield">
        <div class="crf-form-left-area">
          <div class="crf-label">
            <?php _e('Show this on User Page:', $textdomain ); ?>
          </div>
        </div>
        <div class="crf-form-right-area crf_lock_feature_rightside_message">
        <input type="checkbox" name="" id="field_visibility" value="1" style="width:auto;" disabled/>
         <input type="hidden" name="field_visibility" id="field_visibility" value="1" />
        <span class="crf_pro_features" style="float:right;"><?php _e('Only Available in Pro Editions.',$textdomain);?> <a href="http://registrationmagic.com/?download_id=317&edd_action=add_to_cart"><?php _e('Click here',$textdomain);?></a><?php _e(' to upgrade.',$textdomain);?></span>
        </div>
      </div>
    </div>
    <div class="crf-form-footer">
      <div class="crf-form-button">
      	<?php wp_nonce_field('save_crf_add_field'); ?>
        <input type="hidden" name="form_id" id="form_id" value="<?php if(isset($_REQUEST['formid'])) echo $_REQUEST['formid']?>" />
        <input type="hidden" name="field_id" id="field_id" value="<?php if(isset($_REQUEST['id'])) echo $_REQUEST['id']?>" />
        <input type="submit" value="Save" name="field_submit" id="field_submit" />
        <input type="button" value="Cancel" class="crf-back-button cancel_button" name="field_back" id="field_back" onClick="redirectform(<?php if(isset($_REQUEST['formid'])) echo $_REQUEST['formid']?>,'crf_manage_form_fields')"  />
      </div>
      <div class="customcrferror" style="display:none;"></div>
    </div>
  </form>
</div>
<script>
    
</script>
<script type="text/javascript">
    function check(prev, formid) {
        name = jQuery("#field_name").val();
        jQuery.post('<?php echo get_option('siteurl').'/wp-admin/admin-ajax.php';?>?action=check_crf_field_name&cookie=encodeURIComponent(document.cookie)', {
                'name': name,
                'prev': prev,
                'formid': formid
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
