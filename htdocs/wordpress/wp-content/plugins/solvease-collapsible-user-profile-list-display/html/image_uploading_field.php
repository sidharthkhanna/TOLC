   <div id="cp_container">
                <h3><?php _e( 'Custom User Profile Photo', 'custom-user-profile-photo' ); ?></h3>

                <table class="form-table">
                    <tr>
                        <th><label for="cp_meta"><?php _e( 'Profile Photo', 'custom-user-profile-photo' ); ?></label></th>
                        <td>
                            <!-- Outputs the image after save -->
                            <div id="current_img">
                                <?php if($cp_upload_url): ?>
                                    <img src="<?php echo esc_url( $cp_upload_url ); ?>" class="cp-current-img" height="200" width="300">
                                    <div class="edit_options uploaded">
                                        <a class="remove_img"><span>Remove</span></a>
                                        <a href="<?php echo $cp_upload_edit_url; ?>" class="edit_img" target="_blank"><span>Edit</span></a>
                                    </div>
                                <?php elseif($cp_url) : ?>
                                    <img src="<?php echo esc_url( $cp_url ); ?>" class="cp-current-img" height="200" width="300">
                                    <div class="edit_options single">
                                        <a class="remove_img"><span>Remove</span></a>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Select an option: Upload to WPMU or External URL -->
                            <div id="cp_options">
                                <input type="radio" id="upload_option" name="img_option" value="upload" class="tog" checked> 
                                <label for="upload_option">Upload New Image</label><br>
                            </div>

                            <!-- Hold the value here if this is a WPMU image -->
                            <div id="cp_upload">
                                <input type="hidden" name="cp_upload_meta" id="cp_upload_meta" value="<?php echo esc_url_raw( $cp_upload_url ); ?>" class="hidden" />
                                <input type="hidden" name="cp_upload_edit_meta" id="cp_upload_edit_meta" value="<?php echo esc_url_raw( $cp_upload_edit_url ); ?>" class="hidden" />
                                <input type='button' class="cp_wpmu_button button-primary" value="<?php _e( $btn_text, 'custom-user-profile-photo' ); ?>" id="uploadimage"/><br />
                            </div>  
                            <!-- Outputs the text field and displays the URL of the image retrieved by the media uploader -->
                            <div id="cp_external">
                                <input type="text" name="cp_meta" id="cp_meta" value="<?php echo esc_url_raw( $cp_url ); ?>" class="regular-text" />
                            </div>
                            <!-- Outputs the save button -->
                            <span class="description"><?php _e( 'Upload a custom photo for your user profile or use a URL to a pre-existing photo.', 'custom-user-profile-photo' ); ?></span>
                            <p class="description"><?php _e('Update Profile to save your changes.', 'custom-user-profile-photo'); ?></p>
                        </td>
                      </tr>
                    </table><!-- end form-table -->
              </div> <!-- end #cp_container -->