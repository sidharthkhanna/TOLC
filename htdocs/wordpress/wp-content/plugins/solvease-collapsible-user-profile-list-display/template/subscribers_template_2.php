
<?php
$blogusers = get_users( 'blog_id=3&orderby=nicename&role=subscriber' );?>
 <!--Array of WP_User objects.-->   

<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<?php
    $counter = 0;
foreach ( $blogusers as $user ) {
    $all_meta_for_user  =  get_user_meta($user->ID);
    $description        =  $all_meta_for_user['description'][0];
    $nickname           =  $all_meta_for_user['nickname'][0];
    $twitter            =  $all_meta_for_user['twitter'][0];
    $twitter_name       =  $all_meta_for_user['twitter_name'][0];
    $facebook           =  $all_meta_for_user['facebook'][0];
    $facebook_name      =  $all_meta_for_user['facebook_name'][0];
    $linkIn             =  $all_meta_for_user['linkIn'][0];
    $linkIn_name        =  $all_meta_for_user['linkIn_name'][0];
    $occupation         =  $all_meta_for_user['occupation'][0];
    $company_name       =  $all_meta_for_user['company_name'][0];
    $education          =  $all_meta_for_user['education'][0];
    $image              =  get_cp_meta( $user->ID, $size );
    $counter =$counter+1;
    ?>

          <div class="panel panel-default">
              <div class="panel-heading" role="tab" id="a<?php echo esc_html( $nickname); ?>">

                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#<?php echo esc_html($nickname); ?>" aria-expanded="false" aria-controls="<?php echo esc_html($nickname); ?>">
                <div class="row">
                    <div class="col-sm-2">
                         <span class="glyphicon glyphicon-plus"></span>
                    </div>
                    <div class="col-sm-8 user_name">
                       <?php echo esc_html($user->display_name);?>
                    </div>
                    <div class="col-sm-2 user_icon">
                        <span class="glyphicon glyphicon-user"></span>
                    </div>
                </div>
                </a>

            </div>
            <div id="<?php echo esc_html($nickname); ?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="a<?php echo esc_html($nickname); ?>">
              <div class="panel-body">
                   <div class="upper_row">
                          <div class="row">
                            <div class="col-sm-5">
                                <img src="<?php echo esc_html($image); ?>" class="cp-current-img">
                            </div>
                            <div class="col-sm-7">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <?php if(!empty($user->display_name)){?> Name 
                                    </div>
                                    <div class="col-sm-2">
                                        :
                                    </div>
                                    <div class="col-sm-6">
                                        <?php echo esc_html($user->display_name);}?>
                                    </div>
                                </div><!-- User Name -->
                                
                                <div class="row">
                                    <div class="col-sm-4">
                                        <?php if(!empty($occupation )){?> Occupation
                                    </div>
                                    <div class="col-sm-2">
                                        :
                                    </div>
                                    <div class="col-sm-6">
                                        <?php echo esc_html($occupation) ;} ?>
                                    </div>
                                </div><!-- User Occupation-->
                                <div class="row">
                                    <div class="col-sm-4">
                                        <?php if(!empty($company_name)){?> Company Name
                                    </div>
                                    <div class="col-sm-2">
                                        :
                                    </div>
                                    <div class="col-sm-6">
                                        <?php echo esc_html($company_name);} ?>
                                    </div>
                                </div><!-- User Company Name-->
                                <div class="row">
                                    <div class="col-sm-4">
                                        <?php if(!empty($education)){?> Education
                                    </div>
                                    <div class="col-sm-2">
                                        :
                                    </div>
                                    <div class="col-sm-6">
                                        <?php echo esc_html($education);} ;?>
                                     </div><!-- User Education-->
                                  </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <?php if(!empty($user->user_email)){?> Email  
                                    </div>
                                    <div class="col-sm-2">
                                        :
                                    </div>
                                    <div class="col-sm-6">
                                        <?php echo esc_html($user->user_email);} ?> 
                                    </div>
                                </div><!-- User Email -->

                                <div class="row">
                                    <div class="col-sm-4">
                                       <?php if(!empty($user->user_url)){?> Website 
                                    </div>
                                    <div class="col-sm-2">
                                        :
                                    </div>
                                    <div class="col-sm-6">
                                        <a href="<?php echo esc_url($user->user_url); ?>"target="_blank" ><?php echo esc_url($user->user_url);} ?></a>
                                    </div>
                                </div><!-- User Website -->

                                <div class="row">
                                    <div class="col-sm-4">
                                       <?php if(!empty($twitter)){?> Twitter
                                    </div>
                                    <div class="col-sm-2">
                                        :
                                    </div>
                                    <div class="col-sm-6">
                                        <a href="<?php echo esc_url($twitter); ?>" target="_blank"><?php echo esc_url($twitter_name);} ?></a>
                                    </div>
                                </div><!-- User Twitter -->

                               <div class="row">
                                    <div class="col-sm-4">
                                        <?php if(!empty($facebook)){?> Facebook
                                    </div>
                                    <div class="col-sm-2">
                                        :
                                    </div>
                                    <div class="col-sm-6">
                                        <a href="<?php echo esc_url($facebook); ?>" target="_blank"><?php echo esc_url($facebook_name);} ?></a>
                                    </div>
                                </div><!-- User Facebook -->
                                <div class="row">
                                    <div class="col-sm-4">
                                        <?php if(!empty($linkIn)){?> linkIn
                                    </div>
                                    <div class="col-sm-2">
                                        :
                                    </div>
                                    <div class="col-sm-6">
                                        <a href="<?php echo esc_url($linkIn); ?>" target="_blank"><?php echo esc_url($linkIn_name);} ?></a>
                                    </div>
                                </div><!-- User LinkedIn-->
                                
                          </div>
                        </div>
                  <div class="lower_row">
                            <div class="row">
                              <div class="col-sm-12">
                                 <?php if(!empty($description)){?></br>      <b>About Me  : </b><?php echo esc_url( $description); }?>   
                              </div>  
                            </div>
                 </div>
              </div>
            </div>
          </div>

        <?php }?>
        </div>
        <?php

         function get_cp_meta( $user_id, $size ) {

            //allow the user to specify the image size
            if (!$size){
                $size = 'medium'; // Default image size if not specified.
            }
            if(!$user_id){
                $user_id = $post->post_author;
            }

            // get the custom uploaded image
            $attachment_upload_url = esc_url( get_the_author_meta( 'cp_upload_meta', $user_id ) );

            // get the external image
            $attachment_ext_url = esc_url( get_the_author_meta( 'cp_meta', $user_id ) );
            $attachment_url = '';
            if($attachment_upload_url){
                $attachment_url = $attachment_upload_url;
            } elseif($attachment_ext_url) {
                $attachment_url = $attachment_ext_url;
            }

            // grabs the id from the URL using Frankie Jarretts function
            $attachment_id = get_attachment_image_by_url( $attachment_url );

            // retrieve the thumbnail size of our image
            $image_thumb = wp_get_attachment_image_src( $attachment_id, $size );

            // return the image thumbnail
            return $image_thumb[0];
            }
        
        
        
            /**
             * Get Attachment Url
             * @global type $wpdb
             * @param type $url
             * @return type
             */
              function get_attachment_image_by_url( $url ) {

                // Split the $url into two parts with the wp-content directory as the separator.
                $parse_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );

                // Get the host of the current site and the host of the $url, ignoring www.
                $this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
                $file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );

            // Return nothing if there aren't any $url parts or if the current host and $url host do not match.
            if ( !isset( $parse_url[1] ) || empty( $parse_url[1] ) || ( $this_host != $file_host ) ) {
                return;
            }
            // Now we're going to quickly search the DB for any attachment GUID with a partial path match.
            global $wpdb;
            $prefix     = $wpdb->prefix;
            $attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM " . $prefix . "posts WHERE guid RLIKE %s;", $parse_url[1] ) );
            // Returns null if no attachment is found.
            return $attachment[0];
            }
            ?>



