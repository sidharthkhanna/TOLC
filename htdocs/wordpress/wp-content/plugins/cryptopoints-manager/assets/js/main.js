jQuery(document).ready(function($) {
	$('.datetimepicker').datetimepicker();
	$('td input.pm_fixed_payout').focus(function () {
		$(this).parent().parent('tr').find('td input.pm_random_payout').attr('disabled', 'disabled');
	}).blur(function () {
		if ($(this).val() != 0) {
			$(this).parent().parent('tr').find('td input.pm_random_payout').attr('disabled', 'disabled');
		} else {
			$(this).parent().parent('tr').find('td input.pm_random_payout').removeAttr('disabled', 'disabled');
		}
	});
	$('td input.pm_random_payout').focus(function () {
		$(this).parent().parent('tr').find('td input.pm_fixed_payout').attr('disabled', 'disabled');
	}).blur(function () {
		if ($(this).val() != 0) {
			$(this).parent().parent('tr').find('td input.pm_fixed_payout').attr('disabled', 'disabled');
		} else {
			$(this).parent().parent('tr').find('td input.pm_fixed_payout').removeAttr('disabled', 'disabled');
		}
	});
});