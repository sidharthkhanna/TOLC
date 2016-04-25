<?php
/*Controls custom field creation in the dashboard area*/
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_submissions =$wpdb->prefix."crf_submissions";
$crf_fields =$wpdb->prefix."crf_fields";
$crf_forms =$wpdb->prefix."crf_forms";
$path =  plugin_dir_url(__FILE__); 
$form_fields = new crf_basic_fields;
if(isset($_REQUEST['form_id']))
{
	$form_id = $_REQUEST['form_id'];		
}
else
{
	$qry = "select id from $crf_forms order by id asc limit 1";
    $reg = $wpdb->get_var($qry);
	$form_id = $_REQUEST['form_id']=$reg;
}
if(!empty($_POST['selected']) && isset($_POST['remove']))
{
	$retrieved_nonce = $_REQUEST['_wpnonce'];
	if (!wp_verify_nonce($retrieved_nonce, 'manage_crf_entries' ) ) die( 'Failed security check' );
	$ids = implode(',',$_POST['selected']);
	$query = "delete from $crf_submissions where submission_id in($ids)";
	$wpdb->get_results($query);
}
include 'search_entries.php';
?>
<form name="field_list" id="field_list" method="post">
<?php wp_nonce_field('manage_crf_entries'); ?>
  <div class="crf-main-form">
    <div class="crf-form-heading">
      <h1><?php _e('Submissions', $textdomain ); ?><div class="crf-filter"><img src="<?php echo $path;?>images/filter.png"></div></h1>
      
    </div>
    <div  class="crf-add-remove-field-submissions crf-new-buttons">
    <div class="crf-add-new-button" style="float:left;">
        <a class="crf_lock_feature_link" onClick="return false">Export All</a>
      </div>
      <?php if(isset($_GET['crf_filter_result'])):?>
      <div class="crf-add-new-button" style="float:left;">
        <a class="crf_lock_feature_link" onClick="return false">Export Search Result</a>
      </div>
      <?php endif; ?>
      
      <div class="crf-remove-field grayout_buttons">
        <input type="submit" name="remove" id="remove" value="Delete" onClick="return popup()" disabled/>
      </div>
      
      <select name="form_id" id="form_id" onChange="redirectform(this.value,'crf_entries')">
        <?php
   $form_fields->crf_get_all_form_list_option();
    ?>
      </select>
    </div>
  </div>
  <div class="crf-main-sortable">
    <ul id="sortable" class="crf_entries">
      <?php
$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
$limit = 20; // number of rows in page
$offset = ( $pagenum - 1 ) * $limit;
if($_REQUEST['form_id']!="")
{
	$totalentryqry = "SELECT count(distinct(submission_id))  FROM $crf_submissions where form_id ='".$form_id."'";
 
  if(isset($_GET['crf_filter_result']))
  {
	if($_GET['start_date']!="" && $_GET['end_date']!="")
	{
		$startdate = date_create($_GET['start_date']);
		$startdate =  date_format($startdate, 'U');
		
		$enddate = date_create($_GET['end_date']);
		$enddate =  date_format($enddate, 'U');
		
	$totalentryqry .=" and `field` = 'entry_time' and `value` between '".$startdate."' and '".$enddate."'";
	}
	if($_GET['field_name']!="" && $_GET['field_value']!="")
	{
	$totalentryqry .=" and submission_id in(select distinct(submission_id) FROM wp_crf_submissions where `field` = '".$_GET['field_name']."' and `value` like '%".$_GET['field_value']."%') ";	
	}
	
	if($_GET['field_name']=="" && $_GET['field_value']!="")
	{
	$totalentryqry .=" and submission_id in(select distinct(submission_id) FROM wp_crf_submissions where `value` like '%".$_GET['field_value']."%') ";	
	}
	//echo $totalentryqry;
	
  }
  
  $total = $wpdb->get_var( $totalentryqry );
  
  $num_of_pages = ceil( $total / $limit );
  $qry = "SELECT distinct(submission_id) FROM $crf_submissions where form_id ='".$form_id."'";
 
  if(isset($_GET['crf_filter_result']))
  {
	if($_GET['start_date']!="" && $_GET['end_date']!="")
	{
		$startdate = date_create($_GET['start_date']);
		$startdate =  date_format($startdate, 'U');
		
		$enddate = date_create($_GET['end_date']);
		$enddate =  date_format($enddate, 'U');
		
		$qry .=" and `field` = 'entry_time' and `value` between '".$startdate."' and '".$enddate."'";
	}
	if($_GET['field_name']!="" && $_GET['field_value']!="")
	$qry .=" and submission_id in(select distinct(submission_id) FROM wp_crf_submissions where `field` = '".$_GET['field_name']."' and `value` like '%".$_GET['field_value']."%') ";
	
	if($_GET['field_name']=="" && $_GET['field_value']!="")
	$qry .=" and submission_id in(select distinct(submission_id) FROM wp_crf_submissions where `value` like '%".$_GET['field_value']."%') ";
  }
  
  $qry .=" order by id desc LIMIT $offset, $limit";
  //echo $qry;
  
  $entries = $wpdb->get_results($qry);
}
if(empty($entries))
{
?>
      <li class="rows">
        <div class="cols"><?php _e('No submissions for this form have been recorded.', $textdomain ); ?></div>
      </li>
      <?php
}
else
{
	$form_fields->crf_get_submissions($entries,$form_id,$pagenum);
}
?>
    </ul>
    <?php
	if(isset($num_of_pages))
	{
		$form_fields->crf_get_pagination($num_of_pages,$pagenum);
	}
?>
  </div>
</form>
<div class="crf_price_field_upgrade_banner"><div class="crf_price_field_upgrade_banner_inner" style="width:620px;">Exporting all submissions as spreadsheet is only available in Pro Editions. <a href="http://registrationmagic.com/?download_id=317&edd_action=add_to_cart">Click here to upgrade</a></div></div>
<script>
    function redirectform(id) {
        window.location = 'admin.php?page=crf_entries&form_id=' + id;
    }
	
	jQuery('.crf-filter').click(function () {
		jQuery('.crf-search-submissions').toggle("blind",{},1000);
    });
	
</script>
<script>
    function popup() {
        a = confirm("<?php _e('Are you sure you want to remove?', $textdomain ); ?>");
        if (a == true) {
            return true;
        } else {
            return false;
        }
    }
</script>