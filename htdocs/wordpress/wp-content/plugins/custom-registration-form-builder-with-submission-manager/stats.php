<?php
/*Controls custom field creation in the dashboard area*/
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_entries =$wpdb->prefix."crf_entries";
$crf_fields =$wpdb->prefix."crf_fields";
$crf_forms =$wpdb->prefix."crf_forms";
$crf_stats =$wpdb->prefix."crf_stats";
$path =  plugin_dir_url(__FILE__); 
if(isset($_POST['reset_button']))
{
	//$qry = "TRUNCATE TABLE $crf_stats";	
	$qry = "delete from $crf_stats where form_id=".$_POST['form_id'];
	$wpdb->query($qry);
}
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
?>
<form name="field_list" id="field_list" method="post">
  <div class="crf-main-form">
    <div class="crf-form-name-heading-Submissions">
      <h1 class="hedding-icon"><?php _e('Form Analytics', $textdomain ); ?></h1>
    </div>
    <div  class="crf-add-remove-field-submissions crf-new-buttons">
    <div class="crf-add-new-button" style="float:left;">
        <input class="reset-butten" type="submit" name="reset_button" value="RESET" onClick="return popup()" />
      </div>
      <select name="form_id" id="form_id" onChange="redirectform(this.value,'crf_stats')">
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
		$formnamelength = strlen($row->form_name);
if($formnamelength<=15){echo $row->form_name;}
else
{
$formnamehalf = substr($row->form_name, 0, 15);
echo $formnamehalf.'...';
}?>
        </option>
        <?php
        }
    }
    ?>
      </select>
    </div>
  </div>
  <div class="crf-main-sortable">
    <ul id="sortable" class="crf_entries">
      <?php
$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
$limit = 10; // number of rows in page
$offset = ( $pagenum - 1 ) * $limit;
if($_REQUEST['form_id']!="")
{
  $total = $wpdb->get_var( "SELECT count(*) FROM $crf_stats where form_id ='".$form_id."'" );
  $num_of_pages = ceil( $total / $limit );
  $entries = $wpdb->get_results( "SELECT * FROM $crf_stats where form_id ='".$form_id."' LIMIT $offset, $limit" );
}
else
{
  $num_of_pages = 1;	
}
if(empty($entries))
{
?>
      <li class="rows">
        <div class="cols" style="min-height: 45px;"><?php _e('Sorry, insufficient data captured to generate graphs. Check back after few more submissions have been recorded.', $textdomain ); ?></div>
      </li>
      <?php
}
else
{
?>
      <li class="header rows">
        <div class="cols" style="width:30px;"><?php _e('#',$textdomain);?></div>
        <div class="cols" style="width:140px;"><?php _e('Visitor IP',$textdomain);?></div>
        <div class="cols" style="width:115px;"><?php _e('Submission',$textdomain);?></div>
        <div class="cols" style="width:200px;"><?php _e('Visited On (UTC)',$textdomain);?></div>
        <div class="cols" style="width:200px;"><?php _e('Submitted On (UTC)',$textdomain);?></div>
        <div class="cols" style="width:135px;"><?php _e('Form Filling Time',$textdomain);?></div>
       <!-- <div class="cols" style="">Browser</div>-->
        
      </li>
      <?php
  $i=1 + $offset;
  foreach($entries as $entry)
  {
	  $details = maybe_unserialize($entry->details); 
	if($i%2==0)
	{
		$class="";
	}
	else
	{
		$class="alternate";
	}
  ?>
  
      <li class="<?php echo $class;?> rows">
        <div class="cols" style="width:30px;"><?php echo $i; ?></div>
          <div class="cols" style="width:140px;"><a target="_blank" href="http://www.geoiptool.com/?IP=<?php if(isset($details['User_IP'])) echo $details['User_IP'];?>"><?php if(isset($details['User_IP'])) echo $details['User_IP'];?></a></div>
          <div class="cols" style="Width:115px;"><?php if(isset($details['submitted']) && $details['submitted']=='yes')echo '<img style="width: 20px !important;height: 20px !important;" class="submitted_icon" src="'.$path.'images/right.png" />';?></div>
           <div class="cols" style="width:200px;"><?php if(isset($details['timestamp'])) echo date("Y-m-d H:i:s",$details['timestamp']);?></div>
            <div class="cols" style="width:200px;"><?php if(isset($details['submit_time']) && $details['submit_time']!="") echo date("Y-m-d H:i:s",$details['submit_time']);?></div>
            <div class="cols" style="width:135px;"><?php if(isset($details['total_time'])) echo $details['total_time'];?></div>
      </li>
      <?php 
$i++;
} 
}
?>
<div class="cler"></div>
    </ul>
    <?php
	$args = array(
	'base'               => add_query_arg( 'pagenum', '%#%' ),
	'format'             => '',
	'total'              => $num_of_pages,
	'current'            => $pagenum,
	'show_all'           => False,
	'end_size'           => 1,
	'mid_size'           => 2,
	'prev_next'          => True,
	'prev_text'          => __('&laquo;', 'text-domain' ),
	'next_text'          => __('&raquo;', 'text-domain'),
	'type'               => 'plain',
	'add_args'           => False,
	'add_fragment'       => '',
	'before_page_number' => '',
	'after_page_number'  => ''
);
$page_links = paginate_links( $args );
if ( $page_links ) {
    echo '<div class="tablenav crfpagination"><div class="tablenav-pages" style="">' . $page_links . '</div></div>';
}
?>
  </div>
</form>
<style>
img.submitted_icon{ width: 20px !important; margin-left:40px;
height: 20px !important;
border: none !important;}
</style>
<?php if(!empty($entries)) {include "pie_chart.php";} ?>