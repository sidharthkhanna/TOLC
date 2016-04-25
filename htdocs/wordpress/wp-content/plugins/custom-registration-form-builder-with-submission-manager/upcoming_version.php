<?php
/*Controls custom field creation in the dashboard area*/
global $wpdb;
$textdomain = 'custom-registration-form-pro-with-submission-manager';
$crf_forms =$wpdb->prefix."crf_forms";
$path =  plugin_dir_url(__FILE__); 
?>
<style>
body {
}
.wrraper {
	width: 883px;
	margin: 0px auto;
	background-color: #ccc;
	font-family: "Roboto";
	color: #4b4b4b;
	font-size: 14px;
}
.rf-upcoming-feature-main {
	width: 100%;
}
.rm-upcoming-feature-main {
	background-color: #fff;
	float: left;
	width: 100%;
}
.rm-upcoming-feature-head .rm-upcoming-heading {
	float: left;
	font-size: 18px;
	font-family: "Roboto";
	color: rgb(78, 183, 181);
	line-height: 60px;
	padding-left: 30px;
}
a.rm-sp-cl {
	text-decoration: none;
}
.rm-heading-logo {
    margin-top: 15px;
	float: right;
}
.rm-upcoming-feature-head {
	float: left;
	width: 100%;
	border-bottom: 1px solid #e1e1e1;
}
.rm-upcoming-feature-top-area {
	float: left;
	width: 95%;
	padding: 20px;
}
.rm-uf-mid-left {
	float: left;
	width: 50%;
}
.rm-uf-mid-right {
	float: left;
	width: 50%;
}
.rm-uf-mid {
	float: left;
	width: 96%;
	padding: 20px;
}
.rm-uf-bottom {
	width: 96%;
	padding: 20px;
}
.rm-sp-cl {
	color: #ff6c6c;
}
</style>

<div class="crf-main-form">
  <div class="rm-upcoming-feature-main">
    <div class="rm-upcoming-feature-head">
      <div class="rm-upcoming-heading">Upcoming Features</div>
      <div class="rm-heading-logo"><img src="<?php echo $path;?>images/rm-form-logo.png"></div>
    </div>
    <div class="rm-upcoming-feature-top-area">Hi There!  We want to thank you for using our plugin. We were always committed to improve it and keep adding new meaningful
      features that make user registrations easier on your site. As part of this plan, we have decided to give it a bit more personality and
      whole lot of new stuff. And there is a long list features which we are working on that we will slowly roll out in coming months.<br>
      <br>
      Starting with next version, the plugin will be called <span class="rm-sp-cl">RegistrationMagic.</span> Here are some of the features you should be looking foward to: </div>
    <div class="rm-uf-mid">
      <div class="rm-uf-mid-left" > 1. Major Usability Improvements<br>
        2. Support for multiple language including special characters<br>
        3. Search and Filters for Submissions<br>
        4. Icon based interface<br>
        5. Facebook integration<br>
        6. MailChimp integration<br>
        7. Placeholder text<br>
        8. Multi User admin notifications<br>
        9. Add notes to Submissions<br>
        10. Mail Merge for user notifications<br>
        11. Comprehensive Documentation<br>
        12. Dedicated website for support<br>
        13. Squashed a lot of bugs<br>
        14. Ability to export specific submissions</div>
      <div class="rm-uf-mid-right" ><img src="<?php echo $path;?>images/rm-feature-icon.jpg"></div>
    </div>
    <div class="rm-uf-bottom">We will be releasing RegistrationMagic within first half of November, 2015 followed by some major updates during the fall including
      ability to download Submissions as PDF,  PayPal integration,  Flagging Submissions, Re-Registrations,  imporved compatibility with
      major themes, multipage forms, ability to show submission to users on front end and many more! <br>
      <br>
      If you want to see a feature in upcoming versions please email us <a href="mailto:support@registrationmagic.com" class="rm-sp-cl">support@registrationmagic.com</a><br>
      <br>
      Interface elements will largely stay the same and there should be no learning curve.<br>
      <br>
      Thank you again for using our plugin! </div>
  </div>
</div>
