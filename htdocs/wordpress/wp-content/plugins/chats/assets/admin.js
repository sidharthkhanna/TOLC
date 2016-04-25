function popupAuth(button, link){
    var button = jQuery(button);

    jQuery.arcticmodal({
        type: 'ajax',
        url: link,
        ajax: {
            type: 'get',
            cache: false,
            dataType: 'html',
            success: function(data, el, responce) {
                var h = jQuery('<div id="chats_popup_form_over" class="box-modal" style="display:block;">'+responce+'</div>');
                data.body.html(h);
            }
        },
        beforeClose: function(data, el) {

        }
    });
}

function testMail(obj_mail)
{
    
    if (jQuery(obj_mail).length > 0) {
        document.test_mail_form.email_test.value = jQuery(obj_mail).val();
        document.test_mail_form.submit();
    }
}
function checkedSMTP(t)
{
	if (t.value != 2) {
        jQuery('.smtp-service').each(function(){
            jQuery(this).css('display', 'none');
			jQuery('.email-service-button').css('display', 'block');
		})
	} else {
		jQuery('.smtp-service').each(function(){
            jQuery(this).css('display', 'table-row');
			jQuery('.email-service-button').css('display', 'none');
		})
    } 
    
    document.test_mail_form.type_send.value = t.value;
}
function testSMTP() 
{
    document.test_smtp_form.test_smtp_host.value = jQuery('#smtp_host_id').val();
    document.test_smtp_form.test_smtp_user.value = jQuery('#smtp_user_id').val();
    document.test_smtp_form.test_smtp_pass.value = jQuery('#smtp_pass_id').val();
    document.test_smtp_form.test_smtp_port.value = jQuery('#smtp_port_id').val();
    document.test_smtp_form.submit();
}

function getAccessForm(t)
{
    
    var data_post = {'action' : 'getFormPostPageCategory', 'form' : t.value}
    jQuery("#loading-access-setting").css('display', 'block');
    jQuery.ajax({
        url: ajaxurl,
        type: 'post',
        data: data_post,
        success: function(data) {
             jQuery('#form-show-access').html(data);
             jQuery("#loading-access-setting").css('display', 'none');
        }
    });
}


function processAuth(button){
    var button = jQuery(button);
    var form = button.closest('form');
    var send = 1;
    if( form.find('.auth_type_field:checked').val() == 2 ){
        if( jQuery.trim(form.find('.auth_email_field').val()) == '' ){
            form.find('.auth_email_field').addClass('invalid');
            send = 0;
        }else{
            form.find('.auth_email_field').removeClass('invalid');
        }
        if( jQuery.trim(form.find('.auth_password1_field').val()) == '' ){
            form.find('.auth_password1_field').addClass('invalid');
            send = 0;
        }else{
            form.find('.auth_password1_field').removeClass('invalid');
        }
        if( jQuery.trim(form.find('.auth_password2_field').val()) == '' ){
            form.find('.auth_password2_field').addClass('invalid');
            send = 0;
        }else{
            form.find('.auth_password2_field').removeClass('invalid');
        }
    }else if( form.find('.auth_type_field:checked').val() == 1 ){
        if( jQuery.trim(form.find('.auth_email_field').val()) == '' ){
            form.find('.auth_email_field').addClass('invalid');
            send = 0;
        }else{
            form.find('.auth_email_field').removeClass('invalid');
        }
        if( jQuery.trim(form.find('.auth_password1_field').val()) == '' ){
            form.find('.auth_password1_field').addClass('invalid');
            send = 0;
        }else{
            form.find('.auth_password1_field').removeClass('invalid');
        }
    }else{
        send = 0;
    }

    if(send == 1){
        button.css('visibility','hidden');
        jQuery.ajax({
            url: form.attr('action'),
            type: 'post',
            dataType: 'json',
            data: form.serialize(),
            success: function(data) {
                if(data){
                    if(data.msg){
                        form.find('#chat_answer_msg').remove();
                        var message = '<div id="chat_answer_msg">'+data.msg+'</div>';
                        jQuery( form ).prepend( message );
                    }

                    if(data.status == 1){
                        form.find('input[type="text"]').val('');
                        form.find('input[type="password"]').val('');
                    }
                    if(data.redirect_url && data.redirect_url != ''){
                        window.location.href = data.redirect_url;
                    }
                }
                button.css('visibility','visible');
            }
        });
    }
}

function popupAuthType(button,type){
    var button = jQuery(button);
    var form = button.closest('form');
    if(type == 1){
        form.find('#tr_confirm_password').css('visibility','hidden');
        form.find('#auth_but_login').css('display','inline-block');
        form.find('#auth_but_register').css('display','none');
    }else{
        form.find('#tr_confirm_password').css('visibility','visible');
        form.find('#auth_but_login').css('display','none');
        form.find('#auth_but_register').css('display','inline-block');
    }
}

function showFullContent(button){
    var button = jQuery(button);
    if(button.hasClass('active')){
        button.removeClass('active');
        button.closest('table').find('tbody').css('display','none');
    }else{
        button.addClass('active');
        button.closest('table').find('tbody').css('display','table-row-group');
    }
}

// Uploading files
var file_frame;
var targetID;
jQuery('.upload_image_button').live('click', function( event ){
    event.preventDefault();
    targetID = event.currentTarget.dataset.targetId;

    // If the media frame already exists, reopen it.
    if ( file_frame ) {
        file_frame.open();
        return;
    }

    // Create the media frame.
    file_frame = wp.media.frames.file_frame = wp.media({
        title: jQuery( this ).data( 'uploader_title' ),
        button: {
            text: jQuery( this ).data( 'uploader_button_text' )
        },
        multiple: false  // Set to true to allow multiple files to be selected
    });

    // When an image is selected, run a callback.
    file_frame.on( 'select', function() {
        // We set multiple to false so only get one image from the uploader
        attachment = file_frame.state().get('selection').first().toJSON();

        jQuery('#'+targetID).val(attachment.url);
    });

    // Finally, open the modal
    file_frame.open();
});
