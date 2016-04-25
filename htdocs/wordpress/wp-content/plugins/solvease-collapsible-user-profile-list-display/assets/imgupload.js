(function($){
        $(document).ready(function(){

        // Uploading files
        var file_frame;

          jQuery('.cp_wpmu_button').on('click', function( event ){

            event.preventDefault();
              // If the media frame already exists, reopen it.
                if ( file_frame ) {
                  file_frame.open();
                  return;
                }
             // Create the media frame.
                file_frame = wp.media.frames.file_frame = wp.media({
                  title: jQuery( this ).data( 'uploader_title' ),
                  button: {
                    text: jQuery( this ).data( 'uploader_button_text' ),
                  },
                  multiple: false  // Set to true to allow multiple files to be selected
                });
                
            // When an image is selected, run a callback.
                file_frame.on( 'select', function() {
                  // We set multiple to false so only get one image from the uploader
                  attachment = file_frame.state().get('selection').first().toJSON();

                  // Do something with attachment.id and/or attachment.url here
                  // write the selected image url to the value of the #cp_meta text field
                  jQuery('#cp_meta').val('');
                  jQuery('#cp_upload_meta').val(attachment.url);
                  jQuery('#cp_upload_edit_meta').val('/wp-admin/post.php?post='+attachment.id+'&action=edit&image-editor');
                  jQuery('.cp-current-img').attr('src', attachment.url);
                });
            // Finally, open the modal
            file_frame.open();
          });
          

              // Toggle Image Type
          jQuery('input[name=img_option]').on('click', function( event ){
            var imgOption = jQuery(this).val();

            if (imgOption == 'external'){
              jQuery('#cp_upload').hide();
              jQuery('#cp_external').show();
            } else if (imgOption == 'upload'){
              jQuery('#cp_external').hide();
              jQuery('#cp_upload').show();
            }

            });
            

             // Remove Image Function
          jQuery('.edit_options').hover(function(){
            jQuery(this).stop(true, true).animate({opacity: 1}, 100);
          }, function(){
            jQuery(this).stop(true, true).animate({opacity: 0}, 100);
          });
            //
          jQuery('.remove_img').on('click', function( event ){
            jQuery(this).parent().add('.cp-current-img').fadeOut('fast', function(){jQuery(this).remove()});
            jQuery('#cp_upload_meta, #cp_upload_edit_meta, #cp_meta').val('');
          });
          
       });  
  })(jQuery);