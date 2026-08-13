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
 * SAFETY: the value POSTed to YITH stays in points. The customer-facing dollar
 * amount is converted once from YITH's own live points/discount pair, clamped
 * to YITH's server-set maximum, then written to YITH's native points field.
 * Apply triggers YITH's own bound control; Max only fills the visible field.
 *
 * The native form is hidden by CSS only while html.pep-redeem-ready is present,
 * which this script adds once it has taken over. If the script fails to run the
 * native form stays visible, so redemption is never left without a control.
 */
( function () {
	'use strict';

	// The $5.00 floor YITH enforces server-side, mirrored in the field.
	var MINIMUM_DOLLARS = 5;
	var redemptionState = null;

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

	function readRedemptionState( form ) {
		if ( ! form ) {
			return redemptionState;
		}

		var pointsMax = ( form.querySelector( '[name="ywpar_points_max"]' ) || {} ).value || '0';
		var maxDollars = ( form.querySelector( '[name="ywpar_max_discount"]' ) || {} ).value || '0';
		var nonce = ( form.querySelector( '[name="ywpar_input_points_nonce"]' ) || {} ).value || '';
		var rateMethod = ( form.querySelector( '[name="ywpar_rate_method"]' ) || {} ).value || 'fixed';

		if ( parseInt( pointsMax, 10 ) > 0 && parseFloat( maxDollars ) > 0 ) {
			redemptionState = {
				pointsMax: pointsMax,
				maxDollars: maxDollars,
				nonce: nonce,
				rateMethod: rateMethod
			};
		}

		return redemptionState;
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
		// The slot is rendered server-side in document order (between the applied
		// pills and the BAC card). Without it there is nowhere correct to put the
		// card, so the native form is left visible instead.
		var row = review.querySelector( 'tr.pep-redeem-slot' );

		if ( ! row ) {
			return null;
		}

		row.innerHTML =
			'<td colspan="2" class="pepselect-panel-cell">' +
				'<div class="pep-redeem">' +
					'<div class="pep-redeem__label">REDEEM CASH BACK <span class="pep-redeem__balance"></span></div>' +
					'<div class="pep-redeem__controls">' +
						'<div class="pep-redeem__field">' +
							'<span class="pep-redeem__prefix" aria-hidden="true">$</span>' +
							'<input type="text" class="pep-redeem__input" inputmode="decimal" value="0" autocomplete="off" aria-label="Cash back amount in dollars" />' +
						'</div>' +
						'<button type="button" class="pep-redeem__btn">Apply</button>' +
						'<button type="button" class="pep-redeem__max">Max</button>' +
					'</div>' +
					'<div class="pep-redeem__note">Minimum redemption is $5.00.</div>' +
				'</div>' +
			'</td>';

		var note = row.querySelector( '.pep-redeem__note' );
		var input = row.querySelector( '.pep-redeem__input' );
		var applyButton = row.querySelector( '.pep-redeem__btn' );
		var maxButton = row.querySelector( '.pep-redeem__max' );

		function resetNote() {
			if ( note ) {
				note.textContent = 'Minimum redemption is $5.00.';
			}
		}

		function setBusy( busy ) {
			var card = row.querySelector( '.pep-redeem' );

			if ( card ) {
				card.classList.toggle( 'is-loading', busy );
				card.setAttribute( 'aria-busy', busy ? 'true' : 'false' );
			}

			if ( applyButton ) {
				applyButton.disabled = busy;
			}

			if ( maxButton ) {
				maxButton.disabled = busy;
			}

			if ( input ) {
				input.disabled = busy;
			}
		}

		// Apply the requested amount. The field written is always POINTS.
		//
		// The redemption is triggered by clicking YITH's own Apply button, which is
		// what YITH binds its AJAX handler to. A native form submit is not a
		// substitute: it fires but applies nothing.
		function apply() {
			var form = nativeForm();
			var state = readRedemptionState( form );

			if ( ! state ) {
				return;
			}

			var pointsField = form ? form.querySelector( '[name="ywpar_input_points"]' ) : null;

			var requested = parseFloat( input ? input.value : '' );

			if ( isNaN( requested ) || requested < MINIMUM_DOLLARS ) {
				if ( note ) {
					note.textContent = 'Enter ' + money( MINIMUM_DOLLARS ) + ' or more to redeem.';
				}

				if ( input ) {
					input.focus();
					input.select();
				}

				return;
			}

			var points = dollarsToPoints( requested, state.pointsMax, state.maxDollars );

			if ( points <= 0 ) {
				return;
			}

			if ( pointsField ) {
				pointsField.value = points;
			}

			var button = nativeButton();

			if ( button ) {
				// YITH's AJAX handler is bound to this click. Its native submit fallback
				// is what produced Chrome's "Leave site?" warning whenever that handler
				// was late or absent, so keep the click and remove only the navigation.
				button.type = 'button';
				setBusy( true );
				button.click();

				// A successful refresh replaces this card. If YITH returns no refresh,
				// recover the controls instead of leaving the checkout locked.
				window.setTimeout( function () {
					if ( document.documentElement.contains( row ) ) {
						setBusy( false );
					}
				}, 4000 );
			} else if ( window.jQuery && window.yith_ywpar_general && window.yith_ywpar_general.wc_ajax_url ) {
				// After cash back is removed, YITH removes the coupon but does not put
				// its native form back into Fluid Checkout's fragment. Use the exact
				// endpoint and payload from YITH 4.27.0 so redemption remains reusable
				// without a reload or an inert re-created form.
				setBusy( true );
				window.jQuery.ajax( {
					type: 'POST',
					url: window.yith_ywpar_general.wc_ajax_url.toString().replace( '%%endpoint%%', 'ywpar_apply_points' ),
					data: {
						ywpar_points_max: state.pointsMax,
						ywpar_max_discount: state.maxDollars,
						ywpar_rate_method: state.rateMethod,
						ywpar_input_points_nonce: state.nonce,
						ywpar_input_points: points,
						ywpar_input_points_check: 1
					},
					dataType: 'html'
				} ).fail( function () {
					setBusy( false );

					if ( note ) {
						note.textContent = 'Cash back could not be applied. Please try again.';
					}
				} ).done( function () {
					window.jQuery( document.body ).trigger( 'update_checkout' );
				} );
			}
		}

		function fillMaximum() {
			var state = readRedemptionState( nativeForm() );
			var maxDollars = state ? state.maxDollars : '0';

			if ( ! input ) {
				return;
			}

			input.value = ( parseFloat( maxDollars ) || 0 ).toFixed( 2 );
			resetNote();
			input.focus();
			input.select();
		}

		if ( input ) {
			input.addEventListener( 'focus', function () {
				input.select();
			} );

			input.addEventListener( 'input', resetNote );

			input.addEventListener( 'blur', function () {
				if ( '' === input.value || isNaN( parseFloat( input.value ) ) ) {
					input.value = '0';
				}
			} );
		}

		applyButton.addEventListener( 'click', function ( event ) {
			event.preventDefault();
			apply();
		} );

		maxButton.addEventListener( 'click', function ( event ) {
			event.preventDefault();
			fillMaximum();
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
		var state = readRedemptionState( form );

		// The slot row is rendered server-side and starts EMPTY, so its presence
		// does not mean the card has been built. Treating it as a built card is
		// what broke 0.20.0-beta.27: buildCard() was skipped, the balance lookup
		// below threw, the catch stripped html.pep-redeem-ready, and YITH's raw
		// points bar became visible again. Test for the card itself.
		var card = document.querySelector( '.pep-redeem' ) ? document.querySelector( 'tr.pep-redeem-slot' ) : null;

		// The slot is absent while cash back is applied. Once removal restores the
		// slot, the remembered server-verified values can rebuild the card at 0
		// immediately even though YITH does not re-render its native form.
		var slot = review.querySelector( 'tr.pep-redeem-slot' );

		if ( ! slot || ! state ) {
			if ( card && card.parentNode ) {
				card.parentNode.removeChild( card );
			}

			return;
		}

		// YITH renders the form inside a .woocommerce-cart-notice box that carries
		// its own border and tint; tag it so the CSS hides the whole box, not just
		// the form, leaving no empty styled band behind.
		var wrapper = form ? form.closest( '.woocommerce-cart-notice' ) : null;

		if ( wrapper ) {
			wrapper.classList.add( 'pep-redeem-native-wrap' );
		}

		// Apply the full available balance; the posted points field is left at its
		// own maximum, in points, never converted.
		var maxDiscount = state.maxDollars;
		var pointsMax = state.pointsMax;
		var pointsField = form ? form.querySelector( '[name="ywpar_input_points"]' ) : null;

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

		card.querySelector( '.pep-redeem__balance' ).textContent =
			'(YOU HAVE ' + money( maxDiscount ).toUpperCase() + ')';

		var amountInput = card.querySelector( '.pep-redeem__input' );

		if ( amountInput ) {
			amountInput.max = maxDiscount;
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
			// Paint the new state immediately. The short fallback covers Fluid's
			// occasional follow-up fragment swap without the old 600ms visible lag.
			run();
			window.setTimeout( run, 120 );
		} );
	}

	// WooCommerce removal still needs its server round-trip, but the click should
	// never feel dead while that happens. The fragment replaces the marked pill
	// on success, and a failed refresh leaves it visibly recoverable on reload.
	document.addEventListener( 'click', function ( event ) {
		var remove = event.target.closest ? event.target.closest( '.pepselect-applied__x' ) : null;

		if ( remove ) {
			var pill = remove.closest( '.pepselect-applied' );

			if ( pill ) {
				pill.classList.add( 'is-removing' );
				pill.setAttribute( 'aria-busy', 'true' );
			}
		}
	}, true );
}() );
