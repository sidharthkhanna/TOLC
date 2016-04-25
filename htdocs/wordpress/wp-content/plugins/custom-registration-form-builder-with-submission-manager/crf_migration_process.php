<?php
error_reporting(0);
global $wpdb;
$crf_entries =$wpdb->prefix."crf_entries";
$crf_submissions =$wpdb->prefix."crf_submissions";
include 'crf_functions.php';
$qry = "select * from $crf_entries";
$reg = $wpdb->get_results($qry);
foreach($reg as $row)
{
	$id = $row->id;
	$form_id = $row->form_id;
	$form_type = $row->form_type;
	$user_approval = $row->user_approval;
	$value = maybe_unserialize($row->value);
	
	crf_insert_submission($id,$form_id,'form_type',$form_type);	
	crf_insert_submission($id,$form_id,'user_approval',$user_approval);	
	foreach($value as $key => $val)
	{
		//echo $key . ' '. $val;	
		if(is_array($val))
		{
			$val = implode(',',$val);	
		}
		crf_insert_submission($id,$form_id,$key,$val);	
	}
	
		
}
echo 'submission migrated successful';
update_option( "crf_migrate_submission", 'yes' );
?>