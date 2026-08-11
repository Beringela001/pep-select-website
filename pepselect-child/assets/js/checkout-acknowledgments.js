/**
 * Client-side validation for the checkout compliance Acknowledgments (M12-1).
 *
 * Shows an inline error next to each unticked required box and moves focus to
 * the first one, rather than a generic top-of-page notice. This is UX only; the
 * server-side check in inc/checkout-fields.php is the authoritative gate that
 * actually blocks placement and records acceptance, so it cannot be bypassed by
 * disabling JavaScript.
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

	function setError( field, show ) {
		var n = nodes( field );

		if ( n.error ) {
			n.error.textContent = show ? field.message : '';
			n.error.hidden = ! show;
		}

		if ( n.input ) {
			n.input.setAttribute( 'aria-invalid', show ? 'true' : 'false' );

			var row = n.input.closest( '.form-row' );

			if ( row ) {
				row.classList.toggle( 'pepselect-ack--error', show );
			}
		}
	}

	// Returns true when every required box is ticked. On failure it flags each
	// unticked box and focuses the first, then returns false to block placement.
	function validate() {
		var first = null;

		cfg.fields.forEach( function ( field ) {
			var n = nodes( field );
			var ok = !! ( n.input && n.input.checked );

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

	// Clear a box's error as soon as it is ticked.
	cfg.fields.forEach( function ( field ) {
		$( document ).on( 'change', '#' + field.input, function () {
			if ( this.checked ) {
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

	bind();
	$( document.body ).on( 'updated_checkout', bind );
}( jQuery ) );
