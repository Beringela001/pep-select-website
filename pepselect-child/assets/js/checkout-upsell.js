/**
 * Bacteriostatic Water checkout upsell toggle.
 *
 * Toggling the switch adds or removes the product through the WooCommerce AJAX
 * endpoint, then triggers update_checkout so the order summary and totals
 * re-render. The block is re-rendered server-side on that refresh, so the
 * switch state always reflects the real cart. A delegated handler keeps it
 * working after each re-render replaces the row.
 */
( function ( $ ) {
	'use strict';

	if ( ! window.pepselectBacwater || ! window.pepselectBacwater.endpoint ) {
		return;
	}

	$( document ).on( 'change', '[data-pepselect-bacwater-input]', function () {
		var input = $( this );
		var add = input.is( ':checked' ) ? '1' : '0';

		input.prop( 'disabled', true );

		$.ajax( {
			type: 'POST',
			url: window.pepselectBacwater.endpoint,
			data: {
				nonce: window.pepselectBacwater.nonce,
				add: add,
			},
		} )
			.done( function () {
				// The refreshed order review re-renders this row with the real
				// cart state and re-enables the input.
				$( document.body ).trigger( 'update_checkout' );
			} )
			.fail( function () {
				// Revert the visual state and let the customer try again.
				input.prop( 'disabled', false ).prop( 'checked', '1' !== add );
			} );
	} );
}( jQuery ) );
