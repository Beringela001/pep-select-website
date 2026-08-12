/**
 * Checkout redemption presentation (M12-6).
 *
 * YITH Points & Rewards renders its classic redemption form
 * (form.ywpar_apply_discounts) at the top of the checkout - unstyled, out of
 * place, and framed in points. This keeps YITH's form fully functional but
 * hidden, and presents a themed, dollar-framed redemption card inside the
 * order-summary panel (below the line items, above the totals), matching where
 * the reference checkout places redemption.
 *
 * SAFETY: the value POSTed to YITH stays in points. This applies the customer's
 * full available balance - ywpar_input_points is left at its own server-set
 * maximum (ywpar_points_max) - so no points<->dollars conversion is done on the
 * posted amount. The card's Apply button only triggers YITH's own hidden Apply
 * control, which reads the untouched points field. Remove is YITH's native
 * control in the totals row.
 *
 * The native form is hidden by CSS only while html.pep-redeem-ready is present,
 * which this script adds once it has taken over. If the script fails to run the
 * native form stays visible, so redemption is never left without a control.
 */
( function () {
	'use strict';

	function money( value ) {
		var n = parseFloat( value );
		return '$' + ( isNaN( n ) ? 0 : n ).toFixed( 2 );
	}

	function nativeForm() {
		return document.querySelector( 'form.ywpar_apply_discounts' );
	}

	// True when a YITH redemption is currently on the cart. Its discount row is
	// the only marker left once the apply form is gone.
	function redemptionApplied( review ) {
		return !! review.querySelector( 'tr.cart-discount[class*="ywpar"], tr[class*="ywpar_discount"]' );
	}

	var refetching = false;
	var refetched = false;

	// YITH only prints its apply form on a full page load. If the page was loaded
	// with a redemption applied, removing it over AJAX leaves no form anywhere in
	// the document, so the card cannot be rebuilt and the block renders empty
	// until a reload. Fetch the checkout once in the background, lift the form out
	// of the response and park it hidden, so the card can return in place.
	function refetchNativeForm( done ) {
		if ( refetching || refetched || ! window.fetch ) {
			return;
		}

		refetching = true;

		window.fetch( window.location.pathname, { credentials: 'same-origin' } )
			.then( function ( response ) {
				return response.ok ? response.text() : '';
			} )
			.then( function ( html ) {
				refetching = false;

				if ( ! html ) {
					return;
				}

				var parsed = new DOMParser().parseFromString( html, 'text/html' );
				var fresh = parsed.querySelector( 'form.ywpar_apply_discounts' );

				// No form in a fresh load either: the balance is genuinely below the
				// minimum. Stop asking.
				refetched = true;

				if ( ! fresh ) {
					return;
				}

				var holder = document.getElementById( 'pep-redeem-native-holder' );

				if ( ! holder ) {
					holder = document.createElement( 'div' );
					holder.id = 'pep-redeem-native-holder';
					holder.hidden = true;
					document.body.appendChild( holder );
				}

				holder.innerHTML = '';
				holder.appendChild( document.importNode( fresh, true ) );

				done();
			} )
			.catch( function () {
				refetching = false;
				refetched = true;
			} );
	}

	function nativeButton() {
		return document.querySelector( 'form.ywpar_apply_discounts button, form.ywpar_apply_discounts input[type="submit"]' );
	}

	// YITH adds the applied discount as a totals row labelled "Redeem points";
	// reframe only that label. The amount and the native Remove link are untouched.
	function relabelAppliedLine( review ) {
		var cells = review.querySelectorAll( '.cart-discount th' );

		for ( var i = 0; i < cells.length; i++ ) {
			if ( /redeem points/i.test( cells[ i ].textContent || '' ) ) {
				cells[ i ].textContent = 'Cash back applied';
			}
		}
	}

	function buildCard( review ) {
		var subtotal = review.querySelector( '.cart-subtotal' );

		if ( ! subtotal ) {
			return null;
		}

		var row = document.createElement( 'tr' );
		row.className = 'pep-redeem-slot';
		row.innerHTML =
			'<td colspan="2">' +
				'<div class="pep-redeem">' +
					'<div class="pep-redeem__row">' +
						'<span class="pep-redeem__label">Cash back</span>' +
						'<span class="pep-redeem__balance"></span>' +
					'</div>' +
					'<button type="button" class="pep-redeem__btn"></button>' +
					'<div class="pep-redeem__note">Minimum redemption is $5.00.</div>' +
				'</div>' +
			'</td>';

		subtotal.parentNode.insertBefore( row, subtotal );

		row.querySelector( '.pep-redeem__btn' ).addEventListener( 'click', function ( event ) {
			event.preventDefault();

			var button = nativeButton();

			if ( button ) {
				button.click();
			}
		} );

		return row;
	}

	function sync() {
		var review = document.querySelector( '.fc-checkout-order-review' );

		if ( ! review ) {
			return;
		}

		// Enables the CSS that hides YITH's native form. Absent this class (script
		// did not run) the native form stays visible as a functional fallback.
		document.documentElement.classList.add( 'pep-redeem-ready' );

		relabelAppliedLine( review );

		var form = nativeForm();
		var card = document.querySelector( '.pep-redeem-slot' );

		// No apply form present. Either a redemption is applied (its Remove control
		// lives in the totals), or the form was never rendered on this page load
		// because the page opened with one applied and it has since been removed.
		if ( ! form ) {
			if ( card && card.parentNode ) {
				card.parentNode.removeChild( card );
			}

			// Nothing applied and no form: recover the form so the button returns
			// without a reload.
			if ( ! redemptionApplied( review ) ) {
				refetchNativeForm( run );
			}

			return;
		}

		// A form is available again, so a later removal may need a fresh one.
		refetched = false;

		// YITH renders the form inside a .woocommerce-cart-notice box that carries
		// its own border and tint; tag it so the CSS hides the whole box, not just
		// the form, leaving no empty styled band behind.
		var wrapper = form.closest( '.woocommerce-cart-notice' );

		if ( wrapper ) {
			wrapper.classList.add( 'pep-redeem-native-wrap' );
		}

		// Apply the full available balance; the posted points field is left at its
		// own maximum, in points, never converted.
		var maxDiscount = ( form.querySelector( '[name="ywpar_max_discount"]' ) || {} ).value || '0';
		var pointsMax = ( form.querySelector( '[name="ywpar_points_max"]' ) || {} ).value || '';
		var pointsField = form.querySelector( '[name="ywpar_input_points"]' );

		if ( pointsField && pointsMax ) {
			pointsField.value = pointsMax;
		}

		if ( ! card ) {
			card = buildCard( review );

			// Could not place the card (no subtotal row): fall back to the native
			// form by removing the hide gate.
			if ( ! card ) {
				document.documentElement.classList.remove( 'pep-redeem-ready' );
				return;
			}
		}

		card.querySelector( '.pep-redeem__balance' ).textContent = money( maxDiscount ) + ' available';
		card.querySelector( '.pep-redeem__btn' ).textContent = 'Apply ' + money( maxDiscount ) + ' cash back';
	}

	function run() {
		try {
			sync();
		} catch ( e ) {
			// On any failure, leave the native form visible.
			document.documentElement.classList.remove( 'pep-redeem-ready' );
		}
	}

	if ( 'loading' !== document.readyState ) {
		run();
	} else {
		document.addEventListener( 'DOMContentLoaded', run );
	}

	// Re-run after every AJAX review refresh (apply, remove, cart or shipping
	// change), when YITH re-renders and Fluid rebuilds the order review.
	if ( window.jQuery ) {
		jQuery( document.body ).on( 'updated_checkout', function () {
			// Twice: Fluid replaces the review fragment asynchronously, so a single
			// pass can miss the freshly-rendered applied "Redeem points" row. run()
			// is idempotent (the card is not duplicated, the relabel is a no-op once
			// applied).
			window.setTimeout( run, 80 );
			window.setTimeout( run, 600 );
		} );
	}
}() );
