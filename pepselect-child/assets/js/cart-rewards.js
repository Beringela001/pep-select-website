/**
 * Cart/checkout rewards note restyle (WEB M7).
 *
 * Same capture-and-restyle intent as the product pills, but the cart is a
 * WooCommerce block cart rendered client-side, so this runs in the browser.
 * It reads YITH Points & Rewards' own rendered "you will earn N Points" banner
 * (the plugin stays the source of truth), drops the trophy, converts points to
 * dollars to match the cash-back framing used everywhere else (100 points =
 * $1.00), and presents it as an enlarged cyan cash-back pill centered above the
 * cart.
 *
 * The value is never computed here. When cart totals change (coupon applied or
 * removed, quantity changed) the cart page is re-requested and YITH's freshly
 * rendered value is re-captured, so YITH's own rules (such as no points on the
 * coupon-discounted amount) always hold.
 *
 * Keyed on YITH's rendered text rather than a class, so it works regardless of
 * the plugin's markup/version. If the banner is absent (for example when logged
 * out) this is a no-op and the page is unchanged.
 */
( function () {
	'use strict';

	var POINTS_PER_DOLLAR = 100; // 100 points = $1.00.
	var EARN_RE = /you will earn\s+([\d.,]+)\s*point/i;
	var COIN_ICON =
		'<svg class="pepselect-pill__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8Zm.9-8.7c-1.6-.4-2.1-.7-2.1-1.3s.5-.9 1.3-.9a1.7 1.7 0 0 1 1.6.9l1.5-.9a3.1 3.1 0 0 0-2-1.5V6.5h-1.8v1.3c-1.3.3-2.2 1.2-2.2 2.5 0 1.7 1.4 2.3 2.9 2.7 1.4.3 1.7.7 1.7 1.3s-.6 1-1.4 1a2 2 0 0 1-1.9-1.2l-1.6.9a3.5 3.5 0 0 0 2.3 1.7v1.3h1.8v-1.3c1.4-.3 2.3-1.2 2.3-2.6 0-1.8-1.5-2.4-3.1-2.8Z"></path></svg>';

	var host = null;
	var refreshTimer = null;
	var inFlight = false;

	function toDollars( points ) {
		return '$' + ( points / POINTS_PER_DOLLAR ).toFixed( 2 );
	}

	/**
	 * Find YITH's rendered earn message inside a document/element and return the
	 * points it states, or 0 when absent. Nothing is calculated here beyond
	 * reading the number YITH printed.
	 */
	function capturePoints( root ) {
		var nodes = root.querySelectorAll( 'div, p, span, li' );

		for ( var i = 0; i < nodes.length; i++ ) {
			var el = nodes[ i ];

			if ( el.classList && el.classList.contains( 'pepselect-rewards-note' ) ) {
				continue;
			}

			var text = el.textContent || '';
			if ( ! EARN_RE.test( text ) ) {
				continue;
			}

			// Only the leaf-most element carrying the message.
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

			// Hide YITH's own banner in the live document; our pill replaces it.
			if ( el.ownerDocument === document ) {
				el.classList.add( 'pepselect-rewards-source' );
			}

			var match = text.match( EARN_RE );
			var points = match ? parseInt( match[ 1 ].replace( /[.,]/g, '' ), 10 ) : 0;

			if ( points ) {
				return points;
			}
		}

		return 0;
	}

	/**
	 * Ensure the centered host sits at the top of the cart (or checkout) column.
	 */
	function ensureHost() {
		if ( host && document.body.contains( host ) ) {
			return host;
		}

		var anchor =
			document.querySelector( '.wp-block-woocommerce-cart' ) ||
			document.querySelector( '.wc-block-cart' ) ||
			document.querySelector( '.woocommerce-cart-form' ) ||
			document.querySelector( '.wc-block-checkout' ) ||
			document.querySelector( '.woocommerce' );

		if ( ! anchor ) {
			return null;
		}

		host = document.createElement( 'div' );
		host.className = 'pepselect-rewards-note-wrap';
		anchor.insertBefore( host, anchor.firstChild );

		return host;
	}

	function render( points ) {
		var mount = ensureHost();

		if ( ! mount ) {
			return;
		}

		if ( ! points ) {
			mount.innerHTML = '';
			return;
		}

		mount.innerHTML =
			'<span class="pepselect-pill pepselect-cashback-pill pepselect-rewards-note">' +
			COIN_ICON +
			'<span class="pepselect-pill__label">You’ll earn ' +
			toDollars( points ) +
			' in cash back</span></span>';
	}

	/**
	 * Re-request the cart page and re-capture YITH's freshly rendered value.
	 * The number is always YITH's, never recomputed here.
	 */
	function refreshFromServer() {
		if ( inFlight ) {
			return;
		}

		inFlight = true;

		window
			.fetch( window.location.href, {
				credentials: 'same-origin',
				headers: { 'X-Requested-With': 'XMLHttpRequest' },
			} )
			.then( function ( response ) {
				return response.text();
			} )
			.then( function ( html ) {
				var parsed = new window.DOMParser().parseFromString( html, 'text/html' );
				render( capturePoints( parsed ) );
			} )
			.catch( function () {
				/* Leave the current value in place if the refresh fails. */
			} )
			.then( function () {
				inFlight = false;
			} );
	}

	function scheduleRefresh() {
		window.clearTimeout( refreshTimer );
		refreshTimer = window.setTimeout( refreshFromServer, 700 );
	}

	function init() {
		render( capturePoints( document ) );

		// Classic/jQuery cart events.
		if ( window.jQuery ) {
			window
				.jQuery( document.body )
				.on(
					'updated_cart_totals updated_wc_div wc_fragments_refreshed applied_coupon removed_coupon added_to_cart',
					scheduleRefresh
				);
		}

		// Block cart: totals re-render on quantity/coupon changes.
		if ( window.MutationObserver && document.body ) {
			var observer = new MutationObserver( function ( mutations ) {
				for ( var i = 0; i < mutations.length; i++ ) {
					var target = mutations[ i ].target;

					if ( ! target || ! target.closest ) {
						continue;
					}

					// Ignore our own pill updates.
					if ( target.closest( '.pepselect-rewards-note-wrap' ) ) {
						continue;
					}

					if (
						target.closest(
							'.wc-block-cart__totals, .wc-block-components-totals-item, .wc-block-cart-items, .cart_totals, .woocommerce-cart-form'
						)
					) {
						scheduleRefresh();
						return;
					}
				}
			} );

			observer.observe( document.body, { childList: true, subtree: true, characterData: true } );
		}
	}

	if ( 'loading' !== document.readyState ) {
		init();
	} else {
		document.addEventListener( 'DOMContentLoaded', init );
	}
}() );
