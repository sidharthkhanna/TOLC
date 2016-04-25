<?php
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_entries =$wpdb->prefix."crf_entries";
$crf_fields =$wpdb->prefix."crf_fields";
$crf_forms =$wpdb->prefix."crf_forms";
$crf_stats =$wpdb->prefix."crf_stats";
$path =  plugin_dir_url(__FILE__); 
//include_once 'crf_functions.php';
$form_fields = new crf_basic_fields;

$qry = "select count(*) from $crf_stats where form_id=".$form_id;
$total_stats = $wpdb->get_var($qry);

$qry = "select count(*) from $crf_stats where `details` LIKE '%yes%' and form_id=".$form_id;
$total_submission = $wpdb->get_var($qry);

$total_fail = $total_stats-$total_submission;
//echo "SELECT * FROM $crf_stats where form_id ='".$form_id."' <br/>";
$stats_entries = $wpdb->get_results( "SELECT * FROM $crf_stats where form_id ='".$form_id."'" );

// pie chart for browsers
if(!empty($stats_entries))
{
	$i=1;
 foreach($stats_entries as $entry)
  {
	  //for get country name start
	  $details = maybe_unserialize($entry->details);
		$ExactBrowserNameUA=$details['Browser'];
		$ExactBrowserNameBR = $form_fields->crf_get_browser_name($ExactBrowserNameUA);
		$browsers_uses[] = $ExactBrowserNameBR;
		//for get success browser details start
		if(isset($details['submitted']) && $details['submitted']=='yes')
		{
			$browsers_success_uses[] = $ExactBrowserNameBR;
			  //for get country name end
		}
		//for get success browser details end
		unset($details);

		$i++;
  }
}

// end pie chart for browsers
//print_r($browsers_success_uses);die;

$qry = "select * from $crf_stats where `details` LIKE '%yes%' and form_id=".$form_id;
$reg = $wpdb->get_results($qry);
$i=0;
$total_time = 0;
foreach($reg as $row)
{
	$details = maybe_unserialize($row->details);	
	$total_time = $total_time + $details['total_time'];
	$i++;
}

if($i>0)
{
$average_time = $total_time/$i;
$success_percentage = ($i/$total_stats)*100;
}
?>
<script type="text/javascript">
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);
      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {
        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Status');
        data.addColumn('number', 'Total');
        data.addRows([
          ['Success', <?php echo $total_submission;?>],
          ['Failure', <?php echo $total_fail;?>]
        ]);
        // Set chart options
        var options = {
		is3D: true,
                       'width':400,
                       'height':300,'colors': ['#4eb7b5', '#ff6c6c']};
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
<script type="text/javascript">
      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});
      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart2);
      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart2() {
		<?php $browsers =  array_count_values($browsers_uses);
		if(!isset($browsers['Opera'])) $browsers['Opera']=0;
		if(!isset($browsers['Chrome'])) $browsers['Chrome']=0;
		if(!isset($browsers['Internet Explorer'])) $browsers['Internet Explorer']=0;
		if(!isset($browsers['Firefox'])) $browsers['Firefox']=0;
		if(!isset($browsers['Safari'])) $browsers['Safari']=0;
		if(!isset($browsers['Other'])) $browsers['Other']=0;
		 ?>
        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Browser');
        data.addColumn('number', 'Views');
        data.addRows([
          ['Opera', <?php if(isset($browsers['Opera'])) echo $browsers['Opera'];?>],
          ['Chrome', <?php if(isset($browsers['Chrome'])) echo $browsers['Chrome'];?>],
		  ['Internet Explorer', <?php if(isset($browsers['Internet Explorer'])) echo $browsers['Internet Explorer'];?>],
          ['Firefox', <?php if(isset($browsers['Firefox'])) echo $browsers['Firefox'];?>],
		  ['Safari', <?php if(isset($browsers['Safari'])) echo $browsers['Safari'];?>],
          ['Other', <?php if(isset($browsers['Other'])) echo $browsers['Other'];?>]
        ]);
        // Set chart options
        var options = {is3D: true,
                       'width':400,
                       'height':300,'colors': ['#4eb7b5', '#b9e2e0', '#ff6c6c', '#ff9d9d', '#ffd4d4','#e39797']};
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div2'));
        chart.draw(data, options);
      }
    </script>
<?php if(isset($browsers_success_uses)):?>
<script type="text/javascript">
	<?php
    $success_browsers =  array_count_values($browsers_success_uses); 
	if(!isset($success_browsers['Opera'])) $success_browsers['Opera']=0;
	if(!isset($success_browsers['Chrome'])) $success_browsers['Chrome']=0;
	if(!isset($success_browsers['Internet Explorer'])) $success_browsers['Internet Explorer']=0;
	if(!isset($success_browsers['Firefox'])) $success_browsers['Firefox']=0;
	if(!isset($success_browsers['Safari'])) $success_browsers['Safari']=0;
	if(!isset($success_browsers['Other'])) $success_browsers['Other']=0;
	
	?>
    google.load("visualization", "1", {packages:["corechart"]});
    google.setOnLoadCallback(drawChart);
    function drawChart() {
      var data = google.visualization.arrayToDataTable([
        ["Browsers", "Success", "Failure"],
        ['Opera', <?php if(isset($success_browsers['Opera'])) echo $success_browsers['Opera'];?>,<?php if(isset($browsers['Opera'])) echo $browsers['Opera']-$success_browsers['Opera'];?>],
		['Chrome', <?php if(isset($success_browsers['Chrome'])) echo $success_browsers['Chrome'];?>,<?php if(isset($browsers['Chrome'])) echo $browsers['Chrome']-$success_browsers['Chrome'];?>],
		['Internet Explorer', <?php if(isset($success_browsers['Internet Explorer'])) echo $success_browsers['Internet Explorer'];?>,<?php if(isset($browsers['Internet Explorer'])) echo $browsers['Internet Explorer']-$success_browsers['Internet Explorer'];?>],
		['Firefox', <?php if(isset($success_browsers['Firefox'])) echo $success_browsers['Firefox'];?>,<?php if(isset($browsers['Firefox'])) echo $browsers['Firefox']-$success_browsers['Firefox'];?>],
		['Safari', <?php if(isset($success_browsers['Safari'])) echo $success_browsers['Safari'];?>,<?php if(isset($browsers['Safari'])) echo $browsers['Safari']-$success_browsers['Safari'];?>],
		['Other', <?php if(isset($success_browsers['Other'])) echo $success_browsers['Other'];?>,<?php if(isset($browsers['Other'])) echo $browsers['Other']-$success_browsers['Other'];?>]
      ]);

      var view = new google.visualization.DataView(data);
      var options = {
        width: 600,
        height: 400,
        bar: {groupWidth: "75%"},
        legend: { position: 'top', maxLines: 3 },
		colors: ['#4eb7b5', '#ff6c6c'],
		isStacked: true
      };
      var chart = new google.visualization.BarChart(document.getElementById("crf_barchart_values"));
      chart.draw(view, options);
  }
  </script>
<?php endif; ?>

<div class="crf-main-form crf-main-form2">
  <div class="charts">
    <div class="main-chat">
    <h1 class="chat-hedding">Conversion %
     <span class="icon"></span>
    </h1>
    <div class="chartdiv" id="chart_div"></div>
    </div>
     <div class="main-chat1">
     <h1 class="chat-hedding">Browsers Used
     <span class="icon"></span></h1>
    <div class="chartdiv1" id="chart_div2"></div>
    </div>
    <div class="cler"></div>
    </div>
    <div class="chartss">
      <?php if(isset($success_percentage)):?>
      <div class="success-div">
      <h1>
     <span class="icon"></span>
    </h1>
      <div class="chartdiv">
        <div class="percent_rate"><span class="tex-average"><?php echo round($success_percentage,1);?><span class="tex-color1">%</span></span></div>
        <div class="charts_title"><h2>Form Success Rate</h2></div>
      </div>
      </div>
      <?php endif;?>
      <?php if(isset($average_time)):?>
      <div class="Time-div">
      <h1>
     <span class="icon"></span>
    </h1>
      <div class="chartdiv">
        <div class="percent_rate oreng"><span class="tex-average"><?php echo round($average_time,1);?><span class="tex-color2">s</span></span></div>
        <div class="charts_title"><h2>Average Time</h2></div>
      </div>
      </div>
      <?php endif; ?>
      <div class="cler"></div>
    </div>
    <?php if(isset($success_percentage)):?>
  <div class="barchart">
  <h1>Browser Based Conversion Comparison<span class="icon"></span></h1>
  <div id="crf_barchart_values"> </div></div>
  <?php endif; ?>
</div>
