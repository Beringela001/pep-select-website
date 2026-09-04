( function ( window, document, $ ) {
	'use strict';

	var config = window.pepSelectShippingRestrictions || {};
	var allowedStates = Array.isArray( config.allowedStates ) ? config.allowedStates : [];
	var regionNames = config.regionNames || { AK: 'Alaska', HI: 'Hawaii', PR: 'Puerto Rico' };
	var unsupportedMessage = config.unsupportedMessage || 'Pep Select does not currently ship to this destination. Enter an address in the 50 U.S. states, Washington, D.C., or Puerto Rico.';
	var incompleteMessage = config.incompleteMessage || 'Enter a complete street address, including the street number and street name.';
	var checkingMessage = config.checkingMessage || 'Verifying delivery address…';
	var verifiedMessage = config.verifiedMessage || 'Delivery address verified.';
	var validationState = {};
	var validationTimers = {};

	function addressLineIsComplete( addressLine ) {
		var normalized = String( addressLine || '' ).trim().replace( /\s+/g, ' ' );
		return /\d/.test( normalized ) && /[A-Za-z]{2,}/.test( normalized ) && normalized.split( ' ' ).length >= 2;
	}

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
		var address1 = document.getElementById( prefix + '_address_1' );
		var country = document.getElementById( prefix + '_country' );
		var state = document.getElementById( prefix + '_state' );
		var postcode = document.getElementById( prefix + '_postcode' );
		var addressRow = document.getElementById( prefix + '_address_1_field' );
		var postcodeRow = document.getElementById( prefix + '_postcode_field' );
		var invalidInput;
		var message;

		if ( ! address1 || ! country || ! state || ! postcode || ! addressRow || ! postcodeRow ) {
			return null;
		}

		message = '';
		invalidInput = postcode;
		if ( address1.value && ! addressLineIsComplete( address1.value ) ) {
			message = incompleteMessage;
			invalidInput = address1;
		} else if ( country.value && state.value && postcode.value ) {
			message = addressError( country.value.toUpperCase(), state.value.toUpperCase(), postcode.value );
		}

		addressRow.classList.toggle( 'pepselect-shipping-area-invalid', Boolean( message ) && invalidInput === address1 );
		postcodeRow.classList.toggle( 'pepselect-shipping-area-invalid', Boolean( message ) && invalidInput === postcode );
		address1.setAttribute( 'aria-invalid', message && invalidInput === address1 ? 'true' : 'false' );
		postcode.setAttribute( 'aria-invalid', message && invalidInput === postcode ? 'true' : 'false' );

		return {
			address1: address1,
			postcode: postcode,
			message: message,
			input: invalidInput
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
				setDescription( status.address1, warningId, Boolean( status.message ) && status.input === status.address1 );
				setDescription( status.postcode, warningId, Boolean( status.message ) && status.input === status.postcode );
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

	function readAddress( prefix ) {
		var fields = {};
		var ids = [ 'address_1', 'address_2', 'city', 'state', 'postcode', 'country' ];

		ids.forEach( function ( name ) {
			fields[ name ] = document.getElementById( prefix + '_' + name );
		} );
		if ( ! fields.address_1 || ! fields.city || ! fields.state || ! fields.postcode || ! fields.country ) {
			return null;
		}

		return {
			prefix: prefix,
			fields: fields,
			address_1: fields.address_1.value.trim(),
			address_2: fields.address_2 ? fields.address_2.value.trim() : '',
			city: fields.city.value.trim(),
			state: fields.state.value.trim().toUpperCase(),
			postcode: fields.postcode.value.trim(),
			country: fields.country.value.trim().toUpperCase()
		};
	}

	function addressIsComplete( address ) {
		return Boolean( address && address.address_1 && address.city && address.state && address.postcode && address.country );
	}

	function addressFingerprint( address ) {
		return [ 'address_1', 'address_2', 'city', 'state', 'postcode', 'country' ].map( function ( key ) {
			return String( address && address[ key ] || '' ).trim().replace( /\s+/g, ' ' ).toUpperCase();
		} ).join( '|' );
	}

	function activeAddressPrefixes() {
		var shipping = readAddress( 'shipping' );
		var billing = readAddress( 'billing' );
		var same = document.getElementById( 'billing_same_as_shipping' );
		var prefixes = [];

		if ( shipping && ( shipping.address_1 || shipping.city || shipping.postcode ) ) {
			prefixes.push( 'shipping' );
			if ( billing && same && ! same.checked ) {
				prefixes.push( 'billing' );
			}
		} else if ( billing ) {
			prefixes.push( 'billing' );
		}

		return prefixes;
	}

	function validationPanel( prefix ) {
		var id = 'pepselect-' + prefix + '-address-verification';
		var panel = document.getElementById( id );
		var postcodeRow;

		if ( panel ) {
			return panel;
		}
		postcodeRow = document.getElementById( prefix + '_postcode_field' );
		if ( ! postcodeRow || ! postcodeRow.parentNode ) {
			return null;
		}

		panel = document.createElement( 'div' );
		panel.id = id;
		panel.className = 'pepselect-address-verification';
		panel.hidden = true;
		panel.setAttribute( 'aria-live', 'polite' );
		postcodeRow.parentNode.insertBefore( panel, postcodeRow.nextSibling );
		return panel;
	}

	function formatSuggestion( suggestion ) {
		return [ suggestion.address_1, suggestion.address_2, suggestion.city, suggestion.state, suggestion.postcode ]
			.filter( Boolean )
			.join( ', ' );
	}

	function applySuggestion( prefix, suggestion ) {
		[ 'address_1', 'address_2', 'city', 'state', 'postcode', 'country' ].forEach( function ( name ) {
			var field = document.getElementById( prefix + '_' + name );
			if ( field && Object.prototype.hasOwnProperty.call( suggestion, name ) ) {
				setAddressField( field, suggestion[ name ] );
			}
		} );
		validationState[ prefix ] = { status: 'pending', fingerprint: '' };
		validateAddress( prefix, true );
	}

	function renderValidationState( prefix ) {
		var state = validationState[ prefix ] || {};
		var panel = validationPanel( prefix );
		var address = readAddress( prefix );
		var row = document.getElementById( prefix + '_state_field' );
		var message;
		var button;

		if ( ! panel ) {
			return;
		}
		panel.replaceChildren();
		panel.className = 'pepselect-address-verification pepselect-address-verification--' + ( state.status || 'idle' );
		panel.hidden = ! state.status || state.status === 'idle' || state.status === 'incomplete';
		if ( panel.hidden ) {
			if ( row ) {
				row.classList.remove( 'pepselect-shipping-area-invalid' );
			}
			return;
		}

		message = document.createElement( 'p' );
		message.className = 'pepselect-address-verification__message';
		message.textContent = state.message || '';
		panel.appendChild( message );

		if ( state.suggested && state.suggested.address_1 ) {
			message.textContent = ( state.message || 'Review the verified address.' ) + ' Suggested: ' + formatSuggestion( state.suggested ) + '.';
			button = document.createElement( 'button' );
			button.type = 'button';
			button.className = 'button pepselect-address-verification__apply';
			button.textContent = 'Use verified address';
			button.addEventListener( 'click', function () {
				applySuggestion( prefix, state.suggested );
			} );
			panel.appendChild( button );
		}

		if ( row ) {
			row.classList.toggle( 'pepselect-shipping-area-invalid', state.status === 'invalid' );
		}
		if ( address && address.fields.state ) {
			address.fields.state.setAttribute( 'aria-invalid', state.status === 'invalid' ? 'true' : 'false' );
		}
	}

	function updatePlaceOrderGate() {
		var prefixes = activeAddressPrefixes();
		var ready = prefixes.length > 0 && prefixes.every( function ( prefix ) {
			var address = readAddress( prefix );
			var state = validationState[ prefix ] || {};
			return addressIsComplete( address ) && state.status === 'valid' && state.fingerprint === addressFingerprint( address );
		} );

		document.querySelectorAll( '#place_order, button[name="woocommerce_checkout_place_order"]' ).forEach( function ( button ) {
			if ( ! ready ) {
				if ( ! button.disabled ) {
					button.dataset.pepAddressDisabled = '1';
					button.disabled = true;
				}
				button.setAttribute( 'aria-disabled', 'true' );
			} else if ( button.dataset.pepAddressDisabled === '1' ) {
				button.disabled = false;
				button.removeAttribute( 'aria-disabled' );
				delete button.dataset.pepAddressDisabled;
			}
		} );
	}

	function setValidationState( prefix, state ) {
		validationState[ prefix ] = state;
		renderValidationState( prefix );
		updatePlaceOrderGate();
	}

	function validateAddress( prefix, immediate ) {
		var address = readAddress( prefix );
		var fingerprint = addressFingerprint( address );
		var localMessage;
		var body;

		window.clearTimeout( validationTimers[ prefix ] );
		if ( ! addressIsComplete( address ) ) {
			setValidationState( prefix, { status: 'incomplete', fingerprint: fingerprint } );
			return;
		}
		if ( ! addressLineIsComplete( address.address_1 ) ) {
			setValidationState( prefix, { status: 'invalid', fingerprint: fingerprint, message: incompleteMessage } );
			return;
		}
		localMessage = addressError( address.country, address.state, address.postcode );
		if ( localMessage ) {
			setValidationState( prefix, { status: 'invalid', fingerprint: fingerprint, message: localMessage } );
			return;
		}
		if ( validationState[ prefix ] && validationState[ prefix ].status === 'valid' && validationState[ prefix ].fingerprint === fingerprint ) {
			updatePlaceOrderGate();
			return;
		}

		setValidationState( prefix, { status: 'checking', fingerprint: fingerprint, message: checkingMessage } );
		validationTimers[ prefix ] = window.setTimeout( function () {
			body = new URLSearchParams();
			body.set( 'action', 'pepselect_validate_checkout_address' );
			body.set( 'nonce', config.nonce || '' );
			[ 'address_1', 'address_2', 'city', 'state', 'postcode', 'country' ].forEach( function ( name ) {
				body.set( name, address[ name ] || '' );
			} );

			window.fetch( config.ajaxUrl, {
				method: 'POST',
				credentials: 'same-origin',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' },
				body: body.toString()
			} ).then( function ( response ) {
				return response.json();
			} ).then( function ( response ) {
				var current = readAddress( prefix );
				var result = response && response.success ? response.data : null;
				if ( addressFingerprint( current ) !== fingerprint ) {
					return;
				}
				if ( result && result.valid ) {
					setValidationState( prefix, { status: 'valid', fingerprint: fingerprint, message: verifiedMessage } );
				} else {
					setValidationState( prefix, {
						status: 'invalid',
						fingerprint: fingerprint,
						message: result && result.message ? result.message : 'We could not verify this delivery address. Check it and try again.',
						suggested: result && result.suggested ? result.suggested : null
					} );
				}
			} ).catch( function () {
				if ( addressFingerprint( readAddress( prefix ) ) === fingerprint ) {
					setValidationState( prefix, { status: 'invalid', fingerprint: fingerprint, message: 'Address verification is temporarily unavailable. Try again.' } );
				}
			} );
		}, immediate ? 0 : 700 );
	}

	function validateActiveAddresses( immediate ) {
		activeAddressPrefixes().forEach( function ( prefix ) {
			validateAddress( prefix, immediate );
		} );
		updatePlaceOrderGate();
	}

	function invalidateAddress( prefix ) {
		var address = readAddress( prefix );
		if ( ! address ) {
			return;
		}
		setValidationState( prefix, { status: 'pending', fingerprint: addressFingerprint( address ), message: checkingMessage } );
		validateAddress( prefix, false );
	}

	document.addEventListener( 'input', function ( event ) {
		if ( /_(address_1|address_2|city|country|state|postcode)$/.test( event.target.id || '' ) ) {
			updateWarnings();
			invalidateAddress( /^billing_/.test( event.target.id ) ? 'billing' : 'shipping' );
		}
	} );
	document.addEventListener( 'change', function ( event ) {
		normalizePuertoRicoCountryField( event.target );
		if ( /_(address_1|address_2|city|country|state|postcode)$/.test( event.target.id || '' ) ) {
			updateWarnings();
			invalidateAddress( /^billing_/.test( event.target.id ) ? 'billing' : 'shipping' );
		}
		if ( event.target.id === 'billing_same_as_shipping' ) {
			validateActiveAddresses( true );
		}
	} );
	document.addEventListener( 'fc_gaa_address_autocompleted', normalizePuertoRicoAutocomplete );
	document.addEventListener( 'fc_gaa_address_autocompleted', function ( event ) {
		window.setTimeout( function () {
			invalidateAddress( /^billing_/.test( event.target.id || '' ) ? 'billing' : 'shipping' );
		}, 450 );
	} );
	document.addEventListener( 'submit', function ( event ) {
		if ( event.target && event.target.matches( 'form.checkout' ) ) {
			var prefixes = activeAddressPrefixes();
			var ready = prefixes.length > 0 && prefixes.every( function ( prefix ) {
				var address = readAddress( prefix );
				var state = validationState[ prefix ] || {};
				return state.status === 'valid' && state.fingerprint === addressFingerprint( address );
			} );
			if ( ! ready ) {
				event.preventDefault();
				event.stopImmediatePropagation();
				validateActiveAddresses( true );
			}
		}
	}, true );

	if ( $ ) {
		$( document.body ).on( 'updated_checkout', function () {
			ensurePuertoRicoAutocompleteCountryOption();
			queueWarningUpdate();
			window.setTimeout( function () {
				validateActiveAddresses( false );
			}, 50 );
		} );
		$( document.body ).on( 'checkout_error', queueWarningUpdate );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', function () {
			ensurePuertoRicoAutocompleteCountryOption();
			updateWarnings();
			validateActiveAddresses( false );
		} );
	} else {
		ensurePuertoRicoAutocompleteCountryOption();
		updateWarnings();
		validateActiveAddresses( false );
	}
}( window, document, window.jQuery ) );
