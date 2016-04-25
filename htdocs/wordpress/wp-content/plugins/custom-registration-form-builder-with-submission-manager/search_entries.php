<?php 
	$textdomain = 'custom-registration-form-builder-with-submission-manager';
?>

<div class="crf-main-form crf-search-submissions" style="display:<?php if(isset($_GET['crf_filter_result'])) echo 'block'; else echo 'none';?>">
    <div class="crf-form-heading">
      <h1><?php _e('Search Submissions',$textdomain);?><div class="crf-filter"><img src="<?php echo $path;?>images/filter.png"></div></h1>
    </div>
    <div class="crf-form-setting">
  
     <form name="filter" id="filter" method="get" action="admin.php?page=crf_entries">
      <div class="crf-left-search-form">
     <div class="crf-form-setting-forms">
     <label><span class="dashicons dashicons-calendar-alt"></span><?php _e('From Date',$textdomain);?></label>
	<input type="text" name="start_date" id="start_date" value="<?php if(isset($_GET['start_date'])) echo $_GET['start_date']; ?>" class="crf_date rm_tcal" />
    </div>
    <div class="crf-form-setting-forms">
    <label><span class="dashicons dashicons-calendar-alt"></span><?php _e('To Date',$textdomain);?></label>
    <input type="text" name="end_date" id="end_date" value="<?php if(isset($_GET['end_date'])) echo $_GET['end_date']; ?>" class="crf_date rm_tcal" />
    </div>
    </div>
    <div class="crf-left-search-form">
    <div id="group_field">
    <div class="wrapper">
    <div class="crf-form-setting-forms">
    <label><?php _e('Select Field',$textdomain);?></label>
    <select name="field_name" id="field_name">
     <option value=""><?php _e('All Fields',$textdomain);?></option>
    <?php
	
	 $qry1 = "select * from $crf_fields where Form_Id= '".$form_id."' and Type not in('heading','paragraph','DatePicker','file') order by ordering asc";
	  $reg1 = $wpdb->get_results($qry1);
	  if(!empty($reg1))
	  {
	   foreach($reg1 as $row1)
	   {
		  if(!empty($row1))
		  {
			  $Customfield = $form_fields->crf_get_field_key($row1)
			  ?>
              <option value="<?php echo $Customfield;?>" <?php if(isset($_GET['field_name']) && $Customfield==$_GET['field_name']) echo 'selected';?>><?php echo $row1->Name;?></option>
              <?php
		  }
	   }
	  }
	?>
   
    </select>
    </div>
    
    <div class="crf-form-setting-forms">
    
    <label><?php _e('Match Value',$textdomain);?></label>
    <input type="text" name="field_value" id="field_value" value="<?php if(isset($_GET['field_value'])) echo $_GET['field_value'];?>"/>
    </div>
    
    </div>
    </div>
    </div>
    <div class="crf-form-search-button">
    <input type="hidden" name="form_id" value="<?php echo $form_id;?>" />
    <input type="hidden" name="page" value="crf_entries" />
    <input type="reset" name="reset" onClick="clearForm('#filter')" />
    
    <input type="hidden" name="pagenum" value="1" />
    <input type="submit" name="crf_filter_result" value="<?php _e('Search',$textdomain);?>" />
    </div>
</form>
      
      
    </div>
  </div>