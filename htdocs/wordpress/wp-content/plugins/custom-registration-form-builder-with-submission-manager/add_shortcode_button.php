<?php
/*Controls registration form behavior on the front end*/
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_forms=$wpdb->prefix."crf_forms";
$path =  plugin_dir_url(__FILE__); 
?>
<a href="#TB_inline?width=480&inlineId=crf_add_form_shortcode_popup&width=753" class="thickbox button" id="add_crf_form" title="Add Registration Form"><span class="menu-icon-users"><img src="<?php echo $path;?>images/profile-icon3.png" /></span> Add Form</a>
<div id="crf_add_form_shortcode_popup" style="display:none;">
  <div class="crf-main-form" style=" width:92%;">
    <div class="crf-form-heading">
    <h1><?php _e( 'Select a Form', $textdomain ); ?></h1>
  </div>
      
      <div class="option-main crf-form-setting">
      
      <div class="user-group-option crf-form-right-area">
       <select id="rm_add_form_id" name="add_form_id">
          <?php
		  
			$select = "select * from $crf_forms";
			$reg = $wpdb->get_results($select);
			foreach($reg as $row)
			{
				?>
          <option value="[CRF_Form id='<?php echo $row->id?>']"><?php echo $row->form_name?></option>
          <?php	
			}
		  ?>
        </select>
      </div>
    </div>
      
    
      <br />
      <br />
      <br />
       <div class="crf-form-footer">
      <div class="crf-form-button">
       <input type="submit"  class="button-primary" value="<?php _e('Insert',$textdomain);?>" onClick="insert_form_shortcode(document.getElementById('rm_add_form_id').value)"/>
        <a href="#" class="cancel_button" onclick="tb_remove(); return false;"><?php _e('Cancel',$textdomain);?></a>
	 </div>
     </div>
    </div>
  </div>
<style>
#TB_window { background-color:#f1f1f1;}
</style>