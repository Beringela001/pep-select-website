/**
 * Mobile checkout order flow.
 *
 * Fluid Checkout PRO moves #order_review into its collapsible cart summary on
 * narrow screens, leaving only payment and Place order in the bottom summary
 * card. Pep Select's approved flow keeps the left checkout column first and one
 * complete, always-expanded order card after it. Move the existing node (never
 * clone it) back to Fluid's main placeholder so all WooCommerce/YITH handlers
 * and form fields remain intact.
 */
( function () {
	'use strict';

	var mobileQuery = window.matchMedia( '(max-width: 749px)' );
	var scheduled = false;

	function syncMobileOrder() {
		scheduled = false;

		if ( ! mobileQuery.matches ) {
			document.body.classList.remove( 'pepselect-mobile-order-review-expanded' );
			return;
		}

		var summary = document.querySelector( '.fc-sidebar .fc-checkout-order-review' );
		var placeholder = summary ? summary.querySelector( '.fc-order-review-table__placeholder--main' ) : null;
		var orderReview = document.getElementById( 'order_review' );

		if ( ! summary || ! placeholder || ! orderReview || ! placeholder.parentNode ) {
			// Fail safe: without the order table in its permanent mobile position,
			// leave Fluid's collapsible cart visible and functional.
			document.body.classList.remove( 'pepselect-mobile-order-review-expanded' );
			return;
		}

		if ( orderReview.parentNode !== placeholder.parentNode || orderReview.previousElementSibling !== placeholder ) {
			placeholder.insertAdjacentElement( 'afterend', orderReview );
		}

		// CSS hides Fluid's redundant top dropdown only after the real order table
		// is confirmed inside the bottom card.
		document.body.classList.add( 'pepselect-mobile-order-review-expanded' );
	}

	function scheduleSync() {
		if ( scheduled ) {
			return;
		}

		scheduled = true;
		window.requestAnimationFrame( syncMobileOrder );
	}

	if ( 'loading' !== document.readyState ) {
		scheduleSync();
	} else {
		document.addEventListener( 'DOMContentLoaded', scheduleSync );
	}

	if ( mobileQuery.addEventListener ) {
		mobileQuery.addEventListener( 'change', scheduleSync );
	} else {
		mobileQuery.addListener( scheduleSync );
	}

	window.addEventListener( 'resize', scheduleSync, { passive: true } );

	if ( window.jQuery ) {
		window.jQuery( document.body ).on( 'updated_checkout', scheduleSync );
	}

	// Fluid can relocate or replace the order-review fragment after its own
	// responsive and AJAX handlers run. Observe only structural changes and
	// schedule one idempotent correction per animation frame.
	var wrapper = document.getElementById( 'fc-wrapper' );

	if ( wrapper && window.MutationObserver ) {
		new window.MutationObserver( scheduleSync ).observe( wrapper, {
			childList: true,
			subtree: true
		} );
	}
}() );
