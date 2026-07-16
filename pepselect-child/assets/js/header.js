( function () {
	'use strict';

	const header = document.querySelector( '[data-pepselect-header-preview]' );

	if ( ! header ) {
		return;
	}

	const toggle = header.querySelector( '[data-pepselect-menu-toggle]' );
	const panel = header.querySelector( '[data-pepselect-menu-panel]' );
	const mobileQuery = window.matchMedia( '(max-width: 1024px)' );

	if ( ! toggle || ! panel ) {
		return;
	}

	const closeMenu = ( returnFocus = false ) => {
		panel.classList.remove( 'is-open' );
		toggle.setAttribute( 'aria-expanded', 'false' );

		if ( returnFocus ) {
			toggle.focus();
		}
	};

	const openMenu = () => {
		panel.classList.add( 'is-open' );
		toggle.setAttribute( 'aria-expanded', 'true' );
	};

	toggle.addEventListener( 'click', () => {
		if ( 'true' === toggle.getAttribute( 'aria-expanded' ) ) {
			closeMenu();
		} else {
			openMenu();
		}
	} );

	document.addEventListener( 'keydown', ( event ) => {
		if ( 'Escape' === event.key && 'true' === toggle.getAttribute( 'aria-expanded' ) ) {
			closeMenu( true );
		}
	} );

	panel.addEventListener( 'click', ( event ) => {
		if ( mobileQuery.matches && event.target instanceof Element && event.target.closest( 'a' ) ) {
			closeMenu();
		}
	} );

	const resetForViewport = () => {
		if ( ! mobileQuery.matches ) {
			closeMenu();
		}
	};

	if ( 'function' === typeof mobileQuery.addEventListener ) {
		mobileQuery.addEventListener( 'change', resetForViewport );
	} else {
		mobileQuery.addListener( resetForViewport );
	}
}() );
