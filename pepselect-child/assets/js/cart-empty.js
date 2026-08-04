/**
 * Empty-cart coded list on the client re-render path.
 *
 * On a normal page load the coded list is rendered server-side (see
 * inc/cart-empty.php). But when the last item is removed without a reload,
 * WooCommerce Blocks re-renders the empty cart client-side and re-injects its
 * own "Newest products" grid (woocommerce/product-new), which never runs the
 * PHP render_block filter, so the coded list is lost.
 *
 * This keeps a single source of truth: the same PHP-rendered list is emitted
 * once into a <template> on the page, and this script clones those nodes into
 * the empty-cart block when the stock grid reappears. No card markup is
 * duplicated in JavaScript. The stock grid is hidden in CSS.
 */
( function () {
	'use strict';

	function templateEl() {
		return document.getElementById( 'ps-empty-cart-products-template' );
	}

	function apply() {
		var block = document.querySelector( '.wp-block-woocommerce-empty-cart-block' );

		if ( ! block ) {
			return;
		}

		// Already present (server render, or a previous pass): nothing to do.
		if ( block.querySelector( '.ps-empty-products' ) ) {
			return;
		}

		var grid = block.querySelector( '.wc-block-grid.wp-block-product-new, [data-block-name="woocommerce/product-new"]' );
		var shown = ! block.hidden && 'none' !== window.getComputedStyle( block ).display;

		// Act once the empty view has rendered its product area: either the stock
		// grid is present (the client re-render happened) or the block is visible
		// (server-rendered empty state). Using the grid as the signal avoids a
		// race with the block's display toggling mid-transition.
		if ( ! grid && ! shown ) {
			return;
		}

		var tmpl = templateEl();

		if ( ! tmpl ) {
			return;
		}

		var mount = document.createElement( 'div' );
		mount.className = 'ps-empty-products-mount';

		if ( tmpl.content ) {
			mount.appendChild( tmpl.content.cloneNode( true ) );
		} else {
			mount.innerHTML = tmpl.innerHTML;
		}

		// Insert as a sibling before WooCommerce's grid so the coded list sits
		// where the stock list would have been; the grid stays in the DOM but is
		// hidden by CSS.
		if ( grid && grid.parentNode ) {
			grid.parentNode.insertBefore( mount, grid );
		} else {
			block.appendChild( mount );
		}
	}

	var timer = null;

	function schedule() {
		if ( timer ) {
			window.clearTimeout( timer );
		}

		// Trailing debounce, so apply runs after the re-render settles rather
		// than on the first mutation of a multi-step transition.
		timer = window.setTimeout( apply, 60 );
	}

	function start() {
		apply();

		// A few delayed passes catch a late first render without needing an
		// observer event.
		window.setTimeout( apply, 200 );
		window.setTimeout( apply, 600 );

		if ( window.MutationObserver ) {
			// Observe the document body: WooCommerce can replace the cart block
			// wholesale on a re-render, which would detach an observer bound to it.
			new window.MutationObserver( schedule ).observe( document.body, {
				childList: true,
				subtree: true,
			} );
		}
	}

	if ( 'loading' !== document.readyState ) {
		start();
	} else {
		document.addEventListener( 'DOMContentLoaded', start );
	}
}() );
