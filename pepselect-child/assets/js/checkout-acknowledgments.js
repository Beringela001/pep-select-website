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
 *
 * The validator is deliberately independent of Fluid Checkout. It fires from a
 * capture-phase guard on the Place order click and form submit (see below), so it
 * never depends on the validate-required class or on Fluid's validation pass -
 * removing a Fluid class can never silence these errors again (regression fixed
 * in 0.20.0-beta.15; the earlier stripFluidRequired approach coupled us to Fluid's
 * pass and is gone).
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

	// Clear a field's error as soon as it is satisfied. Delegated on document so it
	// keeps working across AJAX review refreshes.
	cfg.fields.forEach( function ( field ) {
		$( document ).on( 'change', '#' + field.input, function () {
			if ( isFilled( this ) ) {
				setError( field, false );
			}
		} );
	} );

	// Capture-phase guard.
	//
	// The custom validator must fire on a real Place order click regardless of how
	// Fluid Checkout selects fields for its own pass. We intercept the place-order
	// click and the form submit in the CAPTURE phase, before Fluid's handlers, run
	// our own field check, and - only when a field is invalid - block the event
	// outright (preventDefault + stopImmediatePropagation) and surface our messages.
	// Nothing here reads the validate-required class, so stripping a Fluid class can
	// never silence these errors. When every field is satisfied the guard is a
	// no-op and Fluid's normal validation (address fields, etc.) runs unchanged.
	function guardClick( event ) {
		var target = event.target;

		if ( ! target || ! target.closest || ! target.closest( '#place_order' ) ) {
			return;
		}

		if ( ! validate() ) {
			event.preventDefault();
			event.stopImmediatePropagation();
		}
	}

	function guardSubmit( event ) {
		if ( ! validate() ) {
			event.preventDefault();
			event.stopImmediatePropagation();
		}
	}

	// The button click covers the pointer path; the form submit covers Enter and any
	// programmatic submit. Document-level capture is registered once and survives
	// review refreshes; the submit guard is re-attached if the form node is replaced.
	document.addEventListener( 'click', guardClick, true );

	function attachSubmitGuard() {
		var form = document.querySelector( 'form.checkout' );

		if ( form && ! form.pepselectAckGuarded ) {
			form.addEventListener( 'submit', guardSubmit, true );
			form.pepselectAckGuarded = true;
		}
	}

	attachSubmitGuard();

	// Belt-and-suspenders: WooCommerce's own place-order event, in case a submit
	// path bypasses the capture guards above. Returning false aborts placement.
	function bind() {
		$( 'form.checkout' )
			.off( 'checkout_place_order.pepselectAck' )
			.on( 'checkout_place_order.pepselectAck', function () {
				return validate();
			} );
	}

	bind();

	// Re-bind after each AJAX review refresh. No class stripping: the capture guard
	// above is independent of whatever class Fluid uses to select fields.
	$( document.body ).on( 'updated_checkout', function () {
		attachSubmitGuard();
		bind();
	} );
}( jQuery ) );
