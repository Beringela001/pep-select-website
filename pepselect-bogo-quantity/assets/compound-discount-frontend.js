( function () {
	'use strict';

	var source = window.pepselectAutomaticDiscounts || {};
	var codes = Array.isArray( source.codes ) ? source.codes.map( function ( code ) {
		return String( code ).trim().toLowerCase();
	} ) : [];

	if ( ! codes.length ) {
		return;
	}

	function isManaged( code ) {
		return codes.indexOf( String( code || '' ).trim().toLowerCase() ) !== -1;
	}

	function protectAutomaticDiscounts() {
		document.querySelectorAll( '.woocommerce-remove-coupon[data-coupon]' ).forEach( function ( control ) {
			if ( isManaged( control.getAttribute( 'data-coupon' ) ) ) {
				control.remove();
			}
		} );

		document.querySelectorAll( '.wc-block-components-chip' ).forEach( function ( chip ) {
			var text = String( chip.textContent || '' ).trim().toLowerCase();
			var managed = codes.some( function ( code ) {
				return text.indexOf( code ) === 0;
			} );
			if ( managed ) {
				chip.classList.add( 'pepselect-automatic-discount' );
				chip.querySelectorAll( 'button, [role="button"]' ).forEach( function ( control ) {
					control.remove();
				} );
			}
		} );
	}

	protectAutomaticDiscounts();
	new MutationObserver( protectAutomaticDiscounts ).observe( document.body, { childList: true, subtree: true } );
}() );
