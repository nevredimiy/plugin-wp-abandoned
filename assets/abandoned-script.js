jQuery(document).ready(function ($) {
	var $locHref = window.location.href

	if ($locHref == abandonedPlugin.href_checkout) {
		getClientData();
		}
	function getClientData() {
		$(abandonedPlugin.selectors.trigger_element).on(abandonedPlugin.selectors.event_el, function (e) {
			let firstName = $(abandonedPlugin.selectors.first_name).val();
			let lastName = $(abandonedPlugin.selectors.last_name).val();
			let phone = $(abandonedPlugin.selectors.phone).val();
			let email = $(abandonedPlugin.selectors.email).val();
			let productName = $(abandonedPlugin.selectors.product_name).text();
			let price = $(abandonedPlugin.selectors.price).text();
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
