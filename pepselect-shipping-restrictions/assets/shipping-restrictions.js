( function ( window, document, $ ) {
	'use strict';

	var config = window.pepSelectShippingRestrictions || {};
	var allowedStates = Array.isArray( config.allowedStates ) ? config.allowedStates : [];
	var message = config.message || 'Pep Select ships only to the contiguous 48 states and Washington, D.C. Enter a shipping address within this area.';

	function postcodeIsExcluded( postcode ) {
		var digits = String( postcode || '' ).replace( /\D+/g, '' );

		if ( digits.length < 3 ) {
			return false;
		}

		var prefix = parseInt( digits.slice( 0, 3 ), 10 );

		return ( prefix >= 6 && prefix <= 9 ) ||
			( prefix >= 90 && prefix <= 98 ) ||
			prefix === 340 ||
			( prefix >= 962 && prefix <= 969 ) ||
			( prefix >= 995 && prefix <= 999 );
	}

	function addressIsExcluded( country, state, postcode ) {
		if ( country && country !== 'US' ) {
			return true;
		}

		if ( state && allowedStates.indexOf( state ) === -1 ) {
			return true;
		}

		return postcodeIsExcluded( postcode );
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

	function updateAddressWarning( prefix ) {
		var country = document.getElementById( prefix + '_country' );
		var state = document.getElementById( prefix + '_state' );
		var postcode = document.getElementById( prefix + '_postcode' );
		var row = document.getElementById( prefix + '_postcode_field' );

		if ( ! postcode || ! row ) {
			return;
		}

		var warningId = 'pepselect-' + prefix + '-shipping-area-error';
		var warning = document.getElementById( warningId );
		var excluded = addressIsExcluded(
			country ? country.value.toUpperCase() : '',
			state ? state.value.toUpperCase() : '',
			postcode.value
		);

		if ( excluded && ! warning ) {
			warning = document.createElement( 'p' );
			warning.id = warningId;
			warning.className = 'pepselect-shipping-area-error';
			warning.setAttribute( 'role', 'alert' );
			warning.setAttribute( 'aria-live', 'polite' );
			warning.textContent = message;
			row.insertBefore( warning, row.firstChild );
		}

		if ( warning ) {
			warning.hidden = ! excluded;
		}

		row.classList.toggle( 'pepselect-shipping-area-invalid', excluded );
		postcode.setAttribute( 'aria-invalid', excluded ? 'true' : 'false' );
		setDescription( postcode, warningId, excluded );
	}

	function updateWarnings() {
		updateAddressWarning( 'billing' );
		updateAddressWarning( 'shipping' );
	}

	function queueWarningUpdate() {
		window.setTimeout( updateWarnings, 0 );
	}

	document.addEventListener( 'input', function ( event ) {
		if ( /_(country|state|postcode)$/.test( event.target.id || '' ) ) {
			updateWarnings();
		}
	} );

	document.addEventListener( 'change', function ( event ) {
		if ( /_(country|state|postcode)$/.test( event.target.id || '' ) ) {
			updateWarnings();
		}
	} );

	if ( $ ) {
		$( document.body ).on( 'updated_checkout checkout_error', queueWarningUpdate );
	}

	if ( document.readyState === 'loading' ) {
		document.addEventListener( 'DOMContentLoaded', updateWarnings );
	} else {
		updateWarnings();
	}
}( window, document, window.jQuery ) );
