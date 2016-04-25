<?php
class crf_basic_form
{
	function __construct() {
	
	}
	
	function crf_get_form_option_value($fieldname,$id)
	  {
		  global $wpdb;
		  $crf_forms=$wpdb->prefix."crf_forms";
		  $qry="SELECT $fieldname FROM $crf_forms WHERE id=".$id;
		  $value = $wpdb->get_var($qry);
		  return $value;
		  
	  }
	  
		
}

?>