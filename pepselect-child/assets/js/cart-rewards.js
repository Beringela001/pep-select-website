/**
 * Cart/checkout rewards note restyle (WEB M7).
 *
 * Same capture-and-restyle intent as the product pills, but the cart is a
 * WooCommerce block cart rendered client-side, so this runs in the browser:
 * it reads YITH Points & Rewards' own rendered "you will earn N Points" banner
 * (the plugin stays the source of truth), converts the points to dollars to
 * match the cash-back framing used everywhere else (100 points = $1.00), drops
 * the trophy, and restyles it as the cyan cash-back pill.
 *
 * Keyed on YITH's rendered text rather than a class, so it works regardless of
 * the plugin's markup/version. If the banner is absent (e.g. logged-out), this
 * is a no-op and the page is unchanged.
 */
( function () {
	'use strict';

	var POINTS_PER_DOLLAR = 100; // 100 points = $1.00.
	var EARN_RE = /you will earn\s+([\d.,]+)\s*point/i;
	var COIN_ICON =
		'<svg class="pepselect-pill__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" width="16" height="16"><path fill="currentColor" d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8Zm.9-8.7c-1.6-.4-2.1-.7-2.1-1.3s.5-.9 1.3-.9a1.7 1.7 0 0 1 1.6.9l1.5-.9a3.1 3.1 0 0 0-2-1.5V6.5h-1.8v1.3c-1.3.3-2.2 1.2-2.2 2.5 0 1.7 1.4 2.3 2.9 2.7 1.4.3 1.7.7 1.7 1.3s-.6 1-1.4 1a2 2 0 0 1-1.9-1.2l-1.6.9a3.5 3.5 0 0 0 2.3 1.7v1.3h1.8v-1.3c1.4-.3 2.3-1.2 2.3-2.6 0-1.8-1.5-2.4-3.1-2.8Z"></path></svg>';

	function toDollars( points ) {
		var dollars = points / POINTS_PER_DOLLAR;
		return '$' + dollars.toFixed( 2 );
	}

	function restyle() {
		var candidates = document.querySelectorAll( 'div, p, span, li' );

		for ( var i = 0; i < candidates.length; i++ ) {
			var el = candidates[ i ];

			if ( el.getAttribute( 'data-pepselect-rewards' ) ) {
				continue;
			}

			var text = el.textContent || '';
			if ( ! EARN_RE.test( text ) ) {
				continue;
			}

			// Only act on the leaf-most element carrying the message, so we do
			// not restyle an outer wrapper.
			var childCarries = false;
			for ( var c = 0; c < el.children.length; c++ ) {
				if ( EARN_RE.test( el.children[ c ].textContent || '' ) ) {
					childCarries = true;
					break;
				}
			}
			if ( childCarries ) {
				continue;
			}

			var match = text.match( EARN_RE );
			var points = match ? parseInt( match[ 1 ].replace( /[.,]/g, '' ), 10 ) : 0;
			if ( ! points ) {
				continue;
			}

			el.setAttribute( 'data-pepselect-rewards', '1' );
			el.className = 'pepselect-pill pepselect-cashback-pill pepselect-rewards-note';
			el.innerHTML =
				COIN_ICON +
				'<span class="pepselect-pill__label">You’ll earn ' +
				toDollars( points ) +
				' in cash back</span>';
		}
	}

	function init() {
		restyle();

		// The block cart re-renders on quantity/coupon changes; re-apply then.
		if ( window.MutationObserver && document.body ) {
			var observer = new MutationObserver( function () {
				restyle();
			} );
			observer.observe( document.body, { childList: true, subtree: true } );
		}
	}

	if ( 'loading' !== document.readyState ) {
		init();
	} else {
		document.addEventListener( 'DOMContentLoaded', init );
	}
}() );
