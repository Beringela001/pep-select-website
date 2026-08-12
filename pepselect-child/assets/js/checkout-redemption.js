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

	// The $5.00 floor YITH enforces server-side, mirrored in the field.
	var MINIMUM_DOLLARS = 5;

	/**
	 * The ONLY place dollars become points.
	 *
	 * The figure the customer sees is DOLLARS; the field POSTed to YITH is
	 * POINTS. The rate is derived from YITH's own pair of fields
	 * (ywpar_points_max against ywpar_max_discount) rather than assumed, so it
	 * cannot drift if the store's rate changes, and the result is always clamped
	 * to the server-set maximum. Verified against production values
	 * (1120 points / $11.20): $5.00 -> 500, $7.00 -> 700, $20.00 -> 1120.
	 *
	 * @param {*} dollars     Requested amount, in dollars.
	 * @param {*} pointsMax   Server-set maximum, in points.
	 * @param {*} maxDollars  Server-set maximum, in dollars.
	 * @return {number} points, integer, never above pointsMax.
	 */
	function dollarsToPoints( dollars, pointsMax, maxDollars ) {
		var amount = parseFloat( dollars );
		var maxPts = parseInt( pointsMax, 10 );
		var maxUsd = parseFloat( maxDollars );

		if ( isNaN( amount ) || amount <= 0 || isNaN( maxPts ) || maxPts <= 0 ) {
			return 0;
		}

		var rate = ( ! isNaN( maxUsd ) && maxUsd > 0 ) ? ( maxPts / maxUsd ) : 0;

		if ( ! isFinite( rate ) || rate <= 0 ) {
			return 0;
		}

		var points = Math.round( amount * rate );

		if ( points > maxPts ) {
			points = maxPts;
		}

		return points < 0 ? 0 : points;
	}

	function money( value ) {
		var n = parseFloat( value );
		return '$' + ( isNaN( n ) ? 0 : n ).toFixed( 2 );
	}

	function nativeForm() {
		return document.querySelector( 'form.ywpar_apply_discounts' );
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
					'<div class="pep-redeem__controls">' +
						'<div class="pep-redeem__field">' +
							'<span class="pep-redeem__prefix" aria-hidden="true">$</span>' +
							'<input type="number" class="pep-redeem__input" inputmode="decimal" step="0.01" min="' + MINIMUM_DOLLARS + '" aria-label="Cash back amount in dollars" />' +
						'</div>' +
						'<button type="button" class="pep-redeem__max">Max</button>' +
						'<button type="button" class="pep-redeem__btn">Apply</button>' +
					'</div>' +
					'<div class="pep-redeem__note">Minimum redemption is $5.00.</div>' +
				'</div>' +
			'</td>';

		subtotal.parentNode.insertBefore( row, subtotal );

		var note = row.querySelector( '.pep-redeem__note' );
		var input = row.querySelector( '.pep-redeem__input' );

		// Apply the requested amount. Max writes the server maximum straight
		// through, with no conversion round-trip; any other amount goes through
		// dollarsToPoints once. The field written is always POINTS.
		//
		// The redemption is triggered by clicking YITH's own Apply button, which is
		// what YITH binds its AJAX handler to. A native form submit is not a
		// substitute: it fires but applies nothing.
		function apply( useMax ) {
			var form = nativeForm();

			if ( ! form ) {
				return;
			}

			var pointsField = form.querySelector( '[name="ywpar_input_points"]' );
			var pointsMax = ( form.querySelector( '[name="ywpar_points_max"]' ) || {} ).value || '0';
			var maxDollars = ( form.querySelector( '[name="ywpar_max_discount"]' ) || {} ).value || '0';

			if ( ! pointsField ) {
				return;
			}

			var points;

			if ( useMax ) {
				points = parseInt( pointsMax, 10 ) || 0;
			} else {
				var requested = parseFloat( input ? input.value : '' );

				if ( isNaN( requested ) || requested < MINIMUM_DOLLARS ) {
					if ( note ) {
						note.textContent = 'Enter ' + money( MINIMUM_DOLLARS ) + ' or more to redeem.';
					}

					if ( input ) {
						input.focus();
					}

					return;
				}

				points = dollarsToPoints( requested, pointsMax, maxDollars );
			}

			if ( points <= 0 ) {
				return;
			}

			pointsField.value = points;

			var button = nativeButton();

			if ( button ) {
				button.click();
			}
		}

		row.querySelector( '.pep-redeem__btn' ).addEventListener( 'click', function ( event ) {
			event.preventDefault();
			apply( false );
		} );

		row.querySelector( '.pep-redeem__max' ).addEventListener( 'click', function ( event ) {
			event.preventDefault();
			apply( true );
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

		// No apply form present: redemption is already applied (its Remove control
		// lives in the totals) or the balance is below the minimum. Drop our card.
		if ( ! form ) {
			if ( card && card.parentNode ) {
				card.parentNode.removeChild( card );
			}

			return;
		}

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

		var amountInput = card.querySelector( '.pep-redeem__input' );

		if ( amountInput ) {
			amountInput.max = maxDiscount;
			amountInput.placeholder = String( parseFloat( maxDiscount ).toFixed( 2 ) );
		}
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
