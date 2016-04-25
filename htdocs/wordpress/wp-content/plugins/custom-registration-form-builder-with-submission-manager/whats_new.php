<?php
global $wpdb;
$textdomain = 'custom-registration-form-builder-with-submission-manager';
$crf_submissions =$wpdb->prefix."crf_submissions";
$crf_fields =$wpdb->prefix."crf_fields";
$crf_forms =$wpdb->prefix."crf_forms";
$crf_stats =$wpdb->prefix."crf_stats";
$path =  plugin_dir_url(__FILE__); 
wp_enqueue_style( 'crf_whats_new', plugin_dir_url(__FILE__) . 'css/crf_whats_new.css');
?>
    
    <div class="rm-whats-new">
        <div class="logo"><img src="<?php echo $path;?>images/whatsnew/logo.png"></div>
        <h1>Congratulations...You have successfully upgraded to <span class="rmred">v2.5!</span></h1>
        <h2>So what's new in RegistrationMagic?</h2>
        <p>As always, we are bringing a number of new features to the newest edition of RegistrationMagic. And all along, it is you - the end user, who is guiding us to every subsequent release. So if you need a new feature, want to report a bug or just speak up your mind, please use the <a href="http://registrationmagic.com/#contact" target="_blank">contact form on our website</a>.</p>
        <p>Without further delay, let's round up the new features in this release.</p>
        
        <div class="feature-block">
        <div class="left">
        <img src="<?php echo $path;?>images/whatsnew/userpage.jpg">   
        </div>
        <div class="right">
            <div class="title">
                <div class="icon">
                <img src="<?php echo $path;?>images/whatsnew/usermanager.png">
                </div>
                 <h2> User Manager</h2></div>
            <p>Welcome to the built in User Manager! This is start of something new. From its humble beginnings, we have been thinking RegistrationMagic would be incomplete without a built-in User Manager allowing more control to admins, beyond what default WP User manager offers.</p>
            <p>With upcoming releases, we shall be building upon the User Manager and roll out more features, filters and sub-categories.</p>
            <p>RegistrationMagic User Pages now show:</p>
            <ul>
            <li>Profile Fields</li>
            <li>Gravatar Image</li>
            <li>Submissions</li>
            <li>Payment History</li>
            <li>Last Login Time</li>
            <li>Custom Fields<sup>Silver Edition</sup></li>
            </ul>
        </div>
        </div>
        
        <div class="feature-block">
        <div class="right">
        <img src="<?php echo $path;?>images/whatsnew/edituserpage">   
        </div>
        <div class="left">
              <div class="title">
                <div class="icon">
                <img src="<?php echo $path;?>images/whatsnew/editinguser.png">
                </div>
                 <h2> Deactivating, Activating and Editing Users</h2></div>
            <p>Again, something not difficult without our own User pages - ability to deactivate a user without needing to delete the profile altogether. Basically, it puts users back in pending state before they had verified their respective accounts. So we are just working around WPs strengths here. (Deactivating User is currently available in Silver Edition.)</p>    
            <p>You can also batch deactivate or activate users from User table.</p>
            <p>Each User page can be edited by the admin including profile fields and the custom fields saved as part of the submission process.</p>
        </div>
        </div>
        
         <div class="feature-block">
        <div class="left">
        <img src="<?php echo $path;?>images/whatsnew/customfieldspage.jpg">   
        </div>
        <div class="right">
              <div class="title">
                <div class="icon">
                <img src="<?php echo $path;?>images/whatsnew/customfields.png">
                </div>
                 <h2> Custom Fields on User Page</h2></div>
            <p>After we added option to include profile fields in Custom Fields Manager and Submissions under WP User pages, ability to add and show any field on the User page was a natural next step. But we wanted to do it without falling into temptation of turning RegistrationMagic into a Profile maker plugin. We had to find out the best way to do it.</p>
            <p>And the solution was simple (which it usually is) - Now when you create a new custom field, in the "Advance Options" section, you will find a new checkbox "Show this on User Page". If you turn it on, the values user fills during the submission will also show on the profile pages</p>
            <p>In short, now you can have user fill multiple forms and use selected information from individual forms to be displayed on their User page. Isn't it cool!</p>
        </div>
        </div>
        
        <div class="feature-block">
        <div class="rmcomingup">
        <div class="title"><h2>So, what's comping up next?</h2>
        <h4>Here's small preview of what we are working on right now</h4>
        <img src="<?php echo $path;?>images/whatsnew/userlist.png">
        <p>LISTS</p>
        <p>We believe a part of RegistrationMagic should be dedicated to tools for admins for inviting users over to sign up for registration forms. Lists are the first step in this direction. You can create, edit, delete or merge lists. Each of these contain a list of contacts you can upload using spreadsheet, import from FaceBook or Gmail, or build from your past sign-ups. For example, a list of users who filled the form for Summer Camp 2015. These lists can then be used to send invites to users using built-in mail merge feature.</p>
        <p>You will be able to create multiple mail templates. Once a user, who you have invited, submits the form, it records it as successful conversion inside the list stats. Therefore, you can track overall list conversion percentages. This feature will be available coming March. Meanwhile, we will continue to roll out minor updates and additions.</p>
        </div>
        <a href="admin.php?page=crf_manage_forms">Continue to the plugin</a>
        </div>
        </div>
        
    </div>