jQuery(document).ready(function ($) {
	var $locHref = window.location.href
	if ($locHref == abandonedPlugin.href_checkout) {
		getClientData();
	}
	function getClientData() {
		$('#billing_phone').on('blur', function (e) {
			let firstName = $('#billing_first_name').val();
			let lastName = $('#billing_last_name').val();
			let phone = $('#billing_phone').val();
			let email = $('#billing_email').val();
			let productName = $('td.product-name').text();
			let price = $('td.product-total').text();
			let dNow = (new Date()).toISOString().split('.')[0];

			var data = {
				action: 'get_client_data',
				nonce:abandonedPlugin.nonce,
				firstName: firstName,
				lastName: lastName,
				phone: phone,
				email: email,
				productName: productName,
				price: price,
				dNow: dNow,
			}

			jQuery.post(abandonedPlugin.ajaxurl, data);

		})

	}
});
