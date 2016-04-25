jQuery(document).ready(function ($) {
	window.fbAsyncInit = function () {
		FB.init({
			appId: '569695146484890',
			version: 'v2.0',
			status: false,
			cookie: false,
			xfbml: true
		});

		FB.Event.subscribe('edge.create', function (response) {
			fb_point = parseInt(fb_point);
			var current_point = parseInt($('.widget_pm_pointswidget .pm_points_display').html().substr(1));
			
			$('.widget_pm_pointswidget .pm_points_display').html('$' + (current_point + fb_point));

			if (response) {
				$.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {
						"action": "social_add_points",
						"uid": uid,
						"points": fb_point,
						"type": "facebook",
						"negative": "false"
					}
				});
			} else {
				console.log('error');
			}
		});
		FB.Event.subscribe('edge.remove', function (response) {
			fb_point = parseInt(fb_point);
			var current_point = parseInt($('.widget_pm_pointswidget .pm_points_display').html().substr(1));

			$('.widget_pm_pointswidget .pm_points_display').html('$' + (current_point - fb_point));

			if (response) {
				$.ajax({
					type: 'POST',
					url: ajaxurl,
					data: {
						"action": "social_add_points",
						"uid": uid,
						"points": fb_point,
						"type": "facebook",
						"negative": "true"
					}
				});
			} else {
				console.log('error');
			}
		});
	};

	// Load the SDK
	(function (d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s);
		js.id = id;
		js.src = "//connect.facebook.net/en_US/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

	$('#twitter').twitterbutton({
		layout: 'horizontal',
		ontweet: function () {
			jQuery.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					"action": "social_add_points",
					"uid": uid,
					"points": tw_point,
					"type": "twitter",
					"negative": "false"
				}
			});
		}
	});
});

(function (d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s);
	js.id = id;
	js.src = "//connect.facebook.net/en_US/sdk.js&appId=569695146484890&version=v2.0";
	fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));