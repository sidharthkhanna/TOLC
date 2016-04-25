/**
 * FILE for all the javascript functionality for the front end of the plugin
 */

/* For front end OTP widget */

var crf_call_otp = function (event) {
    if (event.keyCode == 13) {
        var otp_key_status= jQuery("#crf_otp_login #crf_otp_kcontact").is(":visible");

        var data = {
            'action': 'crf_set_otp',
            'crf_otp_email': jQuery("#crf_otp_econtact").val()
        };

        if(otp_key_status)
        {
            data.crf_otp_key = jQuery("#crf_otp_login #crf_otp_kcontact").val();
        }


        //AJAX Loader
        /*
        var myP = document.createElement("h1");
        myP.setAttribute("id","overlay_div");
        myP.innerHTML = "LOADING..";
        myP.style.color = "red";
        //$("#overlay_div").css("display", "none");

        document.body.appendChild(myP);
        jQuery("#overlay_div").hide();*/
          
        //

        jQuery.post(ajax_url, data, function (response) {
            var responseObj = jQuery.parseJSON(response);
            if(responseObj.error==true){
                jQuery("#crf_otp_login .crf_f_notifications .crf_f_error").hide().html(responseObj.msg).slideDown('slow');
                jQuery("#crf_otp_login .crf_f_notifications .crf_f_success").hide();
                //jQuery("#crf_otp_login " + responseObj.hide).hide('slow');
            }else{
                jQuery("#crf_otp_login .crf_f_notifications .crf_f_error").hide();
                jQuery("#crf_otp_login .crf_f_notifications .crf_f_success").hide().html(responseObj.msg).slideDown('slow');
                jQuery("#crf_otp_login " + responseObj.show).show('slow');
                
                if(responseObj.reload){
                    location.reload();

                }
            }
        });
    }

};

/*Generates the request store a comment*/

var store_crf_comment = function(s_id,f_id){


                document.getElementById("submit_crf_comment").disabled=true; 

                    var comment = jQuery(".crf_comment_text").val();

                    if(comment){

                    var data = {
                            'action': 'crf_store_comment',
                            's_id': s_id,
                            'f_id' : f_id,
                            'comment' : comment
                        };

                    jQuery.post(ajax_url,data,function(response){

                        if(response){

                            jQuery('#crf_f_my_messages').append(response);
                        }
                        
                        jQuery("div.crf_f_comment").text(function(index, currentText) {
                            
                            if(currentText.length <= 30){

                                return currentText;
                            }

                            return currentText.substr(0, 30)+'...';
                        });
                            jQuery(".crf_comment_text").val('');

                    });

                }

                else{

                    jQuery(".crf_comment_text").attr('placeholder','Unable to submit blank comment!');
                    window.setTimeout(function(){jQuery(".crf_comment_text").attr('placeholder','Comment');}, 3000);
                }


       

    };


/*Generates the request store a attatchment*/

/*var  store_crf_attatchment = function(s_id,f_id) {

        var file_name = jQuery('#crf_f_browse').val();

            if(file_name){

                var file_data = jQuery('#crf_f_browse').prop('files')[0];

                var form_data = new FormData(); 

                form_data.append('file', file_data);
                form_data.append('action', 'upload_crf_image');
                form_data.append('file_name', file_name);

                jQuery.ajax({
                
                    url: ajax_url,
                    type: 'post', // point to server-side PHP script 
                    dataType: 'text',  // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,                         
                    success: function(php_script_response){

                        jQuery('#crf_f_browse').val('');

                        if(php_script_response != false){

                            var data = {
                                    'action': 'store_crf_attatchment',
                                    's_id': s_id,
                                    'f_id' : f_id,
                                    'attatchment_id' : php_script_response
                                };

                            jQuery.post(ajax_url,data,function(response){

                                jQuery('#crf_f_content3').append(response);

                                switchToTab(3);

                            });

                        }

                       else{

                        jQuery(".crf_comment_text").attr('placeholder','Invalid file format');

                        window.setTimeout(function(){jQuery(".crf_comment_text").attr('placeholder','Comment');}, 3000);

                       } 

                    }
                        
               

                });

            }

        };*/


/*All the functions to be hooked on the front end at document ready*/

jQuery(document).ready(function(){

    jQuery("div.crf_f_comment").text(function(index, currentText) {
        
        if(currentText.length <= 30){

            return currentText;
        }

        return currentText.substr(0, 30)+'...';
    });

    initJQ();

    jQuery("#crf_f_mail_notification").show('fast',function(){
        jQuery("#crf_f_mail_notification").fadeOut(3000);
    });

    jQuery(document).ajaxStart(function(){
            console.log("started query!");
            jQuery("#crf_f_loading").show();
            });
    jQuery(document).ajaxComplete(function(){
            console.log("finished query!");
            jQuery("#crf_f_loading").hide();
            }); 
});


/*launches all the functions assigned to an element on click event*/
        
function performClick(elemId,s_id,f_id) {

   var elem = document.getElementById(elemId);
   if(elem && document.createEvent) {
      var evt = document.createEvent("MouseEvents");
      evt.initEvent("click", true, false);
      elem.dispatchEvent(evt);

    }
}


/*function to generate comment delete request*/

var crf_f_delete_comment = function(c_id){

    var data = {

        'action': 'delete_comment_f',
        'id' : c_id
    };

    jQuery.post(ajax_url,data,function(response){

        jQuery("#crf_f_comment_"+c_id).hide();
    });
};


/*function to generate logout request*/

var crf_f_logout = function(){

    var data = {

        'action': 'crf_f_logout'
    }

    jQuery.post(ajax_url,data,function(response){
        location.reload();
    });
}




/*function for tabbing functionality*/
function initJQ()
{

    var currActiveIdNum = 1;
    for(var i = 1;i<=9;i++)
    {   
        if(jQuery('#crf_f_head'+i)){

            jQuery('#crf_f_head'+i).click(onHeadClickJQ);
            /*jQuery('#crf_f_head'+i).hover(
                function(){jQuery('#'+this.id).toggleClass("class","crf_f_tab_hover");},
                function(){jQuery('#'+this.id).css("class", (this.id.toString() === "head"+currActiveIdNum)?"crf_f_tab_active":"crf_f_tab_inactive");}
                );*/

        }

        else break;
    }

    jQuery('#crf_f_head'+currActiveIdNum).toggleClass("crf_f_tab_active");
    jQuery('#crf_f_content'+currActiveIdNum).css("display","block");
}



/*function for tabbing functionality*/
function onHeadClickJQ()
{

    
    var headId = this.id.toString();
    var currIdNum = parseInt(headId.slice(headId.length-1)); //Parse last character which is a number, for eg: 2 from head2.
    
    
    jQuery('#crf_f_head'+currIdNum).toggleClass("crf_f_tab_active"); //Colour the current active tab head to highlght.
    jQuery('#crf_f_content'+currIdNum).css("display","block"); //Put the respective tab in view.

    for(var i = 1;i<=9;i++)
    {
        if(jQuery('#crf_f_head'+i))
        {
            if(i==currIdNum) continue;

            jQuery('#crf_f_head'+i).removeClass("crf_f_tab_active"); //recolour other tab heads.
            jQuery('#crf_f_content'+i).css("display","none"); //Hide other tabs.

        }
        else break;
    }

}

/*function for switch to any tab*/

/*function switchToTab(ForceId)
{

    currIdNum = ForceId;   
    
    
    jQuery('#crf_f_head'+currIdNum).toggleClass("crf_f_tab_active"); //Colour the current active tab head to highlght.
    jQuery('#crf_f_content'+currIdNum).css("display","block"); //Put the respective tab in view.

    for(var i = 1;i<=9;i++)
    {
        if(jQuery('#crf_f_head'+i))
        {
            if(i==currIdNum) continue;

            jQuery('#crf_f_head'+i).removeClass("crf_f_tab_active"); //recolour other tab heads.
            jQuery('#crf_f_content'+i).css("display","none"); //Hide other tabs.

        }
        else break;
    }

}*/



function abcdef(){

    alert('hello');
}