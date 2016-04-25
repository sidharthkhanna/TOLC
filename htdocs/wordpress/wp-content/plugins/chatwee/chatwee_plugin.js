jQuery(document).ready(function() {

	var isRequest = 0;

	jQuery("#searchBox").keyup(function() {
		
		var result = jQuery("#searchResults");
		var loader = jQuery("#ajaxLoader");
		var dataTable = jQuery(".dataTableSearch");
		var $value = jQuery(this).val();
		
		if(isRequest)
			isRequest.abort();
	
		isRequest = jQuery.ajax({
		
			type:"POST",
			beforeSend:function() {
				loader.show();
			},
			data: {
				action: 'chatweePlugin',search_name:$value
			},
			url:"admin-ajax.php",
			success: function(data) {
				
				loader.hide();
				dataTable.fadeIn();
				result.html(data);
			}
	
		});

	});
	
});
	
function setAsAdmin(id) {
	
	var element = jQuery("#"+id);
	
	jQuery(".dataTable").show();
	jQuery(".noMods").hide();
	
	element.html("Remove");
	element.attr('onclick','removeAdmin(this.id)');
	element.attr('id',"mod_"+id);
	var row = element.parent().parent();
				jQuery('.dataTable tbody').append(row);		
	if(jQuery("#searchResults").is(':empty'))
		jQuery(".dataTableSearch").hide();
		jQuery.ajax({
			type:"POST",
			data: {
			action: 'chatweePluginAddUser',"id":id
			},	
			url:"admin-ajax.php"
	
		});
	}
	
function removeAdmin(id) {
			
	jQuery("#"+id).parent().parent().remove();
	if(jQuery(".allModerators").children().length == 0){
		jQuery(".dataTable").hide();
		jQuery(".noMods").show();

	}
		jQuery.ajax({
			type:"POST",
			data: {
			action: 'chatweePluginRemoveUser',"id":id
			},	
			url:"admin-ajax.php",
	
		});
	}
  