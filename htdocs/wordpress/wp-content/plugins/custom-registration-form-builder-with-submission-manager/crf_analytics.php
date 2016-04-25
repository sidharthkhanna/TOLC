<?php
$path =  plugin_dir_url(__FILE__); $path =  plugin_dir_url(__FILE__); 
$textdomain = 'custom-registration-form-builder-with-submission-manager';
?> 

            
 <div class="crf-main-form crf-main-form2">
 <div class="crf-chart-top">
    <form name="field_list" id="field_list" method="post">
    <div class="crf-form-name-heading-Submissions">
      <h1 class="fieldanalytics-icon">Field Analytics</h1>
    </div>
    <div class="crf-add-remove-field-submissions crf-new-buttons">
      <select name="form_id" id="form_id">
                <option value="2" selected="">Form 1</option>
                <option value="16">Form 2</option>
              </select>
      </div>
      
   </form>
   </div>
   <div class="cler"></div>
   
   <div class="notice" style="border-left:4px solid #ffd802">
				<p><?php _e( 'Hello there! What you are seeing below is a demo of Field Analytics available in Silver Edition of the plugin. If you want this and many more features, head over to sign up page here:', $textdomain ); ?></p>
			</div>
            
  	<div class="charts charts-2">
    <div class="charts-main-box">
   
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
		  data.addColumn('string', 'Options');
		  data.addColumn('number', 'Submissions');
		  data.addRows([
		  ['Male', 90],
		  ['Female', 120]
		  
		  ]);
		  // Set chart options
		  var options = {
		  is3D: true,
						 'width':400,
						 'height':300,'colors':['#4eb7b5', '#b9e2e0', '#ff6c6c', '#ff9d9d', '#ffd4d4','#e39797','#e05757','#c4a7ef','#f7edb8','#dbecc3']};
		  // Instantiate and draw our chart, passing in some options.
		  var chart = new google.visualization.PieChart(document.getElementById('field_div_sex'));
		  chart.draw(data, options);
		}
	  </script>
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
		  data.addColumn('string', 'Options');
		  data.addColumn('number', 'Submissions');
		  data.addRows([
		  ['Hip Hop', 73],
		  ['Pop', 34],
		  ['Punk', 31],
		  ['Reggae', 60],
		  ['Rap', 56],
		  ['Country', 27],
		  ['Jazz', 22],
		  ['Classical', 90],
		  ['Blues', 45],
		  ['Rock', 116]
		  ]);
		  // Set chart options
		  var options = {
		  is3D: true,
						 'width':400,
						 'height':300,'colors': ['#4eb7b5', '#b9e2e0', '#ff6c6c', '#ff9d9d', '#ffd4d4','#e39797','#e05757','#c4a7ef','#f7edb8','#dbecc3']};
		  // Instantiate and draw our chart, passing in some options.
		  var chart = new google.visualization.PieChart(document.getElementById('field_div_music'));
		  chart.draw(data, options);
		}
	  </script>
      
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
		  data.addColumn('string', 'Options');
		  data.addColumn('number', 'Submissions');
		  data.addRows([
		  ['OS X', 73],
		  ['Windows 8.1', 34],
		  ['Windows 7', 31],
		  ['Windows Vista', 60],
		  ['Ubuntu', 56],
		  ['Fedora', 27],
		  ['Others', 22]
		  ]);
		  // Set chart options
		  var options = {
		  is3D: true,
						 'width':400,
						 'height':300,'colors': ['#4eb7b5', '#b9e2e0', '#ff6c6c', '#ff9d9d', '#ffd4d4','#e39797','#e05757','#c4a7ef','#f7edb8','#dbecc3']};
		  // Instantiate and draw our chart, passing in some options.
		  var chart = new google.visualization.PieChart(document.getElementById('field_div_os'));
		  chart.draw(data, options);
		}
	  </script>
      
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
		  data.addColumn('string', 'Options');
		  data.addColumn('number', 'Submissions');
		  data.addRows([
		  ['Xbox One', 73],
		  ['PS4', 34],
		  ['Wii U', 31],
		  ['PC ftw!!', 60]
		  ]);
		  // Set chart options
		  var options = {
		  is3D: true,
						 'width':400,
						 'height':300,'colors': ['#4eb7b5', '#b9e2e0', '#ff6c6c', '#ff9d9d', '#ffd4d4','#e39797','#e05757','#c4a7ef','#f7edb8','#dbecc3']};
		  // Instantiate and draw our chart, passing in some options.
		  var chart = new google.visualization.PieChart(document.getElementById('field_div_game'));
		  chart.draw(data, options);
		}
	  </script>
      
	  <div class="main-chat crf_main_chart_odd">
	  <h1 class="chat-hedding">Sex<span class="icon"></span></h1>
	  <div class="chartdiv" id="field_div_sex"></div>
	  </div>
      
      <div class="main-chat crf_main_chart_even">
	  <h1 class="chat-hedding">Preferred Music Genre<span class="icon"></span></h1>
	  <div class="chartdiv" id="field_div_music"></div>
	  </div>
      
      <div class="main-chat crf_main_chart_odd">
	  <h1 class="chat-hedding">Your Preferred Desktop OS<span class="icon"></span></h1>
	  <div class="chartdiv" id="field_div_os"></div>
	  </div>
      
      <div class="main-chat crf_main_chart_even">
	  <h1 class="chat-hedding">Your Favorite Next-Gen Console<span class="icon"></span></h1>
	  <div class="chartdiv" id="field_div_game"></div>
	  </div>
      
   </div>
   </div>
   </div>
 
<style>
img.submitted_icon{ width: 20px !important; margin-left:40px;
height: 20px !important;
border: none !important;}
</style>