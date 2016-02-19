<?php header('Access-Control-Allow-Origin: *'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
	<title>MyParcel - WooCommerce - Passdata from PostNL through MyParcel</title>
</head>
<body>
<script type="text/javascript">
/* Querystring.js ******************************************************************************/
	function Querystring(qs) { // optionally pass a querystring to parse
		this.params = new Object()
		this.get=Querystring_get

		if (qs == null)
			qs=location.search.substring(1,location.search.length)

		if (qs.length == 0) return

		qs = qs.replace(/\+/g, ' ')
		var args = qs.split('&') // parse out name/value pairs separated via &


		for (var i=0;i<args.length;i++) {
			var value;
			var pair = args[i].split('=')
			var name = unescape(pair[0])

			if (pair.length == 2)
				value = unescape(pair[1])
			else
				value = name

			this.params[name] = value
		}
	}

	function Querystring_get(key, default_) {
		// This silly looking line changes UNDEFINED to NULL
		if (default_ == null) default_ = null;

		var value=this.params[key]
		if (value==null) value=default_;

		return value
	}

	window.onload = passData;
	function passData()
	{
		var name;
		var street;
		var houseNr;
		var houseNrAdd;
		var postalCodeNum;
		var postalCodeAlpha;
		var city;

		var qs = new Querystring();
		if (qs.get("action") == "confirm")
		{
			name            = qs.get('name');
			street          = qs.get('street');
			houseNr         = qs.get('housenumber');
			houseNrAdd      = qs.get('housenumberadd');
			postalCodeNum   = qs.get('postalcodenum');
			postalCodeAlpha = qs.get('postalcodealpha');
			city            = qs.get('city');

			if(parent.parent.window.opener)
			{
				var shopname = name.split('&nbsp;&nbsp;');
				if(shopname.length == 2) shopname = shopname[1];

				// NOTE: hieronder specificeert u de naam van het formulier
				// in dit voorbeeld: 'checkout_shipping'
				var formulier = parent.parent.window.opener.document.forms['checkout'];

				// NOTE: hieronder stopt u de variabelen in uw formulier
				// formulier['ship_to_different_address'].checked	= true;
				formulier['shipping_company'].value             = shopname;
				formulier['shipping_postcode'].value			= postalCodeNum + postalCodeAlpha;
				formulier['shipping_street_name'].value			= street;
				formulier['shipping_house_number'].value		= houseNr;
				formulier['shipping_house_number_suffix'].value	= houseNrAdd.replace("-", ""); // verwijder eerste streepje van toevoeging!
				formulier['shipping_city'].value				= city;
				formulier['myparcel_is_pakjegemak'].value		= 'yes';

				// store address separately for more reliable access
				var pgaddress = {
					name:            shopname,
					street:          street,
					house_number:    houseNr,
					number_addition: houseNrAdd,
					postcode:        postalCodeNum + postalCodeAlpha,
					town:            city
				};

				formulier['myparcel_pgaddress'].value			= JSON.stringify( pgaddress );

				// NOTE: de locatie slaan we op als 'bedrijf'. De naam van de persoon die afhaalt, dient door de klant ingevuld te worden in een 'naam' veld.

				//Kopieer billing naam als deze velden leeg zijn
				if (formulier['shipping_first_name'].value == '' && formulier['shipping_last_name'].value  == '') {
					formulier['shipping_first_name'].value = formulier['billing_first_name'].value;
					formulier['shipping_last_name'].value  = formulier['billing_last_name'].value;
				}

			}
		}
		parent.parent.window.close();
	}
</script>
</body>
</html>