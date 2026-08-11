/**
 * Client-side validation for the checkout Research Purpose and the two
 * compliance Acknowledgments (M12-1).
 *
 * Shows an inline error next to each invalid required field and moves focus to
 * the first one, in page order, rather than a generic top-of-page notice. When an
 * error is shown the field's aria-describedby is pointed at the error node so a
 * screen reader announces the reason; it is removed when the error clears. This
 * is UX only; the server-side checks in inc/checkout-fields.php are the
 * authoritative gate that blocks placement and records acceptance, so nothing
 * here can be bypassed by disabling JavaScript.
 */
( function ( $ ) {
	'use strict';

	var cfg = window.pepselectAck;

	if ( ! cfg || ! cfg.fields || ! cfg.fields.length ) {
		return;
	}

	function nodes( field ) {
		return {
			input: document.getElementById( field.input ),
			error: document.getElementById( field.error ),
		};
	}

	// A checkbox is satisfied when checked; any other control (the Research
	// Purpose select) when it has a non-empty value.
	function isFilled( input ) {
		if ( ! input ) {
			return false;
		}

		if ( 'checkbox' === input.type ) {
			return input.checked;
		}

		return '' !== ( input.value || '' );
	}

	function setError( field, show ) {
		var n = nodes( field );

		if ( n.error ) {
			n.error.textContent = show ? field.message : '';
			n.error.hidden = ! show;
		}

		if ( n.input ) {
			n.input.setAttribute( 'aria-invalid', show ? 'true' : 'false' );

			if ( show ) {
				n.input.setAttribute( 'aria-describedby', field.error );
			} else {
				n.input.removeAttribute( 'aria-describedby' );
			}

			var row = n.input.closest( '.form-row' );

			if ( row ) {
				row.classList.toggle( 'pepselect-ack--error', show );
			}
		}
	}

	// Flag every invalid required field and focus the first, then return false to
	// block placement.
	function validate() {
		var first = null;

		cfg.fields.forEach( function ( field ) {
			var n = nodes( field );
			var ok = isFilled( n.input );

			setError( field, ! ok );

			if ( ! ok && ! first ) {
				first = n.input;
			}
		} );

		if ( first ) {
			first.focus();
			return false;
		}

		return true;
	}

	// Clear a field's error as soon as it is satisfied.
	cfg.fields.forEach( function ( field ) {
		$( document ).on( 'change', '#' + field.input, function () {
			if ( isFilled( this ) ) {
				setError( field, false );
			}
		} );
	} );

	// WooCommerce runs checkout_place_order handlers before submitting; returning
	// false aborts. Fluid Checkout uses the classic checkout submit flow, so this
	// fires there too. Re-bind after every AJAX review refresh in case the form is
	// re-rendered.
	function bind() {
		$( 'form.checkout' )
			.off( 'checkout_place_order.pepselectAck' )
			.on( 'checkout_place_order.pepselectAck', function () {
				return validate();
			} );
	}

	// Fluid Checkout validates any field carrying the standard validate-required
	// class and shows its own generic "This is a required field." message. Our three
	// fields have their own specific inline validation, so the class is stripped from
	// them to leave a single message source; every other checkout field keeps the
	// class and Fluid still validates it normally. Fluid keys on this class, not on
	// aria-required, which is kept for assistive tech.
	function stripFluidRequired() {
		cfg.fields.forEach( function ( field ) {
			var input = document.getElementById( field.input );
			var row = input && input.closest( '.form-row' );

			if ( row ) {
				row.classList.remove( 'validate-required' );
			}
		} );
	}

	stripFluidRequired();
	bind();
	$( document.body ).on( 'updated_checkout', function () {
		stripFluidRequired();
		bind();
	} );
}( jQuery ) );
