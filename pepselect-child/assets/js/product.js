/**
 * Single compound page: source disclosure toggle and image lightbox.
 */
( function () {
	'use strict';

	document.addEventListener( 'click', ( event ) => {
		const toggle = event.target.closest( '[data-pepselect-sources-toggle]' );

		if ( toggle ) {
			const panel = document.getElementById( toggle.getAttribute( 'aria-controls' ) );
			if ( panel ) {
				const open = ! panel.hidden;
				panel.hidden = open;
				toggle.setAttribute( 'aria-expanded', String( ! open ) );
				toggle.classList.toggle( 'is-open', ! open );
			}
			return;
		}

		const opener = event.target.closest( '[data-pepselect-lightbox-open]' );
		if ( opener ) {
			const box = document.querySelector( '[data-pepselect-lightbox]' );
			const img = document.querySelector( '.pepselect-compound-page__image' );
			if ( box && img ) {
				const full = img.getAttribute( 'data-full' ) || img.src;
				box.querySelector( '.pepselect-lightbox__image' ).src = full;
				box.hidden = false;
			}
			return;
		}

		if ( event.target.closest( '[data-pepselect-lightbox-close]' ) || event.target.matches( '[data-pepselect-lightbox]' ) ) {
			const box = document.querySelector( '[data-pepselect-lightbox]' );
			if ( box ) {
				box.hidden = true;
			}
		}
	} );

	document.addEventListener( 'keydown', ( event ) => {
		if ( 'Escape' === event.key ) {
			const box = document.querySelector( '[data-pepselect-lightbox]' );
			if ( box && ! box.hidden ) {
				box.hidden = true;
			}
		}
	} );
}() );
