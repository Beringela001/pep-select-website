/**
 * Bacteriostatic Water checkout upsell toggle.
 *
 * Turning the switch on adds one Bacteriostatic Water to the cart; turning it
 * off removes it. The switch moves optimistically on click and shows a subtle
 * in-progress state while the request is in flight, then reconciles against the
 * cart state the server reports back, so it can never drift from the real cart.
 * On success the mini-cart fragments and the checkout order review are both
 * refreshed, so the side cart, header count, and order total update without a
 * reload. On failure the switch rolls back to its pre-click state and an inline
 * message is shown; it is never left on with nothing added.
 */
( function ( $ ) {
	'use strict';

	if ( ! window.pepselectBacwater || ! window.pepselectBacwater.endpoint ) {
		return;
	}

	var SELECTOR = '[data-pepselect-bacwater-input]';
	var busy = false;

	function setMessage( $panel, text ) {
		var $msg = $panel.find( '.pepselect-bacwater__message' );

		if ( ! text ) {
			$msg.remove();
			return;
		}

		if ( ! $msg.length ) {
			$msg = $( '<p class="pepselect-bacwater__message" role="alert"></p>' );
			$panel.append( $msg );
		}

		$msg.text( text );
	}

	$( document ).on( 'change', SELECTOR, function () {
		var $input = $( this );
		var $panel = $input.closest( '.pepselect-bacwater' );
		var add = $input.is( ':checked' ) ? '1' : '0';

		// A request is already in flight. The checkbox is disabled below while it
		// runs, but this also protects the delegated handler if the order-review
		// row is re-rendered mid-request. Revert the visual state and wait.
		if ( busy ) {
			$input.prop( 'checked', '1' !== add );
			return;
		}

		busy = true;
		setMessage( $panel, '' );
		$panel.addClass( 'is-busy' );
		$input.prop( 'disabled', true );

		$.ajax( {
			type: 'POST',
			url: window.pepselectBacwater.endpoint,
			data: {
				nonce: window.pepselectBacwater.nonce,
				add: add,
			},
		} )
			.done( function ( response ) {
				// Reconcile the switch against the cart state the server reports
				// rather than trusting the click, so the two can never disagree.
				var inCart = !! ( response && response.data && response.data.in_cart );

				$input.prop( 'checked', inCart );

				// Refresh the mini-cart fragments (side cart, header count, any
				// cart-total pill) and the checkout order review, so the order
				// total and Square amount reflect the change without a reload.
				$( document.body ).trigger( 'wc_fragment_refresh' );
				$( document.body ).trigger( 'update_checkout' );
			} )
			.fail( function () {
				// Roll back to the pre-click state so the switch is never left on
				// with nothing added, and surface a retry hint inside the panel.
				$input.prop( 'checked', '1' !== add );
				setMessage( $panel, 'Could not update your cart. Please try again.' );
			} )
			.always( function () {
				$input.prop( 'disabled', false );
				$panel.removeClass( 'is-busy' );
				busy = false;
			} );
	} );
}( jQuery ) );
