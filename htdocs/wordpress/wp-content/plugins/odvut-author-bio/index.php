<?php
/**
 * Plugin Name: oDvut Author Bio 
 * Plugin URI: http://www.bdask.com
 * Description: With the help of this plugin you should be able to add author's bio in any widget position in your WordPress site or blog.
 * Version: 0.01
 * Author: Mohammad Khalid Masud
 * Author URI: http://www.immyths.com
 * License: GPLv2
 */
 
add_action( 'wp_enqueue_scripts', 'prefix_add_my_stylesheet' );

function prefix_add_my_stylesheet() {
    wp_register_style( 'prefix-style', plugins_url('css/odvut_author_bio.css', __FILE__) );
    wp_enqueue_style( 'prefix-style' );
}


class wp_my_plugin extends WP_Widget {

	// constructor
    function wp_my_plugin() {
        parent::WP_Widget(false, $name = __('oDvut Author Bio', 'wp_widget_plugin') );
    }

	// widget form creation
	function form($instance) {
	
	// Check values
	if( $instance) {
		 $title = esc_attr($instance['title']);
		 $facebook = esc_attr($instance['facebook']);
		 $twitter = esc_attr($instance['twitter']);
		 $linkedin = esc_attr($instance['linkedin']);
		 $bio = esc_textarea($instance['bio']);

	} else {
		 $title = '';
		 $facebook = '';
		 $twitter = '';
		 $linkedin = '';
		 $bio = '';
	}
	?>

	<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'wp_widget_plugin'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<p>
	<label for="<?php echo $this->get_field_id('facebook'); ?>"><?php _e('Facebook Link:', 'wp_widget_plugin'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('facebook'); ?>" name="<?php echo $this->get_field_name('facebook'); ?>" type="text" value="<?php echo $facebook; ?>" />
	</p>

	<p>
	<label for="<?php echo $this->get_field_id('twitter'); ?>"><?php _e('Twitter Link:', 'wp_widget_plugin'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('twitter'); ?>" name="<?php echo $this->get_field_name('twitter'); ?>" type="text" value="<?php echo $twitter; ?>" />
	</p>

	<p>
	<label for="<?php echo $this->get_field_id('linkedin'); ?>"><?php _e('LinkedIn Link:', 'wp_widget_plugin'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('linkedin'); ?>" name="<?php echo $this->get_field_name('linkedin'); ?>" type="text" value="<?php echo $linkedin; ?>" />
	</p>
	
	<p>
	<label for="<?php echo $this->get_field_id('bio'); ?>"><?php _e('About You:', 'wp_widget_plugin'); ?></label>
	<textarea class="widefat" id="<?php echo $this->get_field_id('bio'); ?>" name="<?php echo $this->get_field_name('bio'); ?>"><?php echo $bio; ?></textarea>
	</p>
	
	<?php
	}

	// update widget
	function update($new_instance, $old_instance) {
		  $instance = $old_instance;
		  // Fields
		  $instance['title'] = strip_tags($new_instance['title']);
		  $instance['facebook'] = strip_tags($new_instance['facebook']);
		  $instance['twitter'] = strip_tags($new_instance['twitter']);
		  $instance['linkedin'] = strip_tags($new_instance['linkedin']);
		  $instance['bio'] = strip_tags($new_instance['bio']);
		 return $instance;
	}

	// display widget
	function widget($args, $instance) {
	   extract( $args );
	   // these are the widget options
	   $title = apply_filters('widget_title', $instance['title']);
	   $facebook = $instance['facebook'];
	   $twitter = $instance['twitter'];
	   $linkedin = $instance['linkedin'];
	   $bio = $instance['bio'];
	   $admin_email = get_option( 'admin_email' );
	   
 	   
	   echo $before_widget;
	   // Display the widget
	   echo '<div class="oDvutAuthorBioBox">';
	   	   
	   // Check if title is set	  
	   if ( $title ) {
		  echo $before_title . $title . $after_title;
	   }
	   
	   echo '<p class="oDvutAuthorAvater">';
	   echo get_avatar($admin_email);	  
	   echo '</p>';
	   
	   // If a user has filled out their decscription show a bio on their entries
	   
	   if($bio) {
		  echo '<p class="oDvutAuthorBioDetails">'.$bio.'</p>';
	   }
	          
	   // Check if Facebook is set
	   if( $facebook ) {
		  echo '<a class="oDvutNoBorder" target="_blank" href="'.$facebook.'"><img class="SocialIcons" src="'.plugins_url( "images/fb.png" , __FILE__ ).'" /></a>';
	   }

	   // Check if text is set
	   if( $twitter ) {
		  echo '<a class="oDvutNoBorder" target="_blank" href="'.$twitter.'"><img class="SocialIcons" src="'.plugins_url( "images/tw.png" , __FILE__ ).'" /></a>';
	   }
	   // Check if textarea is set
	   if( $linkedin ) {
		 echo '<a class="oDvutNoBorder" target="_blank" href="'.$linkedin.'"><img class="SocialIcons" src="'.plugins_url( "images/ln.png" , __FILE__ ).'" /></a>';
	   }
	   echo '</div>';
	   echo $after_widget;
	  
	}
}

// register widget
add_action('widgets_init', create_function('', 'return register_widget("wp_my_plugin");'));


/**
 * Add a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function example_add_dashboard_widgets() {

	wp_add_dashboard_widget(
                 'odvut-author-bio',         // Widget slug.
                 'oDvut Author Bio',         // Title.
                 'example_dashboard_widget_function' // Display function.
        );	
}
add_action( 'wp_dashboard_setup', 'example_add_dashboard_widgets' );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function example_dashboard_widget_function() {

	// Display whatever it is you want to show.
	$admin_email = get_option( 'admin_email' );
	
	echo'
		<table width="100%">			
			<tr>				
				<td>
							<h2>Buy Me a Cup of Coffee</h2>
							<h1>$3 ONLY<h1>
							<p>
								<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
									<input type="hidden" name="cmd" value="_s-xclick">
									<input type="hidden" name="hosted_button_id" value="AJYXKVHY8K7K4">
									<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
									<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
								</form>
							</p>
				</td>
				<td>'.get_avatar($admin_email, $size = '128').'</td>
			</tr>			
		</table>
	
	';
	
} 
 
 
 ?>