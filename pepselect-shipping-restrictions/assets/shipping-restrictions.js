( function ( window, document, $ ) {
	'use strict';

	var config = window.pepSelectShippingRestrictions || {};
	var allowedStates = Array.isArray( config.allowedStates ) ? config.allowedStates : [];
	var regionNames = config.regionNames || { AK: 'Alaska', HI: 'Hawaii', PR: 'Puerto Rico' };
	var unsupportedMessage = config.unsupportedMessage || 'Pep Select does not currently ship to this destination. Enter an address in the 50 U.S. states, Washington, D.C., or Puerto Rico.';

	function expectedRegion( postcode ) {
		var digits = String( postcode || '' ).replace( /\D+/g, '' );
		var zip;

		if ( digits.length < 5 ) {
			return '';
		}

		zip = parseInt( digits.slice( 0, 5 ), 10 );
		if ( ( zip >= 601 && zip <= 799 ) || ( zip >= 901 && zip <= 988 ) ) {
			return 'PR';
		}
		if ( zip >= 96701 && zip <= 96898 ) {
			return 'HI';
		}
		if ( zip >= 99501 && zip <= 99950 ) {
			return 'AK';
		}

		return '';
	}

	function postcodeIsUnsupported( postcode ) {
		var digits = String( postcode || '' ).replace( /\D+/g, '' );
		var zip;
		var prefix;

		if ( digits.length < 5 ) {
			return false;
		}

		zip = parseInt( digits.slice( 0, 5 ), 10 );
		prefix = parseInt( digits.slice( 0, 3 ), 10 );

		return prefix === 8 ||
			( prefix >= 90 && prefix <= 98 ) ||
			prefix === 340 ||
			( prefix >= 962 && prefix <= 966 ) ||
			prefix === 969 ||
			zip === 96799;
	}

	function addressError( country, state, postcode ) {
		var expected;

		if ( postcodeIsUnsupported( postcode ) ) {
			return unsupportedMessage;
		}

		expected = expectedRegion( postcode );
		if ( country !== 'US' || allowedStates.indexOf( state ) === -1 ) {
			return unsupportedMessage;
		}

		if ( expected && expected !== state ) {
			return 'This ZIP code belongs to ' + regionNames[ expected ] + '. Select ' + regionNames[ expected ] + ' in the State / Territory field.';
		}

		if ( [ 'AK', 'HI', 'PR' ].indexOf( state ) !== -1 && expected !== state ) {
			return 'The ZIP code does not match ' + regionNames[ state ] + '. Check the ZIP code or select the correct State / Territory.';
		}

		return '';
	}

	function setDescription( input, descriptionId, enabled ) {
		var values = ( input.getAttribute( 'aria-describedby' ) || '' ).split( /\s+/ ).filter( Boolean );
		var index = values.indexOf( descriptionId );

		if ( enabled && index === -1 ) {
			values.push( descriptionId );
		} else if ( ! enabled && index !== -1 ) {
			values.splice( index, 1 );
		}

		if ( values.length ) {
			input.setAttribute( 'aria-describedby', values.join( ' ' ) );
		} else {
			input.removeAttribute( 'aria-describedby' );
		}
	}

	function getAddressStatus( prefix ) {
		var country = document.getElementById( prefix + '_country' );
		var state = document.getElementById( prefix + '_state' );
		var postcode = document.getElementById( prefix + '_postcode' );
		var row = document.getElementById( prefix + '_postcode_field' );
		var message;

		if ( ! country || ! state || ! postcode || ! row ) {
			return null;
		}

		message = country.value && state.value && postcode.value ? addressError(
			country.value.toUpperCase(),
			state.value.toUpperCase(),
			postcode.value
		) : '';

		row.classList.toggle( 'pepselect-shipping-area-invalid', Boolean( message ) );
		postcode.setAttribute( 'aria-invalid', message ? 'true' : 'false' );

		return {
			message: message,
			postcode: postcode
		};
	}

	function updateWarnings() {
		var warningId = 'pepselect-shipping-address-error';
		var billing = getAddressStatus( 'billing' );
		var shipping = getAddressStatus( 'shipping' );
		var active = shipping && shipping.message ? shipping : billing;
		var message = active && active.message ? active.message : '';
		var target = document.getElementById( 'fc-substep__fields--shipping_method' ) ||
			document.querySelector( '.fc-shipping-method__packages' );
		var warning = document.getElementById( warningId );

		if ( message && target && ! warning ) {
			warning = document.createElement( 'p' );
			warning.id = warningId;
			warning.className = 'pepselect-shipping-area-error';
			warning.setAttribute( 'role', 'alert' );
			warning.setAttribute( 'aria-live', 'polite' );
			target.insertBefore( warning, target.firstChild );
		}

		if ( warning ) {
			warning.textContent = message;
			warning.hidden = ! message;
		}

		[ billing, shipping ].forEach( function ( status ) {
			if ( status ) {
				setDescription( status.postcode, warningId, Boolean( status.message ) );
			}
		} );

		document.querySelectorAll( '.fc-shipping-method__no-shipping-methods' ).forEach( function ( noMethods ) {
			noMethods.hidden = Boolean( message );
		} );
	}

	function queueWarningUpdate() {
		window.setTimeout( updateWarnings, 0 );
	}

	function getPlaceCountryCode( place ) {
		var components = place && ( place.addressComponents || place.address_components );
		var country;

		if ( ! Array.isArray( components ) ) {
			return '';
		}

		country = components.find( function ( component ) {
			return Array.isArray( component.types ) && component.types.indexOf( 'country' ) !== -1;
		} );

		return String( country && ( country.shortText || country.short_name ) || '' ).toUpperCase();
	}

	function setAddressField( field, value ) {
		if ( ! field ) {
			return;
		}

		field.value = value;
		if ( $ ) {
			$( field ).val( value ).trigger( 'change' );
		} else {
			field.dispatchEvent( new Event( 'change', { bubbles: true } ) );
		}
	}

	function ensurePuertoRicoAutocompleteCountryOption() {
		[ 'shipping_country', 'billing_country' ].forEach( function ( fieldId ) {
			var country = document.getElementById( fieldId );
			var option;

			if ( ! country || country.querySelector( 'option[value="PR"]' ) ) {
				return;
			}

			option = document.createElement( 'option' );
			option.value = 'PR';
			option.textContent = 'Puerto Rico';
			option.hidden = true;
			option.setAttribute( 'data-pepselect-autocomplete-only', 'true' );
			country.appendChild( option );
		} );
	}

	function normalizePuertoRicoCountryField( field ) {
		var prefix;
		var state;

		if ( ! field || field.value !== 'PR' || ! /^(billing|shipping)_country$/.test( field.id || '' ) ) {
			return;
		}

		prefix = /^billing_/.test( field.id ) ? 'billing' : 'shipping';
		state = document.getElementById( prefix + '_state' );
		field.value = 'US';
		setAddressField( state, 'PR' );
	}

	function normalizePuertoRicoAutocomplete( event ) {
		var input = event.target;
		var prefix;
		var delays = [ 0, 100, 400, 1000 ];

		if ( getPlaceCountryCode( event.detail && event.detail.place ) !== 'PR' ) {
			return;
		}

		prefix = /^billing_/.test( input.id || '' ) ? 'billing' : 'shipping';

		delays.forEach( function ( delay ) {
			window.setTimeout( function () {
				var country = document.getElementById( prefix + '_country' );
				var state = document.getElementById( prefix + '_state' );

				if ( country && country.value !== 'US' ) {
					setAddressField( country, 'US' );
				}
				if ( state && state.value !== 'PR' ) {
					setAddressField( state, 'PR' );
				}
				queueWarningUpdate();
			}, delay );
		} );
	}

	document.addEventListener( 'input', function ( event ) {
		if ( /_(country|state|postcode)$/.test( event.target.id || '' ) ) {
			updateWarnings();
		}
	} );
	document.addEventListener( 'change', function ( event ) {
		normalizePuertoRicoCountryField( event.target );
		if ( /_(country|state|postcode)$/.test( event.target.id || '' ) ) {
			updateWarnings();
		}
	} );
	document.addEventListener( 'fc_gaa_address_autocompleted', normalizePuertoRicoAutocomplete );

	if ( $ ) {
		$( document.body ).on( 'updated_checkout', function () {
			ensurePuertoRicoAutocompleteCountryOption();
			queueWarningUpdate();
		} );
		$( document.body ).on( 'checkout_error', queueWarningUpdate );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', function () {
			ensurePuertoRicoAutocompleteCountryOption();
			updateWarnings();
		} );
	} else {
		ensurePuertoRicoAutocompleteCountryOption();
		updateWarnings();
	}
}( window, document, window.jQuery ) );
