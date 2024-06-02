jQuery(document).ready(function ($) {
	var $locHref = window.location.href
	if ($locHref == myPlugin.href_checkout) {
		getClientData();
	}
	function getClientData() {
		$('#billing_phone').on('blur', function (e) {
			let firstName = $('#billing_first_name').val();
			let lastName = $('#billing_last_name').val();
			let phone = $('#billing_phone').val();
			let email = $('#billing_email').val();
			let dNow = (new Date()).toISOString().split('.')[0];

			var data = {
			action: 'get_client_data',
			firstName: firstName,
			lastName: lastName,
			phone: phone,
			email: email,
			dNow: dNow,
			}

			jQuery.post(myPlugin.ajaxurl, data);

		})

	}
});