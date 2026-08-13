/**
 * Cart/checkout rewards note restyle (WEB M7).
 *
 * YITH Points & Rewards injects its cart banner client-side from its own blocks
 * bundle (assets/js/blocks/frontend_blocks.js), so there is no server-rendered
 * banner in the cart HTML to capture in PHP. This mirrors whatever YITH renders:
 * it reads the points from YITH's banner (the plugin stays the source of truth),
 * drops the trophy, converts to dollars to match the cash-back framing used
 * site-wide (100 points = $1.00), and shows it as an enlarged cyan pill centered
 * above the cart.
 *
 * Deliberately does no network work. An earlier version re-fetched the whole
 * cart page and re-parsed it to refresh the value; that was both useless (the
 * banner is not in the fetched HTML, because YITH injects it) and expensive
 * (each fetch triggered the slow cart request), which made the pill appear late
 * and then flicker. Live updates now come for free: YITH re-renders its own
 * banner when the cart changes, and this mirrors it. Work per update is one
 * cheap DOM scan, coalesced to at most once per animation frame.
 */
( function () {
	'use strict';

	var POINTS_PER_DOLLAR = 100; // 100 points = $1.00.
	var EARN_RE = /you will earn\s+([\d.,]+)\s*point/i;
	var COIN_ICON =
		'<svg class="pepselect-pill__icon" aria-hidden="true" focusable="false" viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8Zm.9-8.7c-1.6-.4-2.1-.7-2.1-1.3s.5-.9 1.3-.9a1.7 1.7 0 0 1 1.6.9l1.5-.9a3.1 3.1 0 0 0-2-1.5V6.5h-1.8v1.3c-1.3.3-2.2 1.2-2.2 2.5 0 1.7 1.4 2.3 2.9 2.7 1.4.3 1.7.7 1.7 1.3s-.6 1-1.4 1a2 2 0 0 1-1.9-1.2l-1.6.9a3.5 3.5 0 0 0 2.3 1.7v1.3h1.8v-1.3c1.4-.3 2.3-1.2 2.3-2.6 0-1.8-1.5-2.4-3.1-2.8Z"></path></svg>';

	var host = null;
	var lastPoints = 0;
	var scanQueued = false;
	var CART_COUPON_TOGGLE =
		'.wp-block-woocommerce-cart-order-summary-coupon-form-block ' +
		'.wc-block-components-panel__button[aria-expanded="false"]';

	/** Keep the cart's existing Woo coupon form mounted instead of collapsed. */
	function expandCartCoupon() {
		if ( ! document.body || ! document.body.classList.contains( 'woocommerce-cart' ) ) {
			return;
		}

		var toggle = document.querySelector( CART_COUPON_TOGGLE );

		if ( toggle ) {
			toggle.click();
		}
	}

	function toDollars( points ) {
		return '$' + ( points / POINTS_PER_DOLLAR ).toFixed( 2 );
	}

	/** Read the points from YITH's rendered banner. Nothing is calculated. */
	function capturePoints() {
		var nodes = document.querySelectorAll( 'div, p, span, li' );

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

			// Hide YITH's own banner; our pill presents the same value.
			if ( ! el.classList.contains( 'pepselect-rewards-source' ) ) {
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

	/** Centered host at the top of the cart (or checkout) column. */
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
		// An empty read means we could not capture, not that nothing is earned;
		// never blank a value already on screen.
		if ( ! points || points === lastPoints ) {
			return;
		}

		var mount = ensureHost();

		if ( ! mount ) {
			return;
		}

		lastPoints = points;

		mount.innerHTML =
			'<span class="pepselect-pill pepselect-cashback-pill pepselect-rewards-note">' +
			COIN_ICON +
			'<span class="pepselect-pill__label">You’ll earn ' +
			toDollars( points ) +
			' in cash back</span></span>';
	}

	/** Coalesce to at most one scan per frame so bursts cost one pass. */
	function queueScan() {
		if ( scanQueued ) {
			return;
		}

		scanQueued = true;

		window.requestAnimationFrame( function () {
			scanQueued = false;
			expandCartCoupon();
			render( capturePoints() );
		} );
	}

	function init() {
		expandCartCoupon();
		render( capturePoints() );

		if ( window.MutationObserver && document.body ) {
			var observer = new MutationObserver( function ( mutations ) {
				for ( var i = 0; i < mutations.length; i++ ) {
					var target = mutations[ i ].target;

					// Ignore our own writes so we never feed ourselves.
					if ( target && target.closest && target.closest( '.pepselect-rewards-note-wrap' ) ) {
						continue;
					}

					queueScan();
					return;
				}
			} );

			// childList only: no characterData, which fires far more often.
			observer.observe( document.body, { childList: true, subtree: true } );
		}
	}

	if ( 'loading' !== document.readyState ) {
		init();
	} else {
		document.addEventListener( 'DOMContentLoaded', init );
	}
}() );
